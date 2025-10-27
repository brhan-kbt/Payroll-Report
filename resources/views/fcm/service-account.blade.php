<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Firebase Service Account') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('fcm.index') }}"
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to FCM Management
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Current Service Account Status -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Current Service Account Status</h3>
                    <div id="service-account-status">
                        <!-- Status will be loaded here -->
                    </div>
                </div>
            </div>

            <!-- Upload Service Account File -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Upload Service Account File</h3>

                    <form id="upload-form" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div>
                            <label for="service_account_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Service Account JSON File
                            </label>
                            <input type="file" id="service_account_file" name="service_account_file" accept=".json,.txt" required
                                   class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-300">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Upload the JSON file downloaded from Firebase Console > Project Settings > Service Accounts
                            </p>
                        </div>

                        <div class="flex justify-end space-x-4">
                            <button type="button" onclick="validateFile()"
                                    class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                Validate File
                            </button>
                            <button type="button" onclick="testServiceAccount()"
                                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Test System
                            </button>
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Upload Service Account
                            </button>
                        </div>
                    </form>

                    <!-- Upload Progress -->
                    <div id="upload-progress" class="hidden mt-4">
                        <div class="bg-blue-100 dark:bg-blue-900 border border-blue-400 text-blue-700 dark:text-blue-200 px-4 py-3 rounded">
                            <div class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Uploading and validating service account file...
                            </div>
                        </div>
                    </div>

                    <!-- Upload Result -->
                    <div id="upload-result" class="hidden mt-4"></div>
                </div>
            </div>

            <!-- Service Account Information -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Service Account Information</h3>
                        <button type="button" onclick="loadServiceAccountInfo()"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Load Info
                        </button>
                    </div>
                    <div id="service-account-info" class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Click "Load Info" to view detailed service account information</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Load service account status
        async function loadServiceAccountStatus() {
            try {
                const response = await fetch('/fcm/service-account/status', {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    credentials: 'same-origin'
                });

                if (response.ok) {
                    const data = await response.json();
                    displayServiceAccountStatus(data);
                }
            } catch (error) {
                console.error('Error loading service account status:', error);
            }
        }

        // Display service account status
        function displayServiceAccountStatus(data) {
            const statusDiv = document.getElementById('service-account-status');

            if (data.exists) {
                statusDiv.innerHTML = `
                    <div class="bg-green-100 dark:bg-green-900 border border-green-400 text-green-700 dark:text-green-200 px-4 py-3 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium">Service Account File Found</h3>
                                <div class="mt-2 text-sm">
                                    <p>File: ${data.file_path}</p>
                                    <p>Project ID: ${data.project_id}</p>
                                    <p>Client Email: ${data.client_email}</p>
                                    <p>Last Modified: ${data.last_modified}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                statusDiv.innerHTML = `
                    <div class="bg-yellow-100 dark:bg-yellow-900 border border-yellow-400 text-yellow-700 dark:text-yellow-200 px-4 py-3 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium">No Service Account File Found</h3>
                                <div class="mt-2 text-sm">
                                    <p>Please upload a Firebase service account JSON file to enable HTTP v1 API.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
        }

        // Validate file before upload
        function validateFile() {
            const fileInput = document.getElementById('service_account_file');
            const file = fileInput.files[0];

            if (!file) {
                alert('Please select a file first.');
                return;
            }

            if (!file.name.endsWith('.json') && !file.name.endsWith('.txt')) {
                alert('Please select a JSON or text file.');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                try {
                    const json = JSON.parse(e.target.result);

                    // Check required fields
                    const requiredFields = ['type', 'project_id', 'private_key', 'client_email'];
                    const missingFields = requiredFields.filter(field => !json[field]);

                    if (missingFields.length > 0) {
                        alert('Invalid service account file. Missing fields: ' + missingFields.join(', '));
                        return;
                    }

                    if (json.type !== 'service_account') {
                        alert('Invalid service account file. Type should be "service_account".');
                        return;
                    }

                    alert('File validation successful! Project ID: ' + json.project_id);
                } catch (error) {
                    alert('Invalid JSON file: ' + error.message);
                }
            };
            reader.readAsText(file);
        }

        // Handle form submission
        document.getElementById('upload-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const fileInput = document.getElementById('service_account_file');
            const file = fileInput.files[0];

            if (!file) {
                alert('Please select a file first.');
                return;
            }

            // Show progress
            document.getElementById('upload-progress').classList.remove('hidden');
            document.getElementById('upload-result').classList.add('hidden');

            try {
                const response = await fetch('/fcm/service-account/upload', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    credentials: 'same-origin',
                    body: formData
                });

                const result = await response.json();

                // Hide progress
                document.getElementById('upload-progress').classList.add('hidden');

                // Show result
                const resultDiv = document.getElementById('upload-result');
                resultDiv.classList.remove('hidden');

                if (result.success) {
                    resultDiv.innerHTML = `
                        <div class="bg-green-100 dark:bg-green-900 border border-green-400 text-green-700 dark:text-green-200 px-4 py-3 rounded">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium">Service Account Uploaded Successfully!</h3>
                                    <div class="mt-2 text-sm">
                                        <p>${result.message}</p>
                                        <p>Project ID: ${result.project_id}</p>
                                        <p>Client Email: ${result.client_email}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    // Reload status
                    loadServiceAccountStatus();
                } else {
                    resultDiv.innerHTML = `
                        <div class="bg-red-100 dark:bg-red-900 border border-red-400 text-red-700 dark:text-red-200 px-4 py-3 rounded">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium">Upload Failed</h3>
                                    <div class="mt-2 text-sm">
                                        <p>${result.message}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }

            } catch (error) {
                document.getElementById('upload-progress').classList.add('hidden');

                const resultDiv = document.getElementById('upload-result');
                resultDiv.classList.remove('hidden');
                resultDiv.innerHTML = `
                    <div class="bg-red-100 dark:bg-red-900 border border-red-400 text-red-700 dark:text-red-200 px-4 py-3 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium">Error</h3>
                                <div class="mt-2 text-sm">
                                    <p>${error.message}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
        });

        // Test service account system
        async function testServiceAccount() {
            try {
                const response = await fetch('/fcm/service-account/test', {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    credentials: 'same-origin'
                });

                if (response.ok) {
                    const data = await response.json();
                    console.log('Service Account Test Results:', data);

                    let message = 'Service Account Test Results:\n\n';
                    message += `Configured Path: ${data.info.configured_path}\n`;
                    message += `File Exists: ${data.info.file_exists}\n`;
                    message += `Directory Exists: ${data.info.directory_exists}\n`;
                    message += `Directory Writable: ${data.info.directory_writable}\n`;
                    message += `File Readable: ${data.info.file_readable}\n`;
                    message += `File Size: ${data.info.file_size} bytes\n`;
                    message += `Last Modified: ${data.info.last_modified || 'N/A'}\n`;

                    if (data.info.json_valid !== undefined) {
                        message += `JSON Valid: ${data.info.json_valid}\n`;
                        if (data.info.json_valid) {
                            message += `Project ID: ${data.info.project_id}\n`;
                            message += `Client Email: ${data.info.client_email}\n`;
                        } else {
                            message += `JSON Error: ${data.info.json_error}\n`;
                        }
                    }

                    alert(message);
                } else {
                    alert('Test failed: ' + response.statusText);
                }
            } catch (error) {
                console.error('Error testing service account:', error);
                alert('Test failed: ' + error.message);
            }
        }

        // Load service account information
        async function loadServiceAccountInfo() {
            try {
                const response = await fetch('/fcm/service-account/info', {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    credentials: 'same-origin'
                });

                if (response.ok) {
                    const data = await response.json();
                    console.log('Service Account Info:', data);
                    displayServiceAccountInfo(data);
                } else {
                    alert('Info failed: ' + response.statusText);
                }
            } catch (error) {
                console.error('Error loading service account info:', error);
                alert('Info failed: ' + error.message);
            }
        }

        // Display service account information
        function displayServiceAccountInfo(data) {
            const infoDiv = document.getElementById('service-account-info');

            if (data.success && data.info) {
                const info = data.info;

                let html = `
                    <div class="space-y-3">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-white dark:bg-gray-600 p-3 rounded border">
                                <h4 class="font-semibold text-gray-900 dark:text-white">File Status</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    <span class="font-medium">File Exists:</span>
                                    <span class="${info.file_exists ? 'text-green-600' : 'text-red-600'}">${info.file_exists ? 'Yes' : 'No'}</span>
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    <span class="font-medium">Directory Exists:</span>
                                    <span class="${info.directory_exists ? 'text-green-600' : 'text-red-600'}">${info.directory_exists ? 'Yes' : 'No'}</span>
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    <span class="font-medium">Directory Writable:</span>
                                    <span class="${info.directory_writable ? 'text-green-600' : 'text-red-600'}">${info.directory_writable ? 'Yes' : 'No'}</span>
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    <span class="font-medium">File Readable:</span>
                                    <span class="${info.file_readable ? 'text-green-600' : 'text-red-600'}">${info.file_readable ? 'Yes' : 'No'}</span>
                                </p>
                            </div>

                            <div class="bg-white dark:bg-gray-600 p-3 rounded border">
                                <h4 class="font-semibold text-gray-900 dark:text-white">File Details</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    <span class="font-medium">File Path:</span> ${info.configured_path}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    <span class="font-medium">File Size:</span> ${info.file_size} bytes
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    <span class="font-medium">Last Modified:</span> ${info.last_modified || 'N/A'}
                                </p>
                            </div>
                        </div>
                `;

                if (info.json_valid !== undefined) {
                    html += `
                        <div class="bg-white dark:bg-gray-600 p-3 rounded border">
                            <h4 class="font-semibold text-gray-900 dark:text-white">JSON Validation</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                <span class="font-medium">JSON Valid:</span>
                                <span class="${info.json_valid ? 'text-green-600' : 'text-red-600'}">${info.json_valid ? 'Yes' : 'No'}</span>
                            </p>
                    `;

                    if (info.json_valid) {
                        html += `
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                <span class="font-medium">Project ID:</span> ${info.project_id}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                <span class="font-medium">Client Email:</span> ${info.client_email}
                            </p>
                        `;
                    } else {
                        html += `
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                <span class="font-medium">JSON Error:</span> ${info.json_error}
                            </p>
                        `;
                    }

                    html += `</div>`;
                }

                html += `</div>`;
                infoDiv.innerHTML = html;
            } else {
                infoDiv.innerHTML = `
                    <div class="bg-red-100 dark:bg-red-900 border border-red-400 text-red-700 dark:text-red-200 px-4 py-3 rounded">
                        <p class="text-sm">Failed to load service account information: ${data.message || 'Unknown error'}</p>
                    </div>
                `;
            }
        }

        // Load data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadServiceAccountStatus();
        });
    </script>
</x-app-layout>
