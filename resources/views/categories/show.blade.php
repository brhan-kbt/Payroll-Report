<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('categories.index') }}"
                   class="mr-4 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $category->name }}
                </h2>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('categories.edit', $category) }}"
                   class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <form action="{{ route('categories.destroy', $category) }}"
                      method="POST"
                      onsubmit="return confirm('Are you sure you want to delete this category?')">
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
            <!-- Category Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row items-start md:items-center space-y-4 md:space-y-0 md:space-x-6">
                        <!-- Category Image -->
                        @if($category->image)
                            <div class="flex-shrink-0">
                                <img src="{{ Storage::url($category->image) }}"
                                     alt="{{ $category->name }}"
                                     class="h-32 w-32 object-cover rounded-lg shadow-md">
                            </div>
                        @else
                            <div class="flex-shrink-0">
                                <div class="h-32 w-32 bg-gradient-to-br from-blue-400 to-purple-500 rounded-lg shadow-md flex items-center justify-center">
                                    <i class="fas fa-folder text-white text-4xl"></i>
                                </div>
                            </div>
                        @endif

                        <!-- Category Info -->
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                                {{ $category->name }}
                            </h1>

                            @if($category->parent)
                                <p class="text-lg text-gray-600 dark:text-gray-300 mb-2">
                                    <i class="fas fa-level-up-alt mr-2"></i>
                                    Subcategory of:
                                    <a href="{{ route('categories.show', $category->parent) }}"
                                       class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        {{ $category->parent->name }}
                                    </a>
                                </p>
                            @endif

                            <div class="flex flex-wrap items-center space-x-6 text-sm text-gray-500 dark:text-gray-400">
                                <span>
                                    <i class="fas fa-file-alt mr-1"></i>
                                    {{ $posts->total() }} posts
                                </span>
                                <span>
                                    <i class="fas fa-sitemap mr-1"></i>
                                    {{ $category->children->count() }} subcategories
                                </span>
                                <span>
                                    <i class="fas fa-calendar mr-1"></i>
                                    Created {{ $category->created_at->format('M d, Y') }}
                                </span>
                                <span>
                                    <i class="fas fa-clock mr-1"></i>
                                    Updated {{ $category->updated_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subcategories -->
            @if($category->children->count() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            <i class="fas fa-sitemap mr-2"></i>Subcategories
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($category->children as $subcategory)
                                <a href="{{ route('categories.show', $subcategory) }}"
                                   class="block p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition duration-200">
                                    <h4 class="font-medium text-gray-900 dark:text-white">{{ $subcategory->name }}</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $subcategory->posts->count() }} posts
                                    </p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Posts in this Category -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        <i class="fas fa-file-alt mr-2"></i>Posts in this Category
                    </h3>

                    @if($posts->count() > 0)
                        <div class="space-y-4">
                            @foreach($posts as $post)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition duration-200">
                                    <div class="flex items-start space-x-4">
                                        @if($post->image)
                                            <img src="{{ Storage::url($post->image) }}"
                                                 alt="{{ $post->title }}"
                                                 class="w-20 h-20 object-cover rounded-lg flex-shrink-0">
                                        @else
                                            <div class="w-20 h-20 bg-gradient-to-br from-gray-400 to-gray-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-file-alt text-white text-xl"></i>
                                            </div>
                                        @endif>

                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-1">
                                                <a href="{{ route('posts.show', $post) }}"
                                                   class="hover:text-blue-600 dark:hover:text-blue-400">
                                                    {{ $post->title }}
                                                </a>
                                            </h4>

                                            @if($post->subtitle)
                                                <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">
                                                    {{ $post->subtitle }}
                                                </p>
                                            @endif

                                            <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                                <span>
                                                    <i class="fas fa-user mr-1"></i>
                                                    {{ $post->user->name }}
                                                </span>
                                                <span>
                                                    <i class="fas fa-eye mr-1"></i>
                                                    {{ $post->views }} views
                                                </span>
                                                <span>
                                                    <i class="fas fa-heart mr-1"></i>
                                                    {{ $post->likes }} likes
                                                </span>
                                                <span>
                                                    <i class="fas fa-clock mr-1"></i>
                                                    {{ $post->created_at->diffForHumans() }}
                                                </span>
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
                        <div class="text-center py-8">
                            <i class="fas fa-file-alt text-gray-400 text-4xl mb-4"></i>
                            <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No posts found</h4>
                            <p class="text-gray-500 dark:text-gray-400">No posts have been published in this category yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
