<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('settings.index') }}"
               class="mr-4 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Create New Setting') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('settings.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Setting Key -->
                        <div>
                            <label for="key" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Setting Key <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="key"
                                   id="key"
                                   value="{{ old('key') }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('key') border-red-500 @enderror"
                                   placeholder="e.g. about_us, privacy_policy, app_version"
                                   required>
                            @error('key')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Use snake_case (underscores instead of spaces).</p>
                        </div>

                        <!-- Setting Value (Rich Text) -->
                        <div>
                            <label for="value" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Setting Value <span class="text-red-500">*</span>
                            </label>

                            <!-- Quill Editor -->
                            <div id="editor"
                                 class="border border-gray-300 dark:border-gray-600 rounded-md shadow-lg focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 @error('value') border-red-500 @enderror"
                                 style="height: 400px;">
                            </div>

                            <!-- Hidden textarea (will store Quill content) -->
                            <textarea name="value" id="value" class="hidden" required>{{ old('value') }}</textarea>

                            @error('value')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <p class="mt-1 text-sm text-gray-500">
                                Use the rich text editor to format your content with headings, lists, links, and more.
                            </p>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('settings.index') }}"
                               class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                                Cancel
                            </a>
                            <button type="submit"
                                    class="px-4 py-2 border border-transparent rounded-md shadow-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                                <i class="fas fa-save mr-2"></i>Create Setting
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Quill JS Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Quill !== 'undefined') {
                const quill = new Quill('#editor', {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            [{ 'header': [1, 2, 3, false] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            [{ 'indent': '-1'}, { 'indent': '+1' }],
                            [{ 'align': [] }],
                            [{ 'color': [] }, { 'background': [] }],
                            ['link', 'image', 'video'],
                            ['blockquote', 'code-block'],
                            ['clean']
                        ]
                    },
                    placeholder: 'Write your setting value here...'
                });

                const valueTextarea = document.getElementById('value');

                // Load old value into Quill if exists
                if (valueTextarea.value) {
                    quill.root.innerHTML = valueTextarea.value;
                }

                // Sync editor content with hidden textarea
                quill.on('text-change', function() {
                    valueTextarea.value = quill.root.innerHTML;
                });

                // Ensure textarea updated before submit
                document.querySelector('form').addEventListener('submit', function() {
                    valueTextarea.value = quill.root.innerHTML;
                });
            }
        });
    </script>
</x-app-layout>
