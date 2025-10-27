<?php

namespace App\Services;

use App\Models\FcmToken;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Google\Auth\ApplicationDefaultCredentials;
use Google\Auth\Credentials\ServiceAccountCredentials;

class FcmService
{
    private $accessToken;
    private $projectId;
    private $fcmUrl;

    public function __construct()
    {
        $this->projectId = config('services.firebase.project_id');
        $this->fcmUrl = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";
        $this->accessToken = $this->getAccessToken();
    }

    /**
     * Get OAuth2 access token for HTTP v1 API
     */
    private function getAccessToken()
    {
        try {
            $useHttpV1 = config('services.firebase.use_http_v1', true);

            if (!$useHttpV1) {
                // Fallback to legacy API
                return config('services.firebase.server_key');
            }

            // Try to get service account from JSON file first
            $serviceAccount = $this->getServiceAccountFromFile();

            if (!$serviceAccount) {
                // Fallback to environment variable
                $serviceAccountKey = config('services.firebase.service_account_key');

                if (!$serviceAccountKey) {
                    Log::warning('Firebase service account key not configured, falling back to server key');
                    return config('services.firebase.server_key');
                }

                // Parse service account key from environment
                $serviceAccount = json_decode($serviceAccountKey, true);

                if (!$serviceAccount) {
                    throw new \Exception('Invalid service account key format');
                }
            }

            // Create credentials
            $credentials = new ServiceAccountCredentials(
                'https://www.googleapis.com/auth/firebase.messaging',
                $serviceAccount
            );

            // Get access token
            $token = $credentials->fetchAuthToken();

            if (!isset($token['access_token'])) {
                throw new \Exception('Failed to obtain access token');
            }

            return $token['access_token'];
        } catch (\Exception $e) {
            Log::error('Failed to get Firebase access token: ' . $e->getMessage());

            // Fallback to server key for legacy API
            Log::info('Falling back to legacy FCM API');
            return config('services.firebase.server_key');
        }
    }

