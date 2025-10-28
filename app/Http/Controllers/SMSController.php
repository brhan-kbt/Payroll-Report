<?php

namespace App\Http\Controllers;

use App\Jobs\SendBulkSmsJob;
use App\Models\Employee;
use App\Models\SmsLog;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Validator;

class SMSController extends Controller
{
    public function index()
    {
        $employees = Employee::orderBy('name')->get();
        $recentSms = SmsLog::latest()->take(5)->get();

        return view('sms.index', compact('employees', 'recentSms'));
    }

    // public function sendSms(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'recipient_type' => 'required|in:employees,manual',
    //         'selected_employees' => 'required_if:recipient_type,employees|array',
    //         'manual_numbers' => 'required_if:recipient_type,manual',
    //         'message' => 'required|min:1|max:1600',
    //     ], [
    //         'selected_employees.required_if' => 'Please select at least one employee.',
    //         'manual_numbers.required_if' => 'Please enter at least one phone number.',
    //         'message.required' => 'Please enter a message.',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Validation failed',
    //             'errors' => $validator->errors()
    //         ], 422);
    //     }

    //     $smsService = new SmsService();
    //     $recipients = [];
    //     $recipientType = $request->recipient_type;

    //     // Get recipients based on type
    //     if ($recipientType === 'employees') {
    //         $employeeIds = $request->selected_employees ?? [];
    //         $employees = Employee::whereIn('id', $employeeIds)->get();

    //         foreach ($employees as $employee) {
    //             if ($employee->phone) {
    //                 $recipients[] = [
    //                     'phone' => $smsService->formatPhoneNumber($employee->phone),
    //                     'name' => $employee->name,
    //                     'employee_id' => $employee->id,
    //                 ];
    //             }
    //         }
    //     } else {
    //         $numbers = array_filter(array_map('trim', explode(',', $request->manual_numbers)));
    //         foreach ($numbers as $number) {
    //             $recipients[] = [
    //                 'phone' => $smsService->formatPhoneNumber($number),
    //                 'name' => 'Manual Entry',
    //                 'employee_id' => null,
    //             ];
    //         }
    //     }

    //     if (empty($recipients)) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'No valid recipients found.'
    //         ], 422);
    //     }

    //     // Send SMS to each recipient
    //     $successCount = 0;
    //     $failCount = 0;

    //     foreach ($recipients as $recipient) {
    //         try {
    //             $smsResponse = $smsService->sendSms($recipient['phone'], $request->message);

    //             SmsLog::create([
    //                 'employee_id' => $recipient['employee_id'],
    //                 'phone' => $recipient['phone'],
    //                 'message' => $request->message,
    //                 'status' => $smsResponse['status'] ?? 'failed',
    //                 'response' => json_encode($smsResponse),
    //             ]);

    //             if (($smsResponse['status'] ?? 'failed') === 'success') {
    //                 $successCount++;
    //             } else {
    //                 $failCount++;
    //             }
    //         } catch (\Exception $e) {
    //             $failCount++;

    //             SmsLog::create([
    //                 'employee_id' => $recipient['employee_id'],
    //                 'phone' => $recipient['phone'],
    //                 'message' => $request->message,
    //                 'status' => 'failed',
    //                 'response' => json_encode(['error' => $e->getMessage()]),
    //             ]);
    //         }
    //     }

    //     $message = $successCount > 0
    //         ? "Successfully sent {$successCount} SMS messages" . ($failCount > 0 ? ", {$failCount} failed" : '')
    //         : "Failed to send all {$failCount} SMS messages";

    //     return response()->json([
    //         'success' => $successCount > 0,
    //         'message' => $message,
    //         'success_count' => $successCount,
    //         'fail_count' => $failCount
    //     ]);
    // }

    public function sendSms(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recipient_type' => 'required|in:employees,manual',
            'selected_employees' => 'required_if:recipient_type,employees|array',
            'manual_numbers' => 'required_if:recipient_type,manual',
            'message' => 'required|min:1|max:1600',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ],
                422,
            );
        }

        $smsService = new SmsService();
        $recipients = [];

        if ($request->recipient_type === 'employees') {
            $employees = Employee::whereIn('id', $request->selected_employees ?? [])->get();

            $recipients = $employees
                ->map(function ($employee) use ($smsService) {
                    return [
                        'phone' => $smsService->formatPhoneNumber($employee->phone),
                        'name' => $employee->name,
                        'employee_id' => $employee->id,
                    ];
                })
                ->toArray();
        } else {
            $numbers = array_filter(array_map('trim', explode(',', $request->manual_numbers)));
            $recipients = collect($numbers)
                ->map(function ($number) use ($smsService) {
                    return [
                        'phone' => $smsService->formatPhoneNumber($number),
                        'name' => 'Manual Entry',
                        'employee_id' => null,
                    ];
                })
                ->toArray();
        }

        if (empty($recipients)) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'No valid recipients found.',
                ],
                422,
            );
        }

        // ✅ Dispatch all jobs in one batch (no iteration)
        $batch = Bus::batch(collect($recipients)->map(fn($recipient) => new SendBulkSmsJob($recipient, $request->message)))->dispatch();

        return response()->json([
            'success' => true,
            'message' => 'SMS sending has started in the background.',
            'batch_id' => $batch->id,
            'recipients_count' => count($recipients),
        ]);
    }

    public function searchEmployees(Request $request)
    {
        $search = $request->get('search', '');

        $employees = Employee::when($search, function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')->orWhere('employee_id', 'like', '%' . $search . '%');
        })
            ->orderBy('name')
            ->get();

        return response()->json($employees);
    }
}
