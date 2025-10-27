<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('settings.index') }}"
                   class="mr-4 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ ucfirst(str_replace('_', ' ', $setting->key)) }}
                </h2>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('settings.edit', $setting) }}"
                   class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <form action="{{ route('settings.destroy', $setting) }}"
                      method="POST"
                      onsubmit="return confirm('Are you sure you want to delete this setting?')">
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
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 space-y-6">

                    <!-- Setting Info -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                            Key
                        </h3>
                        <p class="text-gray-700 dark:text-gray-300 text-sm">
                            {{ $setting->key }}
                        </p>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                            Content
                        </h3>
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-md text-gray-800 dark:text-gray-200 whitespace-pre-wrap">
                            {!! $setting->value !!}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-500 dark:text-gray-400">
                        <p><i class="fas fa-calendar mr-1"></i>Created: {{ $setting->created_at->format('M d, Y H:i') }}</p>
                        <p><i class="fas fa-clock mr-1"></i>Last Updated: {{ $setting->updated_at->diffForHumans() }}</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
