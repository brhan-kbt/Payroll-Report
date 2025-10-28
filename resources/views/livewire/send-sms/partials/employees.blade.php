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
