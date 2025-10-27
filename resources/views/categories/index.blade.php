<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Categories Management') }}
            </h2>
            <a href="{{ route('categories.create') }}"
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                <i class="fas fa-plus mr-2"></i>Add New Category
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Categories Grid -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    @if($categories->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($categories as $category)
                                <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md hover:shadow-lg transition duration-300 overflow-hidden">
                                    <!-- Category Image -->
                                    @if($category->image)
                                        <div class="h-48 bg-cover bg-center"
                                             style="background-image: url('{{ Storage::url($category->image) }}')">
                                        </div>
                                    @else
                                        <div class="h-48 bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                                            <i class="fas fa-folder text-white text-6xl"></i>
                                        </div>
                                    @endif

                                    <!-- Category Content -->
                                    <div class="p-6">
                                        <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">
                                            {{ $category->name }}
                                        </h3>

                                        @if($category->parent)
                                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">
                                                <i class="fas fa-level-up-alt mr-1"></i>
                                                Subcategory of: {{ $category->parent->name }}
                                            </p>
                                        @endif

                                        <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-4">
                                            <span>
                                                <i class="fas fa-file-alt mr-1"></i>
                                                {{ $category->posts_count ?? $category->posts->count() }} posts
                                            </span>
                                            <span>
                                                <i class="fas fa-sitemap mr-1"></i>
                                                {{ $category->children->count() }} subcategories
                                            </span>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="flex space-x-2">
                                            <a href="{{ route('categories.show', $category) }}"
                                               class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center py-2 px-3 rounded text-sm transition duration-200">
                                                <i class="fas fa-eye mr-1"></i>View
                                            </a>
                                            <a href="{{ route('categories.edit', $category) }}"
                                               class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white text-center py-2 px-3 rounded text-sm transition duration-200">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </a>
                                            <form action="{{ route('categories.destroy', $category) }}"
                                                  method="POST"
                                                  class="flex-1"
                                                  onsubmit="return confirm('Are you sure you want to delete this category?')">
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
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $categories->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-folder-open text-gray-400 text-6xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No categories found</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-6">Get started by creating your first category.</p>
                            <a href="{{ route('categories.create') }}"
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                                <i class="fas fa-plus mr-2"></i>Create Category
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
