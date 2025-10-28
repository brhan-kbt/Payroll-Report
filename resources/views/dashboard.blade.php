<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Dashboard Overview') }}
                </h2>
                <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Welcome to your payroll management system</p>
            </div>
            <div class="flex items-center space-x-2 text-blue-600 dark:text-blue-400">
                <i class="fas fa-chart-line text-xl"></i>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Card -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-3xl shadow-2xl overflow-hidden mb-8">
                <div class="p-8 text-white">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex items-center space-x-4 mb-4 lg:mb-0">
                            <div class="p-3 bg-white/20 rounded-2xl backdrop-blur-sm">
                                <i class="fas fa-user-circle text-3xl"></i>
                            </div>
                            <div>
                                <h3 class="text-3xl font-bold">Welcome back, {{ Auth::user()->name }}! 👋</h3>
                                <p class="text-blue-100 text-lg mt-1">Here's what's happening with your payroll system today.</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 bg-white/10 px-4 py-2 rounded-2xl backdrop-blur-sm">
                            <i class="fas fa-calendar-day"></i>
                            <span>{{ now()->format('l, F j, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
                <!-- Total Employees Card -->
                <div class="group cursor-pointer">
                    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-3xl p-1 shadow-2xl transform group-hover:scale-105 transition-all duration-300">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 h-full">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-blue-600 dark:text-blue-400 text-sm font-semibold uppercase tracking-wide">Total Employees</p>
                                    <p class="text-3xl font-bold text-gray-800 dark:text-white mt-2">{{ \App\Models\Employee::count() }}</p>
                                    <p class="text-blue-600 dark:text-blue-400 text-sm font-medium">Active Staff</p>
                                </div>
                                <div class="p-3 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900 dark:to-blue-800 rounded-2xl transform group-hover:scale-110 transition-transform">
                                    <i class="fas fa-users text-blue-600 dark:text-blue-400 text-2xl"></i>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 mt-4">
                                <div class="w-2 h-2 bg-blue-400 rounded-full animate-pulse"></div>
                                <span class="text-blue-600 dark:text-blue-400 text-xs font-medium">All active employees</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Payroll Card -->
                <div class="group cursor-pointer">
                    <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-3xl p-1 shadow-2xl transform group-hover:scale-105 transition-all duration-300">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 h-full">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-green-600 dark:text-green-400 text-sm font-semibold uppercase tracking-wide">Total Payroll</p>
                                    <p class="text-3xl font-bold text-gray-800 dark:text-white mt-2">{{ \App\Models\Payroll::count() }}</p>
                                    <p class="text-green-600 dark:text-green-400 text-sm font-medium">Imported Payrolls</p>
                                </div>
                                <div class="p-3 bg-gradient-to-br from-green-100 to-green-200 dark:from-green-900 dark:to-green-800 rounded-2xl transform group-hover:scale-110 transition-transform">
                                    <i class="fas fa-file-invoice-dollar text-green-600 dark:text-green-400 text-2xl"></i>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 mt-4">
                                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                                <span class="text-green-600 dark:text-green-400 text-xs font-medium">All payroll records</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity Card -->
                <div class="group cursor-pointer">
                    <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-3xl p-1 shadow-2xl transform group-hover:scale-105 transition-all duration-300">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 h-full">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-purple-600 dark:text-purple-400 text-sm font-semibold uppercase tracking-wide">This Month</p>
                                    <p class="text-3xl font-bold text-gray-800 dark:text-white mt-2">
                                        {{ \App\Models\Payroll::whereMonth('created_at', now()->month)->count() }}
                                    </p>
                                    <p class="text-purple-600 dark:text-purple-400 text-sm font-medium">Imported Payrolls</p>
                                </div>
                                <div class="p-3 bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-900 dark:to-purple-800 rounded-2xl transform group-hover:scale-110 transition-transform">
                                    <i class="fas fa-chart-bar text-purple-600 dark:text-purple-400 text-2xl"></i>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 mt-4">
                                <div class="w-2 h-2 bg-purple-400 rounded-full animate-pulse"></div>
                                <span class="text-purple-600 dark:text-purple-400 text-xs font-medium">{{ now()->format('F Y') }} activity</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Management Cards -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-8">
                <!-- Employees Management -->
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 text-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                                    <i class="fas fa-users text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold">Employees Management</h3>
                                    <p class="text-blue-100 text-sm">Manage your workforce</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-blue-200"></i>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">
                            Create, edit, and manage your employee database. Import employees in bulk and maintain up-to-date staff information.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('employees.index') }}"
                               class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 flex items-center justify-center gap-2 group">
                                <i class="fas fa-list group-hover:scale-110 transition-transform"></i>
                                View All Employees
                            </a>
                            <a href="{{ route('employees.create') }}"
                               class="bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 flex items-center justify-center gap-2 group">
                                <i class="fas fa-plus group-hover:scale-110 transition-transform"></i>
                                Add New Employee
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Payroll Management -->
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                    <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4 text-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                                    <i class="fas fa-file-invoice-dollar text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold">Payroll Management</h3>
                                    <p class="text-green-100 text-sm">Process salaries & payments</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-green-200"></i>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">
                            Import payroll data, send SMS notifications to employees, and manage salary distributions efficiently.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('payrolls.index') }}"
                               class="bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 flex items-center justify-center gap-2 group">
                                <i class="fas fa-list group-hover:scale-110 transition-transform"></i>
                                View Payrolls
                            </a>
                            <a href="{{ route('payrolls.create') }}"
                               class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 flex items-center justify-center gap-2 group">
                                <i class="fas fa-plus group-hover:scale-110 transition-transform"></i>
                                Create Payroll
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