    /**
     * Get service account from JSON file
     */
    private function getServiceAccountFromFile()
    {
        try {
            $serviceAccountPath = config('services.firebase.service_account_path');

            if (!$serviceAccountPath) {
                return null;
            }

            // Check if file exists
            if (!file_exists($serviceAccountPath)) {
                Log::warning("Firebase service account file not found: {$serviceAccountPath}");
                return null;
            }

            // Read and parse JSON file
            $jsonContent = file_get_contents($serviceAccountPath);
            $serviceAccount = json_decode($jsonContent, true);

            if (!$serviceAccount) {
                throw new \Exception('Invalid service account JSON file format');
            }

            // Validate required fields
            $requiredFields = ['type', 'project_id', 'private_key', 'client_email'];
            foreach ($requiredFields as $field) {
                if (!isset($serviceAccount[$field])) {
                    throw new \Exception("Missing required field in service account: {$field}");
                }
            }

            Log::info('Firebase service account loaded from file: ' . $serviceAccountPath);
            return $serviceAccount;

        } catch (\Exception $e) {
            Log::error('Failed to load service account from file: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Send notification to a single token
     */
    public function sendToToken(string $token, array $notification, array $data = [])
    {
        $payload = [
            'message' => [
                'token' => $token,
                'notification' => $notification,
                'data' => $data,
            ]
        ];

        return $this->sendRequest($payload);
    }

    /**
     * Send notification to multiple tokens
     */
    public function sendToTokens(array $tokens, array $notification, array $data = [])
    {
        $results = [];
        $successCount = 0;
        $failureCount = 0;
        $failedTokens = [];

        // HTTP v1 API doesn't support batch sending, so we send individually
        foreach ($tokens as $token) {
            $result = $this->sendToToken($token, $notification, $data);
            $results[] = $result;

            if ($result['success']) {
                $successCount++;
            } else {
                $failureCount++;
                $failedTokens[] = $token;

                // Check if this is a permanent failure (invalid token)
                if ($this->isPermanentFailure($result)) {
                    $this->deactivateFailedToken($token);
                }
            }
        }

        // Log failed tokens for cleanup
        if (!empty($failedTokens)) {
            Log::info('Failed tokens detected during notification', [
                'failed_count' => count($failedTokens),
                'failed_tokens' => $failedTokens
            ]);
        }

        return [
            'success' => $failureCount === 0,
            'message' => "Sent to {$successCount} tokens, {$failureCount} failed",
            'results' => $results,
            'success_count' => $successCount,
            'failure_count' => $failureCount,
            'failed_tokens' => $failedTokens
        ];
    }

    /**
     * Send notification to all active tokens
     */
    public function sendToAll(array $notification, array $data = [], string $platform = null)
    {
        $query = FcmToken::active();

        if ($platform) {
            $query->platform($platform);
        }

        $tokens = $query->pluck('token')->toArray();

        if (empty($tokens)) {
            return ['success' => false, 'message' => 'No active tokens found'];
        }

        return $this->sendToTokens($tokens, $notification, $data);
    }

    /**
     * Send notification to specific platform
     */
    public function sendToPlatform(string $platform, array $notification, array $data = [])
    {
        return $this->sendToAll($notification, $data, $platform);
    }

    /**
     * Send notification to topic (for future use)
     */
    public function sendToTopic(string $topic, array $notification, array $data = [])
    {
        $payload = [
            'message' => [
                'topic' => $topic,
                'notification' => $notification,
                'data' => $data,
            ]
        ];

        return $this->sendRequest($payload);
    }

    /**
     * Make HTTP request to FCM (HTTP v1 or Legacy API)
     */
    private function sendRequest(array $payload)
    {
        try {
            $useHttpV1 = config('services.firebase.use_http_v1', true);

            if ($useHttpV1 && strpos($this->accessToken, 'key=') === false) {
                // HTTP v1 API
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->accessToken,
                    'Content-Type' => 'application/json',
                ])->post($this->fcmUrl, $payload);
            } else {
                // Legacy API
                $legacyUrl = 'https://fcm.googleapis.com/fcm/send';
                $response = Http::withHeaders([
                    'Authorization' => 'key=' . $this->accessToken,
                    'Content-Type' => 'application/json',
                ])->post($legacyUrl, $payload);
            }

            $result = $response->json();

            if ($response->successful()) {
                $apiVersion = $useHttpV1 ? 'HTTP v1' : 'Legacy';
                Log::info("FCM notification sent successfully ({$apiVersion})", [
                    'payload' => $payload,
                    'response' => $result
                ]);

                return [
                    'success' => true,
                    'message' => 'Notification sent successfully',
                    'response' => $result,
                    'message_id' => $result['name'] ?? $result['message_id'] ?? null,
                    'api_version' => $useHttpV1 ? 'v1' : 'legacy'
                ];
            } else {
                $apiVersion = $useHttpV1 ? 'HTTP v1' : 'Legacy';
                Log::error("FCM notification failed ({$apiVersion})", [
                    'payload' => $payload,
                    'response' => $result,
                    'status' => $response->status()
                ]);

                // $token = $payload['message']['token'] ?? null;
                // if ($token) {
                //     $this->deleteFailedToken($token);
                // }



                $errorMessage = 'Failed to send notification';
                if (isset($result['error']['message'])) {
                    $errorMessage = $result['error']['message'];
                } elseif (isset($result['error'])) {
                    $errorMessage = $result['error'];
                }

                return [
                    'success' => false,
                    'message' => $errorMessage,
                    'response' => $result,
                    'api_version' => $useHttpV1 ? 'v1' : 'legacy'
                ];
            }
        } catch (\Exception $e) {
            Log::error('FCM service error', [
                'error' => $e->getMessage(),
                'payload' => $payload
            ]);

            return [
                'success' => false,
                'message' => 'Service error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Validate FCM token
     */
    public function validateToken(string $token)
    {
        // Send a test notification to validate token
        $testNotification = [
            'title' => 'Test',
            'body' => 'Token validation'
        ];

        $result = $this->sendToToken($token, $testNotification);

        return $result['success'];
    }

    /**
     * Get notification statistics
     */
    public function getStats()
    {
        return [
            'total_tokens' => FcmToken::count(),
            'active_tokens' => FcmToken::active()->count(),
            'platforms' => FcmToken::active()
                ->selectRaw('platform, COUNT(*) as count')
                ->groupBy('platform')
                ->get()
                ->pluck('count', 'platform')
        ];
    }

    /**
     * Check if the failure is permanent (invalid token)
     */
    private function isPermanentFailure(array $result): bool
    {
        if (!$result['success']) {
            $message = $result['message'] ?? '';

            // Check for permanent failure indicators
            $permanentFailures = [
                'InvalidRegistration',
                'NotRegistered',
                'InvalidToken',
                'MismatchSenderId',
                'invalid token',
                'token not found',
                'registration token not found'
            ];

            foreach ($permanentFailures as $failure) {
                if (stripos($message, $failure) !== false) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Deactivate a failed token
     */
    private function deactivateFailedToken(string $token): void
    {
        try {
            $fcmToken = FcmToken::where('token', $token)->first();

            if ($fcmToken) {
                $fcmToken->update(['is_active' => false]);

                Log::info('Deactivated failed FCM token', [
                    'token_id' => $fcmToken->id,
                    'platform' => $fcmToken->platform,
                    'token' => substr($token, 0, 20) . '...'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to deactivate token', [
                'token' => substr($token, 0, 20) . '...',
                'error' => $e->getMessage()
            ]);
        }
    }

    private function deleteFailedToken(string $token): void
    {
        try {
            $fcmToken = FcmToken::where('token', $token)->first();

            if ($fcmToken) {
                $fcmToken->delete();

                Log::info('Deleted failed FCM token', [
                    'token_id' => $fcmToken->id,
                    'platform' => $fcmToken->platform,
                    'token' => substr($token, 0, 20) . '...'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to delete token', [
                'token' => substr($token, 0, 20) . '...',
                'error' => $e->getMessage()
            ]);
        }
    }
}
