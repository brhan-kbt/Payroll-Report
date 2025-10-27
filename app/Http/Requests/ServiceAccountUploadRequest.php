<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceAccountUploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow authenticated users
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'service_account_file' => [
                'required',
                'file',
                'mimes:json,txt',
                'max:10240', // 10MB max
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'service_account_file.required' => 'Please select a service account file.',
            'service_account_file.file' => 'The uploaded file is not valid.',
            'service_account_file.mimes' => 'The file must be a JSON or text file.',
            'service_account_file.max' => 'The file size must not exceed 10MB.',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->hasFile('service_account_file')) {
                /** @var \Illuminate\Http\UploadedFile $file */
                $file = $this->file('service_account_file');

                // Validate JSON content
                $content = file_get_contents($file->getPathname());
                $serviceAccount = json_decode($content, true);

                if (!$serviceAccount) {
                    $validator->errors()->add('service_account_file', 'Invalid JSON format.');
                    return;
                }

                // Validate required fields
                $requiredFields = ['type', 'project_id', 'private_key', 'client_email'];
                $missingFields = [];

                foreach ($requiredFields as $field) {
                    if (!isset($serviceAccount[$field]) || empty($serviceAccount[$field])) {
                        $missingFields[] = $field;
                    }
                }

                if (!empty($missingFields)) {
                    $validator->errors()->add('service_account_file', 'Missing required fields: ' . implode(', ', $missingFields));
                }

                if (isset($serviceAccount['type']) && $serviceAccount['type'] !== 'service_account') {
                    $validator->errors()->add('service_account_file', 'Invalid service account type. Expected "service_account".');
                }
            }
        });
    }
}
