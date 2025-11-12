<?php

namespace App\Jobs;

use App\Models\SmsLog;
use App\Services\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable; // ✅ ADD THIS
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendBulkSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable; // ✅ INCLUDE HERE

    public $recipient;
    public $message;

    public function __construct(array $recipient, string $message)
    {
        $this->recipient = $recipient;
        $this->message = $message;
    }

    public function handle(): void
    {
        $smsService = new SmsService();

        try {
            $smsResponse = $smsService->sendSms($this->recipient['phone'], $this->message);

            SmsLog::create([
                'phone' => $this->recipient['phone'],
                'message' => $this->message,
                'status' => $smsResponse['status'] ?? 'failed',
                'response' => json_encode($smsResponse),
            ]);
            Log::info('SMS sent successfully');
        } catch (\Exception $e) {
            Log::info('Failed to send SMS: ' . $e->getMessage());
            SmsLog::create([
                'phone' => $this->recipient['phone'],
                'message' => $this->message,
                'status' => 'error',
                'response' => json_encode(['error' => $e->getMessage()]),
            ]);
        }
    }
}
