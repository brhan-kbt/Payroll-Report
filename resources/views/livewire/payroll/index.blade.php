<div class="space-y-6">
    <!-- Filters & Export Card -->
    <div
        class="bg-white dark:bg-slate-900 rounded-2xl shadow-[0_0_20px_rgba(0,0,0,0.08)] dark:shadow-[0_0_10px_rgba(255,255,255,0.1)] p-6 border border-gray-100 dark:border-gray-800">
        <!-- Search and Action Buttons -->
        <div class="flex flex-col lg:flex-row lg:items-center gap-4 mb-6">
            <!-- Search Input -->
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" placeholder="Search by employee name or ID..." wire:model.live="search"
                    class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white transition-all duration-200">
            </div>

            <!-- Month Filter -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-calendar text-gray-400"></i>
                </div>
                <input type="month" wire:model.live="month"
                    class="pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white transition-all duration-200">
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3">
                <!-- Import Component -->
                @livewire('payroll.import')

                <!-- Export Button -->
                <button wire:click="export"
                    class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg flex items-center gap-2">
                    <i class="fas fa-file-export"></i>
                    <span>Export</span>
                </button>
            </div>
        </div>

        <!-- Results Info & Per Page Selector -->
        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
            <!-- Results Info -->
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-3 sm:mb-0">
                <i class="fas fa-chart-bar text-blue-500"></i>
                @if ($payrolls->total() > 0)
                    <span>Showing <span
                            class="font-semibold text-gray-800 dark:text-gray-200">{{ $payrolls->firstItem() }}-{{ $payrolls->lastItem() }}</span>
                        of <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $payrolls->total() }}</span>
                        payroll records</span>
                @else
                    <span>No payroll records found</span>
                @endif
            </div>

            <!-- Items Per Page Selector -->
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-600 dark:text-gray-400">Show:</span>
                <select wire:model.live="perPage"
                    class="px-3 py-2 border w-20 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white text-sm transition-all duration-200">
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="text-sm text-gray-600 dark:text-gray-400">per page</span>
            </div>

            <button id="bulkDeleteBtn"
                class="bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg flex items-center gap-2 hidden">
                <i class="fas fa-trash"></i>
                <span>Delete Selected</span>
            </button>
        </div>
    </div>

    @if ($smsMessage)
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
            class="p-4 mb-4 text-white rounded bg-green-600">
            {{ $smsMessage }}
        </div>
    @endif

    <!-- Payroll Table Card -->
    <div
        class="bg-white dark:bg-slate-900 rounded-2xl shadow-[0_0_20px_rgba(0,0,0,0.08)] dark:shadow-[0_0_10px_rgba(255,255,255,0.1)] overflow-hidden border border-gray-100 dark:border-gray-800">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr
                        class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 border-b border-gray-200 dark:border-gray-700">
                        <th class="py-4 px-6 text-left">
                            <input type="checkbox" name="selectAll" id="selectAll"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        </th>
                        <th class="py-4 px-6 text-left">
                            <div
                                class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-semibold text-sm uppercase tracking-wider">
                                <i class="fas fa-user text-blue-500"></i>
                                Employee
                            </div>
                        </th>
                        <th class="py-4 px-6 text-left">
                            <div
                                class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-semibold text-sm uppercase tracking-wider">
                                <i class="fas fa-calendar text-green-500"></i>
                                Month
                            </div>
                        </th>
                        <th class="py-4 px-6 text-left">
                            <div
                                class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-semibold text-sm uppercase tracking-wider">
                                <i class="fas fa-money-bill-wave text-yellow-500"></i>
                                Gross Salary
                            </div>
                        </th>
                        <th class="py-4 px-6 text-left">
                            <div
                                class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-semibold text-sm uppercase tracking-wider">
                                <i class="fas fa-wallet text-purple-500"></i>
                                Net Salary
                            </div>
                        </th>

                        <th class="py-4 px-6 text-left">
                            <div
                                class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-semibold text-sm uppercase tracking-wider">
                                <i class="fas fa-wallet text-purple-500"></i>
                                SMS Status
                            </div>
                        </th>
                        <th class="py-4 px-6 text-left">
                            <div
                                class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-semibold text-sm uppercase tracking-wider">
                                <i class="fas fa-cog text-gray-500"></i>
                                Actions
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($payrolls as $payroll)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors duration-150 group">
                            <td class="py-4 px-6">
                                <input type="checkbox" name="payroll_ids[]" id="payroll_ids" value="{{ $payroll->id }}"
                                    class="payroll-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            </td>

                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                        {{ substr($payroll->employee->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div
                                            class="font-medium text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                            {{ $payroll->employee->name }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            ID: {{ $payroll->employee->employee_id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 px-3 py-1 rounded-lg text-sm font-medium">
                                        {{ \Carbon\Carbon::parse($payroll->payroll_month)->format('M Y') }}
                                    </span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <span class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ number_format($payroll->gross_earning, 2) }}
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Birr</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <span class="text-lg font-bold text-green-600 dark:text-green-400">
                                        {{ number_format($payroll->net_pay, 2) }}
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Birr</span>
                                </div>
                            </td>

                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold ">
                                        @if ($payroll->smsLogs()->count() > 0 && $payroll->smsLogs()->latest()->first()->status == 'success')
                                            <span
                                                class="flex items-center gap-2 bg-green-600 dark:bg-green-400 rounded-lg p-1 text-white">
                                                {{ $payroll->smsLogs()->latest()->first()->status }}

                                            </span>
                                        @else
                                            <span
                                                class="flex items-center ga1 bg-red-600 dark:bg-red-400 p-1 rounded-lg text-white">
                                                {{ $payroll->smsLogs()->latest()->first()->status ?? 'Pending' }}

                                            </span>
                                        @endif
                                    </span>
                                </div>
                            </td>

                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <!-- View Button -->
                                    <a href="{{ route('payrolls.show', $payroll) }}"
                                        class="inline-flex items-center justify-center w-10 h-10 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/40 text-blue-600 dark:text-blue-400 rounded-xl transition-all duration-200 transform hover:scale-110 group/view"
                                        title="View Payroll Details">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>

                                    {{-- delete payroll button   --}}
                                    <a href="{{ route('payrolls.deletePayroll', $payroll) }}"
                                        onclick="return confirm('Are you sure you want to delete this payroll?')"
                                        class="inline-flex items-center justify-center w-10 h-10 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 text-red-600 dark:text-red-400 rounded-xl transition-all duration-200 transform hover:scale-110 group/delete"
                                        title="Delete Payroll">
                                        <i class="fas fa-trash text-sm"></i>
                                    </a>

                                    <!-- Send SMS Button -->
                                    <button wire:click="sendSms({{ $payroll->id }})"
                                        class="inline-flex items-center justify-center w-10 h-10 bg-purple-50 dark:bg-purple-900/20 hover:bg-purple-100 dark:hover:bg-purple-900/40 text-purple-600 dark:text-purple-400 rounded-xl transition-all duration-200 transform hover:scale-110 group/sms"
                                        title="Resend SMS Notification">
                                        <i class="fas fa-paper-plane text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-16 px-6 text-center">
                                <div
                                    class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                    <i class="fas fa-file-invoice-dollar text-5xl mb-4 opacity-50"></i>
                                    <p class="text-lg font-medium text-gray-500 dark:text-gray-400 mb-2">No payroll
                                        records found</p>
                                    <p class="text-sm text-gray-400 dark:text-gray-500 max-w-md text-center">
                                        No payroll records match your current filters. Try adjusting your search
                                        criteria or import payroll data.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if ($payrolls->hasPages())
        <div
            class="bg-white dark:bg-slate-900 rounded-2xl shadow-[0_0_20px_rgba(0,0,0,0.08)] dark:shadow-[0_0_10px_rgba(255,255,255,0.1)] p-6 border border-gray-100 dark:border-gray-800">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Page {{ $payrolls->currentPage() }} of {{ $payrolls->lastPage() }}
                </div>
                <div class="flex items-center gap-2">
                    {{ $payrolls->links() }}
                </div>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Select All / Unselect All
            $('#selectAll').on('change', function() {
                $('.payroll-checkbox').prop('checked', $(this).is(':checked'));
                toggleBulkDeleteButton();
            });

            // Individual selection
            $(document).on('change', '.payroll-checkbox', function() {
                const total = $('.payroll-checkbox').length;
                const checked = $('.payroll-checkbox:checked').length;

                // If all are selected -> selectAll true, otherwise false
                $('#selectAll').prop('checked', total === checked);

                toggleBulkDeleteButton();
            });

            // Toggle delete button visibility
            function toggleBulkDeleteButton() {
                const anySelected = $('.payroll-checkbox:checked').length > 0;
                $('#bulkDeleteBtn').toggleClass('hidden', !anySelected);
            }

            // Bulk delete event
            $('#bulkDeleteBtn').on('click', function(e) {
                e.preventDefault();

                const ids = $('.payroll-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (ids.length === 0) {
                    alert('Please select at least one payroll record to delete.');
                    return;
                }

                if (!confirm(`Are you sure you want to delete ${ids.length} selected payroll(s)?`)) {
                    return;
                }

                // Send AJAX request to delete selected payrolls
                $.ajax({
                    url: "{{ route('payrolls.bulkDelete') }}", // you'll create this route
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        ids: ids
                    },
                    success: function(response) {
                        alert(response.message || 'Selected payrolls deleted successfully!');
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Something went wrong while deleting. Please try again.');
                    }
                });
            });

        });
    </script>


    <style>
        .pagination {
            display: flex;
            gap: 0.5rem;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .page-item .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 2.5rem;
            height: 2.5rem;
            padding: 0 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            color: #6b7280;
            text-decoration: none;
            transition: all 0.2s;
            font-weight: 500;
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            border-color: #3b82f6;
            color: white;
        }

        .page-item:not(.active) .page-link:hover {
            background-color: #f3f4f6;
            border-color: #d1d5db;
            color: #374151;
        }

        .dark .page-item .page-link {
            border-color: #4b5563;
            color: #9ca3af;
            background-color: #1f2937;
        }

        .dark .page-item:not(.active) .page-link:hover {
            background-color: #374151;
            border-color: #6b7280;
            color: #d1d5db;
        }

        .page-item.disabled .page-link {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Custom scrollbar for table */
        .overflow-x-auto::-webkit-scrollbar {
            height: 6px;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .dark .overflow-x-auto::-webkit-scrollbar-track {
            background: #374151;
        }

        .dark .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #6b7280;
        }
    </style>


</div>
