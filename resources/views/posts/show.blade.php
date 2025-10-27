<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('posts.index') }}"
                   class="mr-4 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ Str::limit($post->title, 60) }}
                </h2>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('posts.edit', $post) }}"
                   class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <form action="{{ route('posts.destroy', $post) }}"
                      method="POST"
                      onsubmit="return confirm('Are you sure you want to delete this post?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                        <i class="fas fa-trash mr-2"></i>Delete
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Post Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg mb-6">
                <div class="p-6">
                    <!-- Featured Image -->
                    @if($post->image)
                        <div class="mb-6">
                            <img src="{{ Storage::url($post->image) }}"
                                 alt="{{ $post->title }}"
                                 class="w-full h-64 md:h-96 object-cover rounded-lg shadow-lg">
                        </div>
                    @endif

                    <!-- Post Title -->
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        {{ $post->title }}
                    </h1>

                    <!-- Post Subtitle -->
                    @if($post->subtitle)
                        <p class="text-xl text-gray-600 dark:text-gray-300 mb-6">
                            {{ $post->subtitle }}
                        </p>
                    @endif

                    <!-- Post Meta -->
                    <div class="flex flex-wrap items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex flex-wrap items-center space-x-6">
                            <span class="flex items-center">
                                <i class="fas fa-user mr-2"></i>
                                {{ $post->user->name }}
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-folder mr-2"></i>
                                <a href="{{ route('categories.show', $post->category) }}"
                                   class="hover:text-blue-600 dark:hover:text-blue-400">
                                    {{ $post->category->name }}
                                </a>
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-calendar mr-2"></i>
                                {{ $post->created_at->format('M d, Y') }}
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-clock mr-2"></i>
                                {{ $post->reading_time }} min read
                            </span>
                        </div>
                        <div class="flex items-center space-x-4 mt-2 md:mt-0">
                            <span class="flex items-center">
                                <i class="fas fa-eye mr-2"></i>
                                {{ $post->views }} views
                            </span>
                            <button onclick="toggleLike({{ $post->id }})"
                                    class="flex items-center hover:text-red-500 transition duration-200">
                                <i class="fas fa-heart mr-2"></i>
                                <span id="likes-count">{{ $post->likes }}</span> likes
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Post Content -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <div class="prose prose-lg max-w-none dark:prose-invert prose-headings:text-gray-900 dark:prose-headings:text-white prose-p:text-gray-700 dark:prose-p:text-gray-300 prose-a:text-blue-600 dark:prose-a:text-blue-400 prose-strong:text-gray-900 dark:prose-strong:text-white prose-code:text-gray-900 dark:prose-code:text-white prose-pre:bg-gray-100 dark:prose-pre:bg-gray-800">
                        {!! $post->body !!}
                    </div>
                </div>
            </div>

            <!-- Post Actions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg mt-6">
                <div class="p-6">
                    <div class="flex justify-between items-center">
                        <div class="flex space-x-4">
                            <button onclick="toggleLike({{ $post->id }})"
                                    class="flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition duration-200">
                                <i class="fas fa-heart mr-2"></i>
                                <span id="likes-count-2">{{ $post->likes }}</span> Likes
                            </button>
                            <button onclick="sharePost()"
                                    class="flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition duration-200">
                                <i class="fas fa-share mr-2"></i>
                                Share
                            </button>
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Last updated {{ $post->updated_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleLike(postId) {
            fetch(`/posts/${postId}/like`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('likes-count').textContent = data.likes;
                document.getElementById('likes-count-2').textContent = data.likes;
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        function sharePost() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $post->title }}',
                    text: '{{ $post->subtitle ?? Str::limit($post->body, 100) }}',
                    url: window.location.href
                });
            } else {
                // Fallback: copy to clipboard
                navigator.clipboard.writeText(window.location.href).then(() => {
                    alert('Post URL copied to clipboard!');
                });
            }
        }
    </script>
</x-app-layout>
