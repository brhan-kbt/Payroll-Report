<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Message -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold mb-2">Welcome back, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600 dark:text-gray-400">Manage your blog content and categories from here.</p>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-folder text-blue-500 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Employees</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ \App\Models\Employee::count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-file-alt text-green-500 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Posts</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ \App\Models\Post::count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-eye text-purple-500 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Views</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ \App\Models\Post::sum('views') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Management Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Categories Management -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                <i class="fas fa-folder mr-2 text-blue-500"></i>
                                Employees Management
                            </h3>
                            <a href="{{ route('employees.index') }}"
                               class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            Create, edit, and manage your employee categories.
                        </p>
                        <div class="flex space-x-2">
                            <a href="{{ route('employees.index') }}"
                               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition duration-200">
                                <i class="fas fa-list mr-1"></i>View All
                            </a>
                            <a href="{{ route('employees.create') }}"
                               class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm transition duration-200">
                                <i class="fas fa-plus mr-1"></i>Create New
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Posts Management -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                <i class="fas fa-file-alt mr-2 text-green-500"></i>
                                Posts Management
                            </h3>
                            <a href="{{ route('posts.index') }}"
                               class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            Create, edit, and manage your blog posts.
                        </p>
                        <div class="flex space-x-2">
                            <a href="{{ route('posts.index') }}"
                               class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm transition duration-200">
                                <i class="fas fa-list mr-1"></i>View All
                            </a>
                            <a href="{{ route('posts.create') }}"
                               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition duration-200">
                                <i class="fas fa-plus mr-1"></i>Create New
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Posts -->
            @if(\App\Models\Post::count() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg mt-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            <i class="fas fa-clock mr-2 text-purple-500"></i>
                            Recent Posts
                        </h3>
                        <div class="space-y-3">
                            @foreach(\App\Models\Post::with(['category', 'user'])->latest()->take(5)->get() as $post)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900 dark:text-white">
                                            <a href="{{ route('posts.show', $post) }}"
                                               class="hover:text-blue-600 dark:hover:text-blue-400">
                                                {{ Str::limit($post->title, 50) }}
                                            </a>
                                        </h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $post->category->name }} • {{ $post->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                                        <span><i class="fas fa-eye mr-1"></i>{{ $post->views }}</span>
                                        <span><i class="fas fa-heart mr-1"></i>{{ $post->likes }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 text-center">
                            <a href="{{ route('posts.index') }}"
                               class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                View all posts →
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
