<div class="space-y-6">
    <!-- Search & Actions Card -->
    <div
        class="bg-white dark:bg-slate-900 rounded-2xl shadow-[0_0_20px_rgba(0,0,0,0.08)] dark:shadow-[0_0_10px_rgba(255,255,255,0.1)] p-6 border border-gray-100 dark:border-gray-800">
        <!-- Search and Action Buttons -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
            <!-- Search Input -->
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" placeholder="Search by name, ID, position, department..." wire:model.live="search"
                    class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white transition-all duration-200">
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3">
                <!-- Import Component -->
                @livewire('employee.import')

                <!-- Add Employee Button -->
                <a href="{{ route('employees.create') }}"
                    class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg flex items-center gap-2">
                    <i class="fas fa-plus-circle"></i>
                    <span>Add Employee</span>
                </a>
            </div>
        </div>

        <!-- Results Info & Per Page Selector -->
        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
            <!-- Results Info -->
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-3 sm:mb-0">
                <i class="fas fa-info-circle text-blue-500"></i>
                @if ($employees->total() > 0)
                    <span>Showing <span
                            class="font-semibold text-gray-800 dark:text-gray-200">{{ $employees->firstItem() }}-{{ $employees->lastItem() }}</span>
                        of <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $employees->total() }}</span>
                        employees</span>
                @else
                    <span>No employees found</span>
                @endif
            </div>

            <!-- Items Per Page Selector -->
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-600 dark:text-gray-400">Show:</span>
                <select wire:model.live="perPage"
                    class="px-3 py-2 w-20 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white text-sm transition-all duration-200">
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

    <!-- Employee Table Card -->
    <div
        class="bg-white dark:bg-slate-900 px-3 md:px-6 rounded-2xl shadow-[0_0_20px_rgba(0,0,0,0.08)] dark:shadow-[0_0_10px_rgba(255,255,255,0.1)] overflow-hidden border border-gray-100 dark:border-gray-800">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr
                        class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 border-b border-gray-200 dark:border-gray-700">
                        <th class="py-4 px-6 text-left">
                            <div
                                class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-semibold text-sm uppercase tracking-wider">
                                <i class="fas fa-hashtag text-blue-500"></i>
                                ID
                            </div>
                        </th>
                        <th class="py-4 px-6 text-left">
                            <div
                                class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-semibold text-sm uppercase tracking-wider">
                                <i class="fas fa-user text-green-500"></i>
                                Name
                            </div>
                        </th>
                        <th class="py-4 px-6 text-left">
                            <div
                                class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-semibold text-sm uppercase tracking-wider">
                                <i class="fas fa-id-card text-purple-500"></i>
                                Employee ID
                            </div>
                        </th>
                        <th class="py-4 px-6 text-left">
                            <div
                                class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-semibold text-sm uppercase tracking-wider">
                                <i class="fas fa-building text-orange-500"></i>
                                Phone
                            </div>
                        </th>
                        <th class="py-4 px-6 text-left">
                            <div
                                class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-semibold text-sm uppercase tracking-wider">
                                <i class="fas fa-briefcase text-red-500"></i>
                                Status
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
                    @forelse($employees as $employee)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors duration-150 group">
                            <td class="py-4 px-6">
                                <span
                                    class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg text-sm font-medium">
                                    {{ $employee->id }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                        {{ substr($employee->name, 0, 1) }}
                                    </div>
                                    <span
                                        class="font-medium text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                        {{ $employee->name }}
                                    </span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span
                                    class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 px-3 py-1 rounded-lg text-sm font-mono">
                                    {{ $employee->employee_id }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-gray-700 dark:text-gray-300">{{ $employee->phone ?? '—' }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-gray-700 dark:text-gray-300">
                                    @if ($employee->is_active)
                                        <span
                                            class="bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 px-3 py-1 rounded-lg text-sm font-medium">
                                            Active
                                        </span>
                                    @else
                                        <span
                                            class="bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 px-3 py-1 rounded-lg text-sm font-medium">
                                            Inactive
                                        </span>
                                    @endif
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2">
                                    <!-- Edit Button -->
                                    <a href="{{ route('employees.edit', $employee) }}"
                                        class="inline-flex items-center justify-center w-10 h-10 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/40 text-blue-600 dark:text-blue-400 rounded-xl transition-all duration-200 transform hover:scale-110 group/edit"
                                        title="Edit Employee">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>

                                    <!-- Delete Button -->
                                    <form action="{{ route('employees.destroy', $employee) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this employee?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center justify-center w-10 h-10 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 text-red-600 dark:text-red-400 rounded-xl transition-all duration-200 transform hover:scale-110 group/delete"
                                            title="Delete Employee">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </form>

                                    <!-- View Button -->
                                    <a href="{{ route('employees.show', $employee) }}"
                                        class="inline-flex items-center justify-center w-10 h-10 bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/40 text-green-600 dark:text-green-400 rounded-xl transition-all duration-200 transform hover:scale-110 group/view"
                                        title="View Details">
                                        <i class="fas fa-eye text-sm"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 px-6 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400 dark:text-gray-500">
                                    <i class="fas fa-users text-4xl mb-4 opacity-50"></i>
                                    <p class="text-lg font-medium text-gray-500 dark:text-gray-400 mb-2">No employees
                                        found</p>
                                    <p class="text-sm text-gray-400 dark:text-gray-500">Try adjusting your search
                                        criteria or add a new employee.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if ($employees->hasPages())
        <div
            class="bg-white dark:bg-slate-900 rounded-2xl shadow-[0_0_20px_rgba(0,0,0,0.08)] dark:shadow-[0_0_10px_rgba(255,255,255,0.1)] p-6 border border-gray-100 dark:border-gray-800">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Page {{ $employees->currentPage() }} of {{ $employees->lastPage() }}
                </div>
                <div class="flex items-center gap-2">
                    {{ $employees->links() }}
                </div>
            </div>
        </div>
    @endif
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
    </style>
</div>
