<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Send FCM Notification') }}
            </h2>
            <a href="{{ route('fcm.index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to FCM Management
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form id="notification-form" class="space-y-6">
                        @csrf

                        <!-- Notification Type -->
                        <div>
                            <label for="notification_type"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Notification Type
                            </label>
                            <select id="notification_type" name="notification_type"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                onchange="toggleFormFields()">
                                <option value="all">Send to All Users</option>
                                <option value="platform">Send to Specific Platform</option>
                                <option value="custom">Send to Custom Tokens</option>
                            </select>
                        </div>

                        <!-- Platform Selection (shown when platform is selected) -->
                        <div id="platform-selection" class="hidden">
                            <label for="platform"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Target Platform
                            </label>
                            <select id="platform" name="platform"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="android">Android</option>
                                <option value="ios">iOS</option>
                                <option value="web">Web</option>
                            </select>
                        </div>

                        <!-- Custom Tokens (shown when custom is selected) -->
                        <div id="custom-tokens" class="hidden">
                            <label for="tokens"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                FCM Tokens (one per line)
                            </label>
                            <textarea id="tokens" name="tokens" rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Enter FCM tokens, one per line..."></textarea>
                        </div>

                        <!-- Notification Title -->
                        <div>
                            <label for="title"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Notification Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Enter notification title...">
                        </div>

                        <!-- Notification Body -->
                        <div>
                            <label for="body"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Notification Body <span class="text-red-500">*</span>
                            </label>
                            <textarea id="body" name="body" rows="4" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Enter notification message..."></textarea>
                        </div>

                        <!-- Image URL -->
                        <div>
                            <label for="image"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Image URL (Optional)
                            </label>
                            <input type="url" id="image" name="image"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="https://example.com/image.jpg">
                        </div>

                        <!-- Post or Category Selection -->
                        <!-- Target Type -->
                        <div class="mb-6">
                            <label for="target_type"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Target Type
                            </label>
                            <select id="target_type" name="target_type" onchange="toggleTargetFields()" required
                                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Select --</option>
                                <option value="post">Post</option>
                                <option value="category">Category</option>
                            </select>
                        </div>

                        <!-- Post Selection -->
                        <div id="post-selection" class="hidden mb-6">
                            <label for="post_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Select Post
                            </label>
                            <select id="post_id" name="post_id"
                                class="select2 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm">
                                <option value="">-- Choose Post --</option>
                                @foreach ($posts as $post)
                                    <option value="{{ $post->id }}">{{ $post->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Category Selection -->
                        <div id="category-selection" class="hidden mb-6">
                            <label for="category_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Select Category
                            </label>
                            <select id="category_id" name="category_id"
                                class="select2 w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm">
                                <option value="">-- Choose Category --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>


                        <!-- Custom Data -->
                        {{-- <div>
                            <label for="custom_data"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Custom Data (JSON - Optional)
                            </label>
                            <textarea id="custom_data" name="custom_data" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder='{"post_id": "123", "type": "new_post"}'></textarea>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Enter valid JSON data that will be sent with the notification
                            </p>
                        </div> --}}

                        <!-- Preview Section -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Preview</h3>
                            <div id="notification-preview" class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 17h5l-5 5-5-5h5v-5a7.5 7.5 0 00-15 0v5h5l-5 5-5-5h5V7a7.5 7.5 0 0115 0v10z">
                                                </path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h4 id="preview-title"
                                            class="text-sm font-medium text-gray-900 dark:text-gray-100">Notification
                                            Title</h4>
                                        <p id="preview-body" class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                            Notification message will appear here...</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-4">
                            <button type="button" onclick="previewNotification()"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Preview
                            </button>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Send Notification
                            </button>
                        </div>
                    </form>

                    <!-- Loading and Result Messages -->
                    <div id="loading" class="hidden mt-4">
                        <div
                            class="bg-blue-100 dark:bg-blue-900 border border-blue-400 text-blue-700 dark:text-blue-200 px-4 py-3 rounded">
                            <div class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Sending notification...
                            </div>
                        </div>
                    </div>

                    <div id="result" class="hidden mt-4"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: "Search...",
                allowClear: true,
                width: '100%'
            });
        });

        // function toggleTargetFields() {
        //     $('#post-selection').hide();
        //     $('#category-selection').hide();

        //     const type = document.getElementById('target_type').value;
        //     if (type === 'post') {
        //         $('#post-selection').show();
        //         $('#post_id').val(null).trigger('change');
        //     } else if (type === 'category') {
        //         $('#category-selection').show();
        //         $('#category_id').val(null).trigger('change');
        //     }
        // }
    </script>

    <script>
        // function toggleTargetFields() {
        //     const type = document.getElementById('target_type').value;
        //     document.getElementById('post-selection').classList.add('hidden');
        //     document.getElementById('category-selection').classList.add('hidden');

        //     if (type === 'post') {
        //         document.getElementById('post-selection').classList.remove('hidden');
        //     } else if (type === 'category') {
        //         document.getElementById('category-selection').classList.remove('hidden');
        //     }
        // }

           function toggleTargetFields() {
        const type = document.getElementById('target_type').value;

        const postWrapper = document.getElementById('post-selection');
        const categoryWrapper = document.getElementById('category-selection');
        const postSelect = document.getElementById('post_id');
        const categorySelect = document.getElementById('category_id');

        // Hide both first
        postWrapper.classList.add('hidden');
        categoryWrapper.classList.add('hidden');

        // Reset required attributes
        postSelect.removeAttribute('required');
        categorySelect.removeAttribute('required');

        if (type === 'post') {
            postWrapper.classList.remove('hidden');
            postSelect.setAttribute('required', 'required');
            // Reset value when switching
            categorySelect.value = "";
            $('#category_id').val(null).trigger('change');
        } else if (type === 'category') {
            categoryWrapper.classList.remove('hidden');
            categorySelect.setAttribute('required', 'required');
            // Reset value when switching
            postSelect.value = "";
            $('#post_id').val(null).trigger('change');
        }
    }


        function toggleFormFields() {
            const notificationType = document.getElementById('notification_type').value;
            const platformSelection = document.getElementById('platform-selection');
            const customTokens = document.getElementById('custom-tokens');

            // Hide all conditional fields
            platformSelection.classList.add('hidden');
            customTokens.classList.add('hidden');

            // Show relevant fields based on selection
            if (notificationType === 'platform') {
                platformSelection.classList.remove('hidden');
            } else if (notificationType === 'custom') {
                customTokens.classList.remove('hidden');
            }
        }

        function previewNotification() {
            const title = document.getElementById('title').value || 'Notification Title';
            const body = document.getElementById('body').value || 'Notification message will appear here...';

            document.getElementById('preview-title').textContent = title;
            document.getElementById('preview-body').textContent = body;
        }

        // Update preview on input
        document.getElementById('title').addEventListener('input', previewNotification);
        document.getElementById('body').addEventListener('input', previewNotification);

        // Form submission
        document.getElementById('notification-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const notificationType = formData.get('notification_type');

            // Show loading
            document.getElementById('loading').classList.remove('hidden');
            document.getElementById('result').classList.add('hidden');

            try {
                let endpoint = '/api/v1/admin/fcm/send';
                let payload = {
                    title: formData.get('title'),
                    body: formData.get('body'),
                    image: formData.get('image') || null
                };

                // Add custom data if provided
                // const customData = formData.get('custom_data');
                // if (customData) {
                //     try {
                //         payload.data = JSON.parse(customData);
                //     } catch (e) {
                //         throw new Error('Invalid JSON in custom data field');
                //     }
                // }

                const targetType = formData.get('target_type');
                if (targetType === 'post') {
                    const postId = formData.get('post_id');
                    if (postId) payload.data = {
                        post_id: postId
                    };
                } else if (targetType === 'category') {
                    const categoryId = formData.get('category_id');
                    if (categoryId) payload.data = {
                        category_id: categoryId
                    };
                }

                // Handle different notification types
                if (notificationType === 'platform') {
                    endpoint = '/api/v1/admin/fcm/send-to-platform';
                    payload.platform = formData.get('platform');
                } else if (notificationType === 'custom') {
                    const tokens = formData.get('tokens').split('\n').filter(token => token.trim());
                    if (tokens.length === 0) {
                        throw new Error('Please provide at least one FCM token');
                    }
                    payload.tokens = tokens;
                }

                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                // Hide loading
                document.getElementById('loading').classList.add('hidden');

                // Show result
                const resultDiv = document.getElementById('result');
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
                                    <h3 class="text-sm font-medium">Notification sent successfully!</h3>
                                    <div class="mt-2 text-sm">
                                        <p>${result.message}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
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
                                    <h3 class="text-sm font-medium">Failed to send notification</h3>
                                    <div class="mt-2 text-sm">
                                        <p>${result.message}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }

            } catch (error) {
                document.getElementById('loading').classList.add('hidden');

                const resultDiv = document.getElementById('result');
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
    </script>
</x-app-layout>
