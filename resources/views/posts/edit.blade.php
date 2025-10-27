<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('posts.index') }}"
                class="mr-4 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Post: ') . Str::limit($post->title, 50) }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Current Image Preview -->
                        @if ($post->image)
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Current Featured Image
                                </label>
                                <div class="relative inline-block">
                                    <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}"
                                        class="h-32 w-48 object-cover rounded-lg shadow-md">
                                    <div
                                        class="absolute inset-0 bg-black bg-opacity-50 opacity-0 hover:opacity-100 transition duration-200 rounded-lg flex items-center justify-center">
                                        <span class="text-white text-sm">Current Image</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Post Title -->
                        <div>
                            <label for="title"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Post Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('title') border-red-500 @enderror"
                                placeholder="Enter post title" required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Post Subtitle -->
                        <div>
                            <label for="subtitle"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Subtitle
                            </label>
                            <input type="text" name="subtitle" id="subtitle"
                                value="{{ old('subtitle', $post->subtitle) }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('subtitle') border-red-500 @enderror"
                                placeholder="Enter post subtitle (optional)">
                            @error('subtitle')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>


                        {{-- Post code  --}}
                        <div>
                            <label for="code"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Code
                            </label>
                            <input type="text" name="code" id="code"
                                value="{{ $post->code }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('code') border-red-500 @enderror"
                                placeholder="Enter post code (optional)">
                            @error('code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Category Selection -->
                        <div>
                            <label for="category_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <select name="category_id" id="category_id"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('category_id') border-red-500 @enderror"
                                required>
                                <option value="">Select a category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Featured Image -->
                        <div>
                            <label for="image"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Update Featured Image
                            </label>

                            <!-- New Image Preview Container -->
                            <div id="newImagePreviewContainer" class="hidden mb-4">
                                <div class="relative inline-block">
                                    <img id="newImagePreview" src="" alt="New Preview"
                                        class="h-48 w-64 object-cover rounded-lg shadow-md border border-gray-300 dark:border-gray-600">
                                    <button type="button" id="removeNewImage"
                                        class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition duration-200">
                                        <i class="fas fa-times text-sm"></i>
                                    </button>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">New image preview</p>
                            </div>

                            <!-- Upload Area -->
                            <div id="uploadArea"
                                class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md hover:border-gray-400 dark:hover:border-gray-500 transition duration-200">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                        viewBox="0 0 48 48">
                                        <path
                                            d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                        <label for="image"
                                            class="relative cursor-pointer bg-white dark:bg-gray-700 rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Upload a new file</span>
                                            <input id="image" name="image" type="file" class="sr-only"
                                                accept="image/*">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF up to 2MB</p>
                                </div>
                            </div>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Post Body -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label for="body"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Post Content <span class="text-red-500">*</span>
                                </label>
                                <div class="flex space-x-2">
                                    <button type="button" id="previewBtn"
                                        class="px-3 py-1 text-sm bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 transition duration-200">
                                        <i class="fas fa-eye mr-1"></i>Preview
                                    </button>
                                    <button type="button" id="editBtn"
                                        class="px-3 py-1 text-sm bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 rounded-md hover:bg-blue-200 dark:hover:bg-blue-800 transition duration-200 hidden">
                                        <i class="fas fa-edit mr-1"></i>Edit
                                    </button>
                                </div>
                            </div>

                            <!-- Editor Container -->
                            <div id="editorContainer">
                                <div id="editor"
                                    class="border border-gray-300 dark:border-gray-600 rounded-md shadow-lg focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 @error('body') border-red-500 @enderror"
                                    style="height: 400px;">
                                </div>
                            </div>

                            <!-- Preview Container -->
                            <div id="previewContainer"
                                class="hidden border border-gray-300 dark:border-gray-600 rounded-md shadow-lg bg-white dark:bg-gray-800 p-4"
                                style="height: 400px; overflow-y: auto;">
                                <div id="previewContent" class="prose dark:prose-invert max-w-none">
                                    <!-- Preview content will be inserted here -->
                                </div>
                            </div>

                            <textarea name="body" id="body" class="hidden" required>{{ old('body', $post->body) }}</textarea>
                            @error('body')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Use the rich text editor to format
                                your content with headings, lists, links, and more. Click Preview to see how your post
                                will look.</p>
                        </div>

                        <div>
                            <label for="link"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                CTA Link
                            </label>
                            <input type="url" name="link" id="link"
                                value="{{ old('link', $post->link) }}" placeholder="https://example.com/post"
                                class="block w-full mt-1 border-gray-300 dark:border-gray-600 rounded-md shadow-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @error('link')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('posts.index') }}"
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                                Cancel
                            </a>
                            <button type="submit"
                                class="px-4 py-2 border border-transparent rounded-md shadow-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200">
                                <i class="fas fa-save mr-2"></i>Update Post
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize Quill.js when page loads
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Quill !== 'undefined') {
                const quill = new Quill('#editor', {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            [{
                                'header': [1, 2, 3, 4, 5, 6, false]
                            }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{
                                'color': []
                            }, {
                                'background': []
                            }],
                            [{
                                'list': 'ordered'
                            }, {
                                'list': 'bullet'
                            }],
                            [{
                                'indent': '-1'
                            }, {
                                'indent': '+1'
                            }],
                            [{
                                'align': []
                            }],
                            ['link', 'image', 'video'],
                            ['blockquote', 'code-block'],
                            ['clean']
                        ]
                    },
                    placeholder: 'Write your post content here...'
                });

                // Set initial content if editing
                const bodyTextarea = document.getElementById('body');
                if (bodyTextarea.value) {
                    quill.root.innerHTML = bodyTextarea.value;
                }

                // Update textarea when editor content changes
                quill.on('text-change', function() {
                    bodyTextarea.value = quill.root.innerHTML;
                });

                // Preview functionality
                const previewBtn = document.getElementById('previewBtn');
                const editBtn = document.getElementById('editBtn');
                const editorContainer = document.getElementById('editorContainer');
                const previewContainer = document.getElementById('previewContainer');
                const previewContent = document.getElementById('previewContent');

                previewBtn.addEventListener('click', function() {
                    // Update the textarea with current editor content
                    bodyTextarea.value = quill.root.innerHTML;

                    // Show preview
                    previewContent.innerHTML = bodyTextarea.value;
                    editorContainer.classList.add('hidden');
                    previewContainer.classList.remove('hidden');
                    previewBtn.classList.add('hidden');
                    editBtn.classList.remove('hidden');
                });

                editBtn.addEventListener('click', function() {
                    // Show editor
                    editorContainer.classList.remove('hidden');
                    previewContainer.classList.add('hidden');
                    previewBtn.classList.remove('hidden');
                    editBtn.classList.add('hidden');
                });

                // Update form validation
                const form = document.querySelector('form');
                form.addEventListener('submit', function(e) {
                    bodyTextarea.value = quill.root.innerHTML;
                    if (!bodyTextarea.value.trim()) {
                        e.preventDefault();
                        alert('Please enter some content for your post.');
                        return false;
                    }
                });
            }

            // Image preview functionality for new image
            const imageInput = document.getElementById('image');
            const newImagePreview = document.getElementById('newImagePreview');
            const newImagePreviewContainer = document.getElementById('newImagePreviewContainer');
            const uploadArea = document.getElementById('uploadArea');
            const removeNewImageBtn = document.getElementById('removeNewImage');

            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Validate file type
                    if (!file.type.startsWith('image/')) {
                        alert('Please select a valid image file.');
                        return;
                    }

                    // Validate file size (2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Image size should be less than 2MB.');
                        return;
                    }

                    // Create preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        newImagePreview.src = e.target.result;
                        newImagePreviewContainer.classList.remove('hidden');
                        uploadArea.classList.add('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Remove new image functionality
            removeNewImageBtn.addEventListener('click', function() {
                imageInput.value = '';
                newImagePreviewContainer.classList.add('hidden');
                uploadArea.classList.remove('hidden');
            });

            // Drag and drop functionality
            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                uploadArea.classList.add('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900');
            });

            uploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                uploadArea.classList.remove('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900');
            });

            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                uploadArea.classList.remove('border-blue-400', 'bg-blue-50', 'dark:bg-blue-900');

                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    const file = files[0];
                    if (file.type.startsWith('image/')) {
                        imageInput.files = files;
                        imageInput.dispatchEvent(new Event('change'));
                    } else {
                        alert('Please drop a valid image file.');
                    }
                }
            });
        });
    </script>
</x-app-layout>
