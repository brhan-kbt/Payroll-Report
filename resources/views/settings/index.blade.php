<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Settings Management') }}
            </h2>
            <a href="{{ route('settings.create') }}"
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                <i class="fas fa-plus mr-2"></i>Add New Setting
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

            <!-- Settings Grid -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    @if($settings->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($settings as $setting)
                                <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md hover:shadow-lg transition duration-300 overflow-hidden">

                                    <!-- Setting Content -->
                                    <div class="p-6">
                                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                                            {{ ucfirst(str_replace('_', ' ', $setting->key)) }}
                                        </h3>

                                        <p class="text-sm mb-4 truncate"  style="color: #888 !important" >
                                            {!! Str::limit($setting->value, 100) !!}
                                        </p>

                                        <!-- Action Buttons -->
                                        <div class="flex space-x-2 mt-4">
                                            <a href="{{ route('settings.show', $setting) }}"
                                               class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center py-1 px-2 rounded text-sm transition duration-200">
                                                <i class="fas fa-eye mr-1"></i>
                                            </a>
                                            <a href="{{ route('settings.edit', $setting) }}"
                                               class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white text-center py-1 px-2 rounded text-sm transition duration-200">
                                                <i class="fas fa-edit mr-1"></i>
                                            </a>
                                            <form action="{{ route('settings.destroy', $setting) }}"
                                                  method="POST"
                                                  class="flex-1"
                                                  onsubmit="return confirm('Are you sure you want to delete this setting?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="w-full bg-red-500 hover:bg-red-600 text-white py-1 px-2 rounded text-sm transition duration-200">
                                                    <i class="fas fa-trash mr-1"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        {{-- <div class="mt-6">
                            {{ $settings->links() }}
                        </div> --}}
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-cog text-gray-400 text-6xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No settings found</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-6">Get started by adding your first setting.</p>
                            <a href="{{ route('settings.create') }}"
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                                <i class="fas fa-plus mr-2"></i>Create Setting
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
