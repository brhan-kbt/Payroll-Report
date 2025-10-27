<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('App Configurations') }}
            </h2>
           
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Search and Filter Bar -->
                    <div class="mb-6 flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <input type="text"
                                   id="searchInput"
                                   placeholder="Search configurations..."
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        <div class="flex gap-2">
                            <select id="typeFilter" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">All Types</option>
                                <option value="string">String</option>
                                <option value="boolean">Boolean</option>
                                <option value="integer">Integer</option>
                                <option value="json">JSON</option>
                            </select>
                            <select id="publicFilter" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">All Access</option>
                                <option value="public">Public</option>
                                <option value="private">Private</option>
                            </select>
                        </div>
                    </div>

                    <!-- Configurations Grid -->
                    <div id="configGrid" class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                        @forelse($configs as $config)
                            <div class="config-card bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all duration-200 overflow-hidden"
                                 data-key="{{ strtolower($config->key) }}"
                                 data-type="{{ $config->type }}"
                                 data-public="{{ $config->is_public ? 'public' : 'private' }}">

                                <!-- Card Header -->
                                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">
                                                {{ $config->key }}
                                            </h3>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="px-2 py-1 text-xs rounded-full {{ $config->type === 'boolean' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : ($config->type === 'json' ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200' : ($config->type === 'integer' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200')) }}">
                                                    {{ ucfirst($config->type) }}
                                                </span>
                                                @if($config->is_public)
                                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        <i class="fas fa-globe mr-1"></i>Public
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                                        <i class="fas fa-lock mr-1"></i>Private
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-1 ml-2">
                                            <a href="{{ route('admin.app-config.show', $config) }}"
                                               class="p-1 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                                               title="View">
                                                <i class="fas fa-eye text-sm"></i>
                                            </a>
                                            <a href="{{ route('admin.app-config.edit', $config) }}"
                                               class="p-1 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors"
                                               title="Edit">
                                                <i class="fas fa-edit text-sm"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.app-config.destroy', $config) }}"
                                                  class="inline" onsubmit="return confirm('Are you sure you want to delete this configuration?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="p-1 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors"
                                                        title="Delete">
                                                    <i class="fas fa-trash text-sm"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Card Body -->
                                <div class="p-4">
                                    <div class="mb-3">
                                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Value</label>
                                        <div class="mt-1">
                                            @if($config->type === 'json')
                                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 max-h-32 overflow-y-auto">
                                                    <pre class="text-xs text-gray-800 dark:text-gray-200 whitespace-pre-wrap">{{ json_encode($config->typed_value, JSON_PRETTY_PRINT) }}</pre>
                                                </div>
                                            @elseif($config->type === 'boolean')
                                                <div class="flex items-center">
                                                    <div class="flex items-center h-6 w-11 rounded-full {{ $config->typed_value ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600' }} transition-colors">
                                                        <div class="h-4 w-4 bg-white rounded-full shadow transform transition-transform {{ $config->typed_value ? 'translate-x-6' : 'translate-x-1' }}"></div>
                                                    </div>
                                                    <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $config->typed_value ? 'Enabled' : 'Disabled' }}
                                                    </span>
                                                </div>
                                            @else
                                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                                    <p class="text-sm text-gray-900 dark:text-gray-100 break-words">
                                                        {{ Str::limit($config->value, 100) }}
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    @if($config->description)
                                        <div>
                                            <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Description</label>
                                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300 line-clamp-2">
                                                {{ $config->description }}
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Card Footer -->
                                <div class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                                    <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                        <span>Updated {{ $config->updated_at->diffForHumans() }}</span>
                                        <span>ID: {{ $config->id }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-cog text-3xl text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No configurations found</h3>
                                <p class="text-gray-500 dark:text-gray-400 mb-4">Get started by creating your first app configuration.</p>
                                <a href="{{ route('admin.app-config.create') }}"
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-plus mr-2"></i>Create Configuration
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const typeFilter = document.getElementById('typeFilter');
            const publicFilter = document.getElementById('publicFilter');
            const configCards = document.querySelectorAll('.config-card');

            function filterConfigs() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedType = typeFilter.value;
                const selectedPublic = publicFilter.value;

                configCards.forEach(card => {
                    const key = card.dataset.key;
                    const type = card.dataset.type;
                    const publicAccess = card.dataset.public;

                    const matchesSearch = key.includes(searchTerm);
                    const matchesType = !selectedType || type === selectedType;
                    const matchesPublic = !selectedPublic || publicAccess === selectedPublic;

                    if (matchesSearch && matchesType && matchesPublic) {
                        card.style.display = 'block';
                        card.style.animation = 'fadeIn 0.3s ease-in-out';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }

            searchInput.addEventListener('input', filterConfigs);
            typeFilter.addEventListener('change', filterConfigs);
            publicFilter.addEventListener('change', filterConfigs);

            // Add CSS animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes fadeIn {
                    from { opacity: 0; transform: translateY(10px); }
                    to { opacity: 1; transform: translateY(0); }
                }
                .line-clamp-2 {
                    display: -webkit-box;
                    -webkit-line-clamp: 2;
                    -webkit-box-orient: vertical;
                    overflow: hidden;
                }
            `;
            document.head.appendChild(style);
        });
    </script>
</x-app-layout>
