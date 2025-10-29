<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Employee Details') }}
                </h2>
                <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">View complete employee information</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('employees.edit', $employee->id) }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl font-semibold transition-all duration-200 transform hover:scale-105 flex items-center gap-2">
                    <i class="fas fa-edit"></i>
                    Edit Employee
                </a>
               
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Employee Header Card -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-3xl shadow-2xl overflow-hidden mb-8">
                <div class="p-8 text-white">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex items-center space-x-6 mb-6 lg:mb-0">
                            <div class="relative">
                                <div class="w-24 h-24 bg-white/20 rounded-3xl flex items-center justify-center text-white text-3xl font-bold backdrop-blur-sm border-2 border-white/30">
                                    {{ substr($employee->name, 0, 1) }}
                                </div>
                                @if($employee->is_active)
                                    <div class="absolute -bottom-2 -right-2 w-6 h-6 bg-green-400 border-2 border-white rounded-full flex items-center justify-center">
                                        <i class="fas fa-check text-white text-xs"></i>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold">{{ $employee->name }}</h1>
                                <div class="flex items-center space-x-4 mt-2">
                                    <span class="bg-white/20 px-3 py-1 rounded-full text-sm font-medium">
                                        {{ $employee->employee_id }}
                                    </span>
                                    <span class="bg-white/20 px-3 py-1 rounded-full text-sm font-medium">
                                        {{ $employee->position ?? 'No Position' }}
                                    </span>
                                    <span class="bg-white/20 px-3 py-1 rounded-full text-sm font-medium flex items-center gap-1">
                                        <i class="fas fa-building"></i>
                                        {{ $employee->department ?? 'No Department' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <div class="text-blue-100 text-sm">Employee Status</div>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-semibold {{ $employee->is_active ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                        <span class="w-2 h-2 rounded-full bg-white"></span>
                                        {{ $employee->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Left Column - Personal Information -->
                <div class="xl:col-span-2 space-y-8">
                    <!-- Personal Information Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                        <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4 text-white">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                                    <i class="fas fa-user text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold">Personal Information</h3>
                                    <p class="text-green-100 text-sm">Employee personal details</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Email -->
                                <div class="space-y-2">
                                    <label class="flex items-center text-sm font-semibold text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-envelope text-blue-500 mr-2"></i>
                                        Email Address
                                    </label>
                                    <div class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                        @if($employee->email)
                                            <a href="mailto:{{ $employee->email }}"
                                               class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium flex items-center gap-2">
                                                <i class="fas fa-external-link-alt text-xs"></i>
                                                {{ $employee->email }}
                                            </a>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500">Not provided</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="space-y-2">
                                    <label class="flex items-center text-sm font-semibold text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-phone text-green-500 mr-2"></i>
                                        Phone Number
                                    </label>
                                    <div class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                        @if($employee->phone)
                                            <a href="tel:{{ $employee->phone }}"
                                               class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 font-medium flex items-center gap-2">
                                                <i class="fas fa-phone-alt text-xs"></i>
                                                {{ $employee->phone }}
                                            </a>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500">Not provided</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Gender -->
                                <div class="space-y-2">
                                    <label class="flex items-center text-sm font-semibold text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-venus-mars text-purple-500 mr-2"></i>
                                        Gender
                                    </label>
                                    <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                        <span class="font-medium text-gray-900 dark:text-white">
                                            {{ $employee->gender ?? 'Not specified' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Date of Birth -->
                                <div class="space-y-2">
                                    <label class="flex items-center text-sm font-semibold text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-birthday-cake text-pink-500 mr-2"></i>
                                        Date of Birth
                                    </label>
                                    <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                        <span class="font-medium text-gray-900 dark:text-white">
                                            @if($employee->date_of_birth)
                                                {{ \Carbon\Carbon::parse($employee->date_of_birth)->format('M d, Y') }}
                                                <span class="text-gray-500 text-sm ml-2">
                                                    ({{ \Carbon\Carbon::parse($employee->date_of_birth)->age }} years old)
                                                </span>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500">Not provided</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                <!-- Date of Joining -->
                                <div class="space-y-2">
                                    <label class="flex items-center text-sm font-semibold text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-calendar-plus text-orange-500 mr-2"></i>
                                        Date of Joining
                                    </label>
                                    <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                        <span class="font-medium text-gray-900 dark:text-white">
                                            @if($employee->date_of_joining)
                                                {{ \Carbon\Carbon::parse($employee->date_of_joining)->format('M d, Y') }}
                                                <span class="text-gray-500 text-sm ml-2">
                                                    ({{ \Carbon\Carbon::parse($employee->date_of_joining)->diffForHumans() }})
                                                </span>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500">Not provided</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                <!-- Employment Duration -->
                                <div class="space-y-2">
                                    <label class="flex items-center text-sm font-semibold text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-clock text-yellow-500 mr-2"></i>
                                        Employment Duration
                                    </label>
                                    <div class="p-3 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                        <span class="font-medium text-gray-900 dark:text-white">
                                            @if($employee->date_of_joining)
                                                {{ \Carbon\Carbon::parse($employee->date_of_joining)->longAbsoluteDiffForHumans(\Carbon\Carbon::now(), 2) }}
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500">Not available</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Address -->
                            @if($employee->address)
                            <div class="mt-6 space-y-2">
                                <label class="flex items-center text-sm font-semibold text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                                    Address
                                </label>
                                <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                    <p class="text-gray-900 dark:text-white leading-relaxed">
                                        {{ $employee->address }}
                                    </p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>


                </div>

                <!-- Right Column - Quick Stats & Actions -->
                <div class="space-y-8">
                    <!-- Quick Stats -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4 text-white">
                            <div class="flex items-center space-x-3">
                                <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                                    <i class="fas fa-chart-bar text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold">Employee Stats</h3>
                                    <p class="text-blue-100 text-sm">Quick overview</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            <!-- Total Payrolls -->
                            <div class="flex items-center justify-between p-4 bg-blue-50 dark:bg-blue-900/20 rounded-2xl">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-800 rounded-2xl flex items-center justify-center">
                                        <i class="fas fa-file-invoice-dollar text-blue-600 dark:text-blue-400 text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                            {{ $employee->payrolls->count() }}
                                        </div>
                                        <div class="text-sm text-blue-700 dark:text-blue-300 font-medium">Total Payrolls</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Average Salary -->
                            @if($employee->payrolls->count() > 0)
                            <div class="flex items-center justify-between p-4 bg-green-50 dark:bg-green-900/20 rounded-2xl">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-green-100 dark:bg-green-800 rounded-2xl flex items-center justify-center">
                                        <i class="fas fa-chart-line text-green-600 dark:text-green-400 text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                                            {{ number_format($employee->payrolls->avg('net_pay'), 2) }}
                                        </div>
                                        <div class="text-sm text-green-700 dark:text-green-300 font-medium">Avg. Net Pay</div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Last Payroll -->
                            @if($employee->payrolls->count() > 0)
                            <div class="flex items-center justify-between p-4 bg-purple-50 dark:bg-purple-900/20 rounded-2xl">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-800 rounded-2xl flex items-center justify-center">
                                        <i class="fas fa-clock text-purple-600 dark:text-purple-400 text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="text-lg font-bold text-purple-600 dark:text-purple-400">
                                            {{ \Carbon\Carbon::parse($employee->payrolls->first()->payroll_month)->format('M Y') }}
                                        </div>
                                        <div class="text-sm text-purple-700 dark:text-purple-300 font-medium">Last Payroll</div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="p-6 space-y-3">
                            <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="w-full" onsubmit="return confirm('Are you sure you want to delete this employee?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 transform hover:scale-105 flex items-center justify-center gap-2">
                                    <i class="fas fa-trash"></i>
                                    Delete Employee
                                </button>
                            </form>
                        </div>
                        </div>
                    </div>




                </div>
            </div>
        </div>
    </div>
</x-app-layout>
