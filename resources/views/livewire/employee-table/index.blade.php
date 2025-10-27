<div>
    <div
        class="mb-4 bg-white dark:bg-slate-900 shadow-[0_0_12px_rgba(0,0,0,0.1)] dark:shadow-[0_0_5px_rgba(255,255,255,0.15)]

            px-4 py-4">
        <!-- Search & Filter -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 space-y-2 sm:space-y-0">
            <div class="flex w-full space-x-2">
                <!-- Search input takes full available width -->
                <input type="text" placeholder="Search by name, ID, position..." wire:model.live="search"
                    class="flex-grow px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white">
                <!-- Button stays at the end -->
                <a href="{{ route('employees.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                    <i class="fas fa-plus mr-2"></i>
                    Employee
                </a>
                {{-- import | export --}}
                @livewire('employee.import')



            </div>
        </div>

        <!-- Items Per Page & Results Info -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 space-y-2 sm:space-y-0">
            <!-- Results Info -->
            <div class="text-sm text-gray-600 dark:text-gray-400">
                @if ($employees->total() > 0)
                    Showing {{ $employees->firstItem() }} to {{ $employees->lastItem() }} of {{ $employees->total() }}
                    results
                @else
                    No results found
                @endif
            </div>

            <!-- Items Per Page Selector -->
            <div class="flex items-center space-x-2">
                <label for="perPage" class="text-sm text-gray-600 dark:text-gray-400"></label>
                <select wire:model.live="perPage" id="perPage"
                    class="px-3 w-20 py-1 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white text-sm">
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
    <!-- Employee Table -->
    <div class="overflow-x-auto shadow-[0_0_12px_rgba(0,0,0,0.1)] dark:shadow-[0_0_5px_rgba(255,255,255,0.15)]">
        <table class="min-w-full bg-white dark:bg-gray-800 shadow-xl ">
            <thead>
                <tr
                    class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-200 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">ID</th>
                    <th class="py-3 px-6 text-left">Name</th>
                    <th class="py-3 px-6 text-left">Employee ID</th>
                    <th class="py-3 px-6 text-left">Department</th>
                    <th class="py-3 px-6 text-left">Position</th>
                    <th class="py-3 px-6 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 dark:text-gray-300 text-sm font-light">
                @forelse($employees as $employee)
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-900">
                        <td class="py-3 px-6 text-left">{{ $employee->id }}</td>
                        <td class="py-3 px-6 text-left">{{ $employee->name }}</td>
                        <td class="py-3 px-6 text-left">{{ $employee->employee_id }}</td>
                        <td class="py-3 px-6 text-left">{{ $employee->department }}</td>
                        <td class="py-3 px-6 text-left">{{ $employee->position }}</td>
                        <td class="py-3 px-6 text-left flex space-x-2 justify-end">
                            <a href="{{ route('employees.edit', $employee) }}"
                                class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-1 rounded text-sm">
                                <i class="fas fa-edit "></i>
                            </a>

                            <form action="{{ route('employees.destroy', $employee) }}" method="POST"
                                onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-500 hover:bg-red-600 text-white py-1 px-1 rounded text-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-6 text-gray-500 dark:text-gray-400">No employees found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $employees->links() }}
    </div>
</div>
