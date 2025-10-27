<div>
    <div class="mb-4 bg-white dark:bg-slate-900 shadow px-4 py-4 rounded">
        <!-- Filters & Export -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 space-y-2 sm:space-y-0">
            <div class="flex w-full space-x-2">
                <input type="text" placeholder="Search by employee name or ID..." wire:model.live="search"
                    class="flex-grow px-4 py-2 border rounded-md focus:ring focus:ring-blue-300 dark:bg-gray-700 dark:text-white">

                <input type="month" wire:model.live="month"
                    class="px-3 py-2 border rounded-md dark:bg-gray-700 dark:text-white">

                <button wire:click="export"
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                    <i class="fas fa-file-export mr-2"></i> Export
                </button>
                @livewire('payroll.import')

            </div>
        </div>

        <!-- Items Per Page & Results Info -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 space-y-2 sm:space-y-0">
            <div class="text-sm text-gray-600 dark:text-gray-400">
                @if ($payrolls->total() > 0)
                    Showing {{ $payrolls->firstItem() }} to {{ $payrolls->lastItem() }} of {{ $payrolls->total() }}
                    results
                @else
                    No results found
                @endif
            </div>

            <div class="flex items-center space-x-2">
                <select wire:model.live="perPage"
                    class="px-3 w-20 py-1 border rounded-md dark:bg-gray-700 dark:text-white text-sm">
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

    <!-- Payroll Table -->
    <div class="overflow-x-auto shadow rounded">
        <table class="min-w-full bg-white dark:bg-gray-800">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-200 uppercase text-sm">
                    <th class="py-3 px-6 text-left">Employee</th>
                    <th class="py-3 px-6 text-left">Month</th>
                    <th class="py-3 px-6 text-left">Gross Salary</th>
                    <th class="py-3 px-6 text-left">Net Salary</th>
                    <th class="py-3 px-6 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 dark:text-gray-300 text-sm">
                @forelse($payrolls as $payroll)
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-900">
                        <td class="py-3 px-6">{{ $payroll->employee->name }}</td>
                        <td class="py-3 px-6">{{ \Carbon\Carbon::parse($payroll->payroll_month)->format('F Y') }}</td>
                        <td class="py-3 px-6">{{ number_format($payroll->gross_earning, 2) }}</td>
                        <td class="py-3 px-6">{{ number_format($payroll->net_pay, 2) }}</td>
                        <td class="py-3 px-6 flex space-x-2">
                            <a href="{{ route('payrolls.show', $payroll) }}"
                                class="bg-blue-500 hover:bg-blue-600 text-white py-1 px-2 rounded text-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-gray-500 dark:text-gray-400">No payroll records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $payrolls->links() }}
    </div>
</div>
