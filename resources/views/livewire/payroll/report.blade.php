<div class=" ">
    <div class="max-w-7xl mx-auto space-y-8">
        {{-- 🔍 Filters Card --}}
        <div
            class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/20 dark:border-gray-700/50 px-8 py-4">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-xl">
                            <i class="fas fa-filter text-blue-600 dark:text-blue-400 text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Payroll Records</h2>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Filter and manage payroll data</p>
                        </div>
                    </div>

                    <!-- Single Row Layout for Filters and Export Buttons -->
                    <div class="flex flex-col xl:flex-row gap-4 items-start xl:items-center justify-between">
                        <!-- Search and Filter Group -->
                        <div class="flex flex-col md:flex-row gap-4 items-start md:items-center flex-1 min-w-0">
                            <!-- Search Input -->
                            <div class="relative flex-1 min-w-[250px]">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                                <input type="text" wire:model.live="search" placeholder="Search employee name, ID..."
                                    class="w-full min-w-[250px] pl-12 pr-4 py-3.5 bg-white/50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 dark:text-white transition-all duration-300 shadow-sm" />
                            </div>

                            <!-- Month Filter -->
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-calendar-alt text-gray-400"></i>
                                </div>
                                <select wire:model.live="month"
                                    class="pl-12 pr-10 py-3.5 bg-white/50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 dark:text-white transition-all duration-300 shadow-sm appearance-none min-w-[180px]">
                                    <option value="">All Months</option>
                                    @for ($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                        </option>
                                    @endfor
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Export Buttons Group -->
                        <div class="flex flex-col sm:flex-row gap-3 shrink-0">
                            <button wire:click="exportPdf"
                                class="p-2 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center gap-3 group min-w-[140px] justify-center">
                                <div class="">
                                    <i class="fas fa-file-pdf text-white text-lg"></i>
                                </div>
                                PDF
                            </button>

                            <button wire:click="export"
                                class="p-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center gap-3 group min-w-[140px] justify-center">
                                <div>
                                    <i class="fas fa-file-excel text-white text-lg"></i>
                                </div>
                                Excel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- 💳 Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 xl:grid-cols-5 gap-6">
            <!-- Success SMS Card -->
            <div class="group cursor-pointer">
                <div
                    class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-3xl p-1 shadow-2xl transform group-hover:scale-105 transition-all duration-300">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 h-full">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-3xl font-bold text-gray-800 dark:text-white mt-2">
                                    {{ number_format($successSms, 0) }}</p>
                                <p class="text-green-600 dark:text-green-400 text-sm font-medium">Success SMS</p>
                            </div>
                            <div
                                class="p-3 bg-gradient-to-br from-green-100 to-green-200 dark:from-green-900 dark:to-green-800 rounded-2xl transform group-hover:scale-110 transition-transform">
                                <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-2xl"></i>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Failed SMS Card -->
            <div class="group cursor-pointer">
                <div
                    class="bg-gradient-to-br from-red-500 to-rose-600 rounded-3xl p-1 shadow-2xl transform group-hover:scale-105 transition-all duration-300">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 h-full">
                        <div class="flex items-center justify-between">
                            <div>

                                <p class="text-3xl font-bold text-gray-800 dark:text-white mt-2">
                                    {{ number_format($smsNotSent) }}</p>
                                <p class="text-red-600 dark:text-red-400 text-sm font-medium">SMS Not Sent</p>
                            </div>
                            <div
                                class="p-3 bg-gradient-to-br from-red-100 to-red-200 dark:from-red-900 dark:to-red-800 rounded-2xl transform group-hover:scale-110 transition-transform">
                                <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-2xl"></i>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

             <div class="group cursor-pointer">
                <div
                    class="bg-gradient-to-br from-red-500 to-rose-600 rounded-3xl p-1 shadow-2xl transform group-hover:scale-105 transition-all duration-300">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 h-full">
                        <div class="flex items-center justify-between">
                            <div>

                                <p class="text-3xl font-bold text-gray-800 dark:text-white mt-2">
                                    {{ number_format($failedSms) }}</p>
                                <p class="text-red-600 dark:text-red-400 text-sm font-medium">Failed SMS</p>
                            </div>
                            <div
                                class="p-3 bg-gradient-to-br from-red-100 to-red-200 dark:from-red-900 dark:to-red-800 rounded-2xl transform group-hover:scale-110 transition-transform">
                                <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-2xl"></i>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Total SMS Sent Card -->
            <div class="group cursor-pointer">
                <div
                    class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-3xl p-1 shadow-2xl transform group-hover:scale-105 transition-all duration-300">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 h-full">
                        <div class="flex items-center justify-between">
                            <div>

                                <p class="text-3xl font-bold text-gray-800 dark:text-white mt-2">
                                    {{ number_format($totalSmsSent, 0) }}</p>
                                <p class="text-blue-600 dark:text-blue-400 text-sm font-medium"> Total SMS Sent</p>
                            </div>
                            <div
                                class="p-3 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900 dark:to-blue-800 rounded-2xl transform group-hover:scale-110 transition-transform">
                                <i class="fas fa-paper-plane text-blue-600 dark:text-blue-400 text-2xl"></i>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Total Employees Card -->
            <div class="group cursor-pointer">
                <div
                    class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-3xl p-1 shadow-2xl transform group-hover:scale-105 transition-all duration-300">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 h-full">
                        <div class="flex items-center justify-between">
                            <div>

                                <p class="text-3xl font-bold text-gray-800 dark:text-white mt-2">
                                    {{ number_format($totalEmployees, 0) }}</p>
                                <p class="text-purple-600 dark:text-purple-400 text-sm font-medium"> Total Employees</p>
                            </div>
                            <div
                                class="p-3 bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-900 dark:to-purple-800 rounded-2xl transform group-hover:scale-110 transition-transform">
                                <i class="fas fa-users text-purple-600 dark:text-purple-400 text-2xl"></i>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- 📊 Payroll Table --}}
        <div
            class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg rounded-3xl shadow-2xl border border-white/20 dark:border-gray-700/50 overflow-hidden">
            <!-- Table Header -->
            <div class="px-8 py-6 border-b border-gray-200/50 dark:border-gray-700/50">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex items-center gap-3 mb-4 lg:mb-0">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-xl">
                            <i class="fas fa-table text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white">Payroll Records</h3>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <!-- Items Per Page Selector -->
                        <div class="flex items-center gap-3">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Show:</span>
                            <select wire:model.live="perPage"
                                class="px-3 py-2 w-20 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white text-sm transition-all duration-200">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span class="text-sm text-gray-600 dark:text-gray-400">per page</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr
                            class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <th class="px-8 py-4 text-left">
                                <div
                                    class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-semibold text-sm uppercase tracking-wider">
                                    <i class="fas fa-user text-blue-500"></i>
                                    Employee
                                </div>
                            </th>

                            <th class="px-8 py-4 text-left">
                                <div
                                    class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-semibold text-sm uppercase tracking-wider">
                                    <i class="fas fa-chart-line text-yellow-500"></i>
                                    Gross Salary
                                </div>
                            </th>
                            <th class="px-8 py-4 text-left">
                                <div
                                    class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-semibold text-sm uppercase tracking-wider">
                                    <i class="fas fa-wallet text-purple-500"></i>
                                    Net Pay
                                </div>
                            </th>

                            <th class="px-8 py-4 text-left">
                                <div
                                    class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-semibold text-sm uppercase tracking-wider">
                                    <i class="fas fa-calendar text-indigo-500"></i>
                                    Month
                                </div>
                            </th>

                            <th class="px-8 py-4 text-left">
                                <div
                                    class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-semibold text-sm uppercase tracking-wider">
                                    <i class="fas fa-tag text-red-500"></i>
                                    Status
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200/50 dark:divide-gray-700/50">
                        @forelse ($payrolls as $payroll)
                            <tr
                                class="hover:bg-gray-50/80 dark:hover:bg-gray-700/30 transition-all duration-200 group">
                                <!-- Employee -->
                                <td class="px-8 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="relative">
                                            <div
                                                class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center text-white font-semibold text-lg shadow-lg">
                                                {{ substr($payroll->employee->name, 0, 1) }}
                                            </div>
                                            <div
                                                class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-400 border-2 border-white dark:border-gray-800 rounded-full">
                                            </div>
                                        </div>
                                        <div>
                                            <div
                                                class="font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                                {{ $payroll->employee->name }}
                                            </div>
                                            <div class="text-gray-500 dark:text-gray-400 text-xs font-medium mt-1">
                                                {{ $payroll->employee->phone }} ID:
                                                {{ $payroll->employee->employee_id }}
                                            </div>
                                        </div>
                                    </div>
                                </td>



                                <!-- Gross Salary -->
                                <td class="px-8 py-4">
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ number_format($payroll->gross_earning, 2) }}
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Birr</span>
                                    </div>
                                </td>

                                <!-- Net Pay -->
                                <td class="px-8 py-4">
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-xl font-extrabold text-green-600 dark:text-green-400">
                                            {{ number_format($payroll->net_pay, 2) }}
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Birr</span>
                                    </div>
                                </td>




                                <!-- Date -->
                                <td class="px-8 py-4">
                                    <div class="text-gray-600 dark:text-gray-400 text-sm font-medium">
                                        {{ \Carbon\Carbon::parse($payroll->payroll_date)->format('d M Y') }}
                                    </div>
                                </td>


                                <!-- Status -->
                                <td class="px-8 py-4">
                                    <span
                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold shadow-sm
                                        ">
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
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-8 py-16 text-center">
                                    <div
                                        class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                        <div
                                            class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-3xl flex items-center justify-center mb-6">
                                            <i class="fas fa-inbox text-4xl opacity-50"></i>
                                        </div>
                                        <p class="text-xl font-semibold text-gray-500 dark:text-gray-400 mb-2">No
                                            payroll records found</p>
                                        <p class="text-gray-400 dark:text-gray-500 max-w-md text-center">
                                            Try adjusting your search criteria or filters to find what you're looking
                                            for.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div
                class="px-8 py-6 border-t border-gray-200/50 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-700/20">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        Showing <strong>{{ $payrolls->firstItem() ?? 0 }}</strong> to
                        <strong>{{ $payrolls->lastItem() ?? 0 }}</strong> of
                        <strong>{{ $payrolls->total() }}</strong> results
                    </div>
                    <div class="flex items-center">
                        {{ $payrolls->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
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
            min-width: 2.75rem;
            height: 2.75rem;
            padding: 0 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 1rem;
            color: #6b7280;
            text-decoration: none;
            transition: all 0.3s;
            font-weight: 600;
            background: white;
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            border-color: #3b82f6;
            color: white;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .page-item:not(.active) .page-link:hover {
            background-color: #3b82f6;
            border-color: #3b82f6;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        }

        .dark .page-item .page-link {
            border-color: #4b5563;
            color: #9ca3af;
            background: #1f2937;
        }

        .dark .page-item:not(.active) .page-link:hover {
            background-color: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }

        .page-item.disabled .page-link {
            opacity: 0.4;
            cursor: not-allowed;
            transform: none !important;
        }
    </style>
</div>
