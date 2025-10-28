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
        } catch (\Exception $e) {
            SmsLog::create([
                'phone' => $this->recipient['phone'],
                'message' => $this->message,
                'status' => 'failed',
                'response' => json_encode(['error' => $e->getMessage()]),
            ]);
        }
    }
}
