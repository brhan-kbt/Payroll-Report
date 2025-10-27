<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('FCM Notifications') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('fcm.history') }}"
                   class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    View History
                </a>
                <a href="{{ route('fcm.service-account') }}"
                   class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                    Service Account
                </a>
                <a href="{{ route('fcm.send') }}"
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Send Notification
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Tokens</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100" id="total-tokens">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Tokens</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100" id="active-tokens">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10m-10 0a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2M9 8h6m-6 4h6m-6 4h6" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Platforms</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100" id="platforms-count">-</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Platform Distribution -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Platform Distribution</h3>
                    <div id="platform-stats" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Platform stats will be loaded here -->
                    </div>
                </div>
            </div>

            <!-- Recent Tokens -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Recent FCM Tokens</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Token</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Platform</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Last Used</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tokens-table" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <!-- Tokens will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Load FCM statistics
        async function loadStats() {
            try {
                const response = await fetch('/api/v1/admin/fcm/stats', {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    credentials: 'same-origin'
                });

                if (response.ok) {
                    const data = await response.json();
                    document.getElementById('total-tokens').textContent = data.data.total_tokens;
                    document.getElementById('active-tokens').textContent = data.data.active_tokens;
                    document.getElementById('platforms-count').textContent = Object.keys(data.data.platforms).length;

                    // Update platform distribution
                    const platformStats = document.getElementById('platform-stats');
                    platformStats.innerHTML = '';

                    Object.entries(data.data.platforms).forEach(([platform, count]) => {
                        const platformDiv = document.createElement('div');
                        platformDiv.className = 'text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg';
                        platformDiv.innerHTML = `
                            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">${count}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400 capitalize">${platform}</div>
                        `;
                        platformStats.appendChild(platformDiv);
                    });
                }
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }

        // Load recent tokens
        async function loadTokens() {
            try {
                const response = await fetch('/api/v1/admin/fcm/tokens?per_page=10', {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    credentials: 'same-origin'
                });

                if (response.ok) {
                    const data = await response.json();
                    const tbody = document.getElementById('tokens-table');
                    tbody.innerHTML = '';

                    data.data.data.forEach(token => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                <div class="truncate max-w-xs">${token.token.substring(0, 20)}...</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    ${token.platform || 'Unknown'}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${token.is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'}">
                                    ${token.is_active ? 'Active' : 'Inactive'}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                ${token.last_used_at ? new Date(token.last_used_at).toLocaleDateString() : 'Never'}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="toggleToken('${token.id}', ${token.is_active})"
                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    ${token.is_active ? 'Deactivate' : 'Activate'}
                                </button>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                }
            } catch (error) {
                console.error('Error loading tokens:', error);
            }
        }

        // Toggle token status
        async function toggleToken(tokenId, isActive) {
            try {
                const response = await fetch(`/fcm/tokens/${tokenId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                if (response.ok) {
                    loadTokens();
                    loadStats();
                }
            } catch (error) {
                console.error('Error toggling token:', error);
            }
        }

        // Load data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadStats();
            loadTokens();
        });
    </script>
</x-app-layout>
