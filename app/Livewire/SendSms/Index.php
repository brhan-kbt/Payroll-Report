<?php

namespace App\Livewire\SendSms;

use Livewire\Component;
use App\Models\Employee;
use App\Models\SmsLog;
use App\Services\SmsService;

class Index extends Component
{
    public $recipientType = 'employees';
    public $selectedEmployees = [];
    public $manualNumbers = '';
    public $message = '';
    public $employeeSearch = '';

    protected $rules = [
        'selectedEmployees' => 'required_if:recipientType,employees|array|min:1',
        'manualNumbers' => 'required_if:recipientType,manual',
        'message' => 'required|min:1|max:1600',
    ];

    protected $messages = [
        'selectedEmployees.required_if' => 'Please select at least one employee.',
        'manualNumbers.required_if' => 'Please enter at least one phone number.',
        'message.required' => 'Please enter a message.',
    ];

    public function setRecipientType($type)
    {
        $this->recipientType = $type;
    }

    public function getEmployeesProperty()
    {
        return Employee::when($this->employeeSearch, function ($query) {
            $query->where('name', 'like', '%' . $this->employeeSearch . '%')
                  ->orWhere('employee_id', 'like', '%' . $this->employeeSearch . '%');
        })
        ->orderBy('name')
        ->get();
    }

    public function getTotalRecipientsProperty()
    {
        if ($this->recipientType === 'employees') {
            return count($this->selectedEmployees);
        } else {
            $numbers = array_filter(array_map('trim', explode(',', $this->manualNumbers)));
            return count($numbers);
        }
    }

    public function getRecentSmsProperty()
    {
        return SmsLog::latest()->take(5)->get();
    }

    public function getMessagePagesProperty()
    {
        return ceil(strlen($this->message) / 160);
    }

    public function getTotalSmsProperty()
    {
        return $this->totalRecipients * $this->messagePages;
    }

    public function removeEmployee($employeeId)
    {
        $this->selectedEmployees = array_filter($this->selectedEmployees, function ($id) use ($employeeId) {
            return $id != $employeeId;
        });
    }

    public function clearSelectedEmployees()
    {
        $this->selectedEmployees = [];
    }

    public function clearForm()
    {
        $this->selectedEmployees = [];
        $this->manualNumbers = '';
        $this->message = '';
        $this->employeeSearch = '';
    }

    public function sendSms()
    {
        $this->validate();

        $smsService = new SmsService();
        $recipients = [];

        // Get recipients based on type
        if ($this->recipientType === 'employees') {
            $employees = Employee::whereIn('id', $this->selectedEmployees)->get();
            foreach ($employees as $employee) {
                if ($employee->phone) {
                    $recipients[] = [
                        'phone' => $smsService->formatPhoneNumber($employee->phone),
                        'name' => $employee->name,
                        'employee_id' => $employee->id,
                    ];
                }
            }
        } else {
            $numbers = array_filter(array_map('trim', explode(',', $this->manualNumbers)));
            foreach ($numbers as $number) {
                $recipients[] = [
                    'phone' => $smsService->formatPhoneNumber($number),
                    'name' => 'Manual Entry',
                    'employee_id' => null,
                ];
            }
        }

        // Send SMS to each recipient
        $successCount = 0;
        $failCount = 0;

        foreach ($recipients as $recipient) {
            try {
                $smsResponse = $smsService->sendSms($recipient['phone'], $this->message);

                SmsLog::create([
                    'employee_id' => $recipient['employee_id'],
                    'phone' => $recipient['phone'],
                    'message' => $this->message,
                    'status' => $smsResponse['status'] ?? 'failed',
                    'response' => json_encode($smsResponse),
                ]);

                if (($smsResponse['status'] ?? 'failed') === 'success') {
                    $successCount++;
                } else {
                    $failCount++;
                }
            } catch (\Exception $e) {
                $failCount++;

                SmsLog::create([
                    'employee_id' => $recipient['employee_id'],
                    'phone' => $recipient['phone'],
                    'message' => $this->message,
                    'status' => 'failed',
                    'response' => json_encode(['error' => $e->getMessage()]),
                ]);
            }
        }

        // Show success message
        if ($successCount > 0) {
            session()->flash('success', "Successfully sent {$successCount} SMS messages" . ($failCount > 0 ? ", {$failCount} failed" : ''));
        } else {
            session()->flash('error', "Failed to send all {$failCount} SMS messages");
        }

        // Clear form on success
        if ($failCount === 0) {
            $this->clearForm();
        }
    }

    public function render()
    {
        return view('livewire.send-sms.index');
    }
}
