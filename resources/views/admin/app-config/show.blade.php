<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('App Configuration Details') }}
            </h2>
            <a href="{{ route('admin.app-config.index') }}"
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Configurations
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Configuration Details -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Configuration Details</h3>

                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Key</dt>
                                    <dd class="mt-1 text-sm text-gray-900 font-mono bg-gray-100 p-2 rounded">{{ $appConfig->key }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Type</dt>
                                    <dd class="mt-1">
                                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                            {{ $appConfig->type }}
                                        </span>
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Public Access</dt>
                                    <dd class="mt-1">
                                        @if($appConfig->is_public)
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Public</span>
                                        @else
                                            <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Private</span>
                                        @endif
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $appConfig->created_at->format('M d, Y H:i:s') }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $appConfig->updated_at->format('M d, Y H:i:s') }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Value Display -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Value</h3>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                @if($appConfig->type === 'json')
                                    <pre class="text-sm text-gray-800 overflow-auto">{{ json_encode($appConfig->typed_value, JSON_PRETTY_PRINT) }}</pre>
                                @elseif($appConfig->type === 'boolean')
                                    <div class="flex items-center">
                                        @if($appConfig->typed_value)
                                            <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-800 font-medium">True</span>
                                        @else
                                            <span class="px-3 py-1 text-sm rounded-full bg-red-100 text-red-800 font-medium">False</span>
                                        @endif
                                    </div>
                                @else
                                    <p class="text-sm text-gray-800 font-mono break-all">{{ $appConfig->typed_value }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($appConfig->description)
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Description</h3>
                            <p class="text-sm text-gray-700">{{ $appConfig->description }}</p>
                        </div>
                    @endif

                    <!-- Raw Value (for debugging) -->
                    <div class="mt-6">
                        <details class="group">
                            <summary class="cursor-pointer text-sm font-medium text-gray-500 hover:text-gray-700">
                                Raw Value (for debugging)
                            </summary>
                            <div class="mt-2 bg-gray-100 p-3 rounded">
                                <code class="text-xs text-gray-800">{{ $appConfig->value }}</code>
                            </div>
                        </details>
                    </div>

                    <!-- Actions -->
                    <div class="mt-8 flex justify-end space-x-4">
                        <a href="{{ route('admin.app-config.edit', $appConfig) }}"
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit Configuration
                        </a>

                        <form method="POST" action="{{ route('admin.app-config.destroy', $appConfig) }}"
                              class="inline" onsubmit="return confirm('Are you sure you want to delete this configuration?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Delete Configuration
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
