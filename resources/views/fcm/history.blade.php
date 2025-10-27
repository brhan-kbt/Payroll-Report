<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Notification History') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('fcm.send') }}"
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Send New Notification
                </a>
                <a href="{{ route('fcm.index') }}"
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to FCM Management
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter Options -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Filter Notifications</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="filter-platform" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Platform
                            </label>
                            <select id="filter-platform" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Platforms</option>
                                <option value="android">Android</option>
                                <option value="ios">iOS</option>
                                <option value="web">Web</option>
                            </select>
                        </div>
                        <div>
                            <label for="filter-status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status
                            </label>
                            <select id="filter-status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Status</option>
                                <option value="success">Success</option>
                                <option value="failed">Failed</option>
                            </select>
                        </div>
                        <div>
                            <label for="filter-date-from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                From Date
                            </label>
                            <input type="date" id="filter-date-from" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="filter-date-to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                To Date
                            </label>
                            <input type="date" id="filter-date-to" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div class="mt-4">
                        <button onclick="applyFilters()" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                            Apply Filters
                        </button>
                        <button onclick="clearFilters()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2">
                            Clear Filters
                        </button>
                    </div>
                </div>
            </div>

            <!-- Notifications List -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Notification History</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Body</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Platform</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Sent At</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="notifications-table" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <!-- Notifications will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                    <div id="pagination" class="mt-4">
                        <!-- Pagination will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let currentPage = 1;
        let currentFilters = {};

        // Load notifications
        async function loadNotifications(page = 1) {
            try {
                const params = new URLSearchParams({
                    page: page,
                    ...currentFilters
                });

                const response = await fetch(`/api/v1/admin/fcm/notifications?${params}`, {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    credentials: 'same-origin'
                });

                if (response.ok) {
                    const data = await response.json();
                    displayNotifications(data.data);
                    displayPagination(data);
                } else {
                    console.error('Failed to load notifications');
                }
            } catch (error) {
                console.error('Error loading notifications:', error);
            }
        }

        // Display notifications
        function displayNotifications(notifications) {
            const tbody = document.getElementById('notifications-table');
            tbody.innerHTML = '';

            if (notifications.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            No notifications found
                        </td>
                    </tr>
                `;
                return;
            }

            notifications.forEach(notification => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                        <div class="truncate max-w-xs">${notification.title}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                        <div class="truncate max-w-xs">${notification.body}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            ${notification.platform || 'All'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${notification.status === 'success' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'}">
                            ${notification.status === 'success' ? 'Success' : 'Failed'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        ${new Date(notification.created_at).toLocaleString()}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button onclick="viewNotification(${notification.id})"
                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">
                            View
                        </button>
                        <button onclick="resendNotification(${notification.id})"
                                class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                            Resend
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        // Display pagination
        function displayPagination(data) {
            const pagination = document.getElementById('pagination');
            if (data.last_page <= 1) {
                pagination.innerHTML = '';
                return;
            }

            let paginationHTML = '<div class="flex items-center justify-between">';

            // Previous button
            if (data.current_page > 1) {
                paginationHTML += `<button onclick="loadNotifications(${data.current_page - 1})" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-l">Previous</button>`;
            }

            // Page numbers
            paginationHTML += '<div class="flex space-x-1">';
            for (let i = 1; i <= data.last_page; i++) {
                if (i === data.current_page) {
                    paginationHTML += `<span class="bg-blue-500 text-white font-bold py-2 px-4 rounded">${i}</span>`;
                } else {
                    paginationHTML += `<button onclick="loadNotifications(${i})" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">${i}</button>`;
                }
            }
            paginationHTML += '</div>';

            // Next button
            if (data.current_page < data.last_page) {
                paginationHTML += `<button onclick="loadNotifications(${data.current_page + 1})" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-r">Next</button>`;
            }

            paginationHTML += '</div>';
            pagination.innerHTML = paginationHTML;
        }

        // Apply filters
        function applyFilters() {
            currentFilters = {
                platform: document.getElementById('filter-platform').value,
                status: document.getElementById('filter-status').value,
                date_from: document.getElementById('filter-date-from').value,
                date_to: document.getElementById('filter-date-to').value
            };

            // Remove empty filters
            Object.keys(currentFilters).forEach(key => {
                if (!currentFilters[key]) {
                    delete currentFilters[key];
                }
            });

            loadNotifications(1);
        }

        // Clear filters
        function clearFilters() {
            document.getElementById('filter-platform').value = '';
            document.getElementById('filter-status').value = '';
            document.getElementById('filter-date-from').value = '';
            document.getElementById('filter-date-to').value = '';
            currentFilters = {};
            loadNotifications(1);
        }

        // View notification details
        function viewNotification(id) {
            // This would open a modal or navigate to a detail page
            alert('View notification details for ID: ' + id);
        }

        // Resend notification
        async function resendNotification(id) {
            if (!confirm('Are you sure you want to resend this notification?')) {
                return;
            }

            try {
                const response = await fetch(`/api/v1/admin/fcm/notifications/${id}/resend`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    credentials: 'same-origin'
                });

                const result = await response.json();

                if (result.success) {
                    alert('Notification resent successfully!');
                    loadNotifications(currentPage);
                } else {
                    alert('Failed to resend notification: ' + result.message);
                }
            } catch (error) {
                alert('Error resending notification: ' + error.message);
            }
        }

        // Load data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadNotifications();
        });
    </script>
    @endpush
</x-app-layout>
