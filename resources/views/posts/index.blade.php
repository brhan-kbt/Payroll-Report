<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Posts Management') }}
            </h2>
            <a href="{{ route('posts.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                <i class="fas fa-plus mr-2"></i>Create New Post
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <form method="GET" action="{{ route('posts.index') }}" class="mb-6">
                <div class="flex">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search posts..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-l-lg focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 rounded-r-lg">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
            <!-- Posts List -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    @if ($posts->count() > 0)
                        <div class="space-y-6">
                            @foreach ($posts as $post)
                                <div
                                    class="border border-gray-200 dark:border-gray-700 rounded-lg px-6 py-3 hover:shadow-lg transition duration-300">
                                    <div
                                        class="flex flex-col lg:flex-row lg:items-start space-y-4 lg:space-y-0 lg:space-x-6">
                                        <!-- Post Image -->
                                        <div class="flex-shrink-0">
                                            @if ($post->image)
                                                <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}"
                                                    class="w-full lg:w-20 h-20 object-cover rounded-lg shadow-md">
                                            @else
                                                <div
                                                    class="w-full lg:w-20 h-20 bg-gradient-to-br from-blue-400 to-purple-500 rounded-lg shadow-md flex items-center justify-center">
                                                    <i class="fas fa-file-alt text-white text-4xl"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Post Content -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <h3
                                                        class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                                                        <a href="{{ route('posts.show', $post) }}"
                                                            class="hover:text-blue-600 dark:hover:text-blue-400">
                                                            {{ $post->title }}
                                                        </a>
                                                    </h3>

                                                    @if ($post->subtitle)
                                                        <p class="text-gray-600 dark:text-gray-300 mb-3">
                                                            {{ $post->subtitle }}
                                                        </p>
                                                    @endif

                                                    {{-- <p class="text-gray-700 dark:text-gray-300 line-clamp-3">
                                                        {{ $post->excerpt }}
                                                    </p> --}}

                                                    <!-- Post Meta -->
                                                    <div
                                                        class="flex flex-wrap items-center space-x-6 text-sm text-gray-500 dark:text-gray-400 mb-4">
                                                        <span class="flex items-center">
                                                            <i class="fas fa-user mr-1"></i>
                                                            #{{ $post->code }}
                                                        </span>

                                                          <span class="flex items-center">
                                                            <i class="fas fa-user mr-1"></i>
                                                            {{ $post->user->name }}
                                                        </span>
                                                        <span class="flex items-center">
                                                            <i class="fas fa-folder mr-1"></i>
                                                            <a href="{{ route('categories.show', $post->category) }}"
                                                                class="hover:text-blue-600 dark:hover:text-blue-400">
                                                                {{ $post->category->name }}
                                                            </a>
                                                        </span>
                                                        <span class="flex items-center">
                                                            <i class="fas fa-eye mr-1"></i>
                                                            {{ $post->views }} views
                                                        </span>
                                                        <span class="flex items-center">
                                                            <i class="fas fa-heart mr-1"></i>
                                                            {{ $post->likes }} likes
                                                        </span>
                                                        <span class="flex items-center">
                                                            <i class="fas fa-clock mr-1"></i>
                                                            {{ $post->reading_time }} min read
                                                        </span>
                                                        <span class="flex items-center">
                                                            <i class="fas fa-calendar mr-1"></i>
                                                            {{ $post->created_at->format('M d, Y') }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <!-- Action Buttons -->
                                                <div class="flex flex-col space-y-2 ml-4">
                                                    <a href="{{ route('posts.show', $post) }}"
                                                        class="bg-blue-500 hover:bg-blue-600 text-white text-center py-2 px-3 rounded text-sm transition duration-200">
                                                        <i class="fas fa-eye mr-1"></i>View
                                                    </a>
                                                    <a href="{{ route('posts.edit', $post) }}"
                                                        class="bg-yellow-500 hover:bg-yellow-600 text-white text-center py-2 px-3 rounded text-sm transition duration-200">
                                                        <i class="fas fa-edit mr-1"></i>Edit
                                                    </a>
                                                    <form action="{{ route('posts.destroy', $post) }}" method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this post?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="w-full bg-red-500 hover:bg-red-600 text-white py-2 px-3 rounded text-sm transition duration-200">
                                                            <i class="fas fa-trash mr-1"></i>Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-file-alt text-gray-400 text-6xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No posts found</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-6">Get started by creating your first post.
                            </p>
                            <a href="{{ route('posts.create') }}"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                                <i class="fas fa-plus mr-2"></i>Create Post
                            </a>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
