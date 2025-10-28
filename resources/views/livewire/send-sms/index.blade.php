<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Send SMS') }}
                </h2>
                <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Send messages to employees or custom phone numbers</p>
            </div>
            <div class="flex items-center space-x-2 text-green-600 dark:text-green-400">
                <i class="fas fa-sms text-xl"></i>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success/Error Messages -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-2xl">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-check-circle text-green-500 text-lg"></i>
                        <span class="text-green-700 dark:text-green-300 text-sm font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-times-circle text-red-500 text-lg"></i>
                        <span class="text-red-700 dark:text-red-300 text-sm font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Main SMS Card -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                <!-- Header -->
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-8 py-6 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                                <i class="fas fa-sms text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold">Send Bulk SMS</h3>
                                <p class="text-green-100 text-sm">Reach multiple recipients at once</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 text-green-200">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <div class="p-8">
                    <form wire:submit.prevent="sendSms" class="space-y-6">
                        <!-- Recipient Selection Tabs -->
                        <div class="space-y-4">
                            <div class="flex border-b border-gray-200 dark:border-gray-600">
                                <button type="button"
                                    class="flex-1 py-3 px-4 text-center font-semibold border-b-2 transition-all duration-200 {{ $recipientType === 'employees' ? 'border-green-500 text-green-600 dark:text-green-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}"
                                    wire:click="setRecipientType('employees')">
                                    <i class="fas fa-users mr-2"></i>
                                    Select Employees
                                </button>
                                <button type="button"
                                    class="flex-1 py-3 px-4 text-center font-semibold border-b-2 transition-all duration-200 {{ $recipientType === 'manual' ? 'border-green-500 text-green-600 dark:text-green-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}"
                                    wire:click="setRecipientType('manual')">
                                    <i class="fas fa-mobile-alt mr-2"></i>
                                    Manual Numbers
                                </button>
                            </div>

                            <!-- Employee Selection -->
                            @if ($recipientType === 'employees')
                                <div class="space-y-3">
                                    <label class="flex items-center text-gray-700 dark:text-gray-300 font-semibold">
                                        <i class="fas fa-user-check text-green-500 mr-2"></i>
                                        Select Employees
                                    </label>

                                    <!-- Search -->
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-search text-gray-400"></i>
                                        </div>
                                        <input type="text"
                                               wire:model.debounce.300ms="employeeSearch"
                                               placeholder="Search employees by name or ID..."
                                               class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700 dark:text-white transition-all duration-200">
                                    </div>

                                    <!-- Selected Employees -->
                                    @if (count($selectedEmployees) > 0)
                                        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-green-700 dark:text-green-300 font-semibold text-sm">
                                                    Selected Employees ({{ count($selectedEmployees) }})
                                                </span>
                                                <button type="button"
                                                        wire:click="clearSelectedEmployees"
                                                        class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-200 text-sm font-medium">
                                                    Clear All
                                                </button>
                                            </div>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach ($selectedEmployees as $employeeId)
                                                    @php
                                                        $employee = $this->employees->firstWhere('id', $employeeId);
                                                    @endphp
                                                    @if ($employee)
                                                        <span class="inline-flex items-center gap-1 bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-300 px-3 py-1 rounded-full text-sm">
                                                            {{ $employee->name }}
                                                            <button type="button"
                                                                    wire:click="removeEmployee({{ $employeeId }})"
                                                                    class="hover:text-green-900 dark:hover:text-green-100">
                                                                <i class="fas fa-times text-xs"></i>
                                                            </button>
                                                        </span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Employee List -->
                                    <div class="max-h-60 overflow-y-auto border border-gray-200 dark:border-gray-600 rounded-xl">
                                        <div class="divide-y divide-gray-200 dark:divide-gray-600">
                                            @forelse($this->employees as $employee)
                                                <label class="flex items-center p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition-colors duration-150">
                                                    <input type="checkbox"
                                                           wire:model="selectedEmployees"
                                                           value="{{ $employee->id }}"
                                                           class="rounded border-gray-300 text-green-600 focus:ring-green-500 dark:border-gray-600 dark:bg-gray-700">
                                                    <div class="ml-3 flex items-center space-x-3 flex-1">
                                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                                            {{ substr($employee->name, 0, 1) }}
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <div class="font-medium text-gray-900 dark:text-white truncate">
                                                                {{ $employee->name }}
                                                            </div>
                                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                                {{ $employee->employee_id }} • {{ $employee->phone }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-right">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                            {{ $employee->department ?? '—' }}
                                                        </div>
                                                    </div>
                                                </label>
                                            @empty
                                                <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                                                    <i class="fas fa-users text-3xl mb-3 opacity-50"></i>
                                                    <p>No employees found</p>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Manual Phone Numbers -->
                            @if ($recipientType === 'manual')
                                <div class="space-y-3">
                                    <label class="flex items-center text-gray-700 dark:text-gray-300 font-semibold">
                                        <i class="fas fa-mobile-alt text-blue-500 mr-2"></i>
                                        Phone Numbers
                                    </label>
                                    <textarea wire:model="manualNumbers"
                                              placeholder="Enter phone numbers separated by commas&#10;Example: +251911223344, +251922334455, +251933445566"
                                              rows="4"
                                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-all duration-200 resize-none"></textarea>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Separate multiple numbers with commas. Include country code (e.g., +251 for Ethiopia).
                                    </p>

                                    @error('manualNumbers')
                                        <div class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                                            <div class="flex items-center gap-2 text-red-600 dark:text-red-400">
                                                <i class="fas fa-exclamation-circle"></i>
                                                <span class="text-sm font-medium">{{ $message }}</span>
                                            </div>
                                        </div>
                                    @enderror
                                </div>
                            @endif
                        </div>

                        <!-- Message Input -->
                        <div class="space-y-3">
                            <label class="flex items-center text-gray-700 dark:text-gray-300 font-semibold">
                                <i class="fas fa-envelope text-purple-500 mr-2"></i>
                                Message
                            </label>
                            <div class="relative">
                                <textarea wire:model="message"
                                          rows="6"
                                          placeholder="Type your message here..."
                                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white transition-all duration-200 resize-none"></textarea>
                                <div class="absolute bottom-3 right-3 flex items-center space-x-2">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        <span>{{ strlen($message) }}</span>/160
                                    </span>
                                </div>
                            </div>

                            @error('message')
                                <div class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                                    <div class="flex items-center gap-2 text-red-600 dark:text-red-400">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span class="text-sm font-medium">{{ $message }}</span>
                                    </div>
                                </div>
                            @enderror
                        </div>

                        <!-- Recipient Count & Preview -->
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                                <div>
                                    <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                        {{ $this->totalRecipients }}
                                    </div>
                                    <div class="text-sm text-blue-700 dark:text-blue-300 font-medium">Total Recipients</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                                        {{ $this->messagePages }}
                                    </div>
                                    <div class="text-sm text-green-700 dark:text-green-300 font-medium">SMS Pages</div>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                                        {{ $this->totalSms }}
                                    </div>
                                    <div class="text-sm text-purple-700 dark:text-purple-300 font-medium">Total SMS</div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200 dark:border-gray-600">
                            <button type="button"
                                    wire:click="clearForm"
                                    class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-xl transition-all duration-200 transform hover:scale-105 disabled:opacity-50 flex items-center justify-center gap-2">
                                <i class="fas fa-eraser"></i>
                                Clear All
                            </button>

                            <button type="submit"
                                    wire:loading.attr="disabled"
                                    class="flex-1 px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold rounded-xl transition-all duration-200 transform hover:scale-105 disabled:opacity-50 flex items-center justify-center gap-2 relative">

                                <span class="flex items-center gap-2 transition-opacity duration-200"
                                      wire:loading.class="opacity-0"
                                      wire:loading.remove.class="opacity-100">
                                    <i class="fas fa-paper-plane"></i>
                                    Send SMS ({{ $this->totalRecipients }} recipients)
                                </span>

                                <span class="flex items-center gap-2 absolute inset-0 justify-center transition-opacity duration-200 opacity-0"
                                      wire:loading.class="opacity-100">
                                    <i class="fas fa-spinner fa-spin"></i>
                                    Sending...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
