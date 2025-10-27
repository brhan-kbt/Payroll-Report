<div>
    <!-- Import Button in Parent Component -->
    <button type="button" wire:click="openImportModal"
        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
        <i class="fas fa-file-import mr-2"></i> Import
    </button>

    <!-- Modal -->
    @if ($showModal)
        <!-- Backdrop - Separate from modal content -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-40"></div>

        <!-- Modal Container -->
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-96 p-6 transform transition-transform duration-300">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payroll Month</label>
                    <input type="month" wire:model="payrollMonth"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                    @error('payrollMonth')
                        <span class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</span>
                    @enderror
                </div>


                <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Import Employees</h2>

                <input type="file" wire:model="file"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white disabled:opacity-50"
                    wire:loading.attr="disabled">

                <!-- Show validation errors -->
                @error('file')
                    <div class="mt-2 p-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md">
                        <span class="text-red-600 dark:text-red-400 text-sm">{{ $message }}</span>
                    </div>
                @enderror

                <!-- Show session messages -->
                @if (session()->has('message'))
                    <div
                        class="mt-2 p-2 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-md">
                        <span class="text-green-600 dark:text-green-400 text-sm">{{ session('message') }}</span>
                    </div>
                @endif

                @if (session()->has('warning'))
                    <div
                        class="mt-2 p-2 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-md">
                        <span class="text-yellow-600 dark:text-yellow-400 text-sm">{{ session('warning') }}</span>
                    </div>
                @endif

                <!-- Show session errors -->
                @if (session()->has('error'))
                    <div
                        class="mt-2 p-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md">
                        <span class="text-red-600 dark:text-red-400 text-sm">{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Import Statistics -->
                @if (!empty($importStats))
                    <div
                        class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-md">
                        <div class="grid grid-cols-3 gap-4 text-sm">
                            <div class="text-center">
                                <div class="font-semibold text-blue-600 dark:text-blue-400">Total</div>
                                <div class="text-2xl font-bold text-blue-700 dark:text-blue-300">
                                    {{ $importStats['total'] }}</div>
                            </div>
                            <div class="text-center">
                                <div class="font-semibold text-green-600 dark:text-green-400">Success</div>
                                <div class="text-2xl font-bold text-green-700 dark:text-green-300">
                                    {{ $importStats['success'] }}</div>
                            </div>
                            <div class="text-center">
                                <div class="font-semibold text-red-600 dark:text-red-400">Errors</div>
                                <div class="text-2xl font-bold text-red-700 dark:text-red-300">
                                    {{ $importStats['errors'] }}</div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Import Errors -->
                @if (!empty($importErrors))
                    <div class="mt-4">
                        <h3 class="text-md font-semibold text-red-600 dark:text-red-400 mb-2">Import Errors:</h3>
                        <div class="max-h-32 overflow-y-auto border border-red-200 dark:border-red-800 rounded-md">
                            <table class="min-w-full divide-y divide-red-200 dark:divide-red-800">
                                <thead class="bg-red-50 dark:bg-red-900/20">
                                    <tr>
                                        <th
                                            class="px-3 py-2 text-left text-xs font-medium text-red-600 dark:text-red-400">
                                            Row</th>
                                        <th
                                            class="px-3 py-2 text-left text-xs font-medium text-red-600 dark:text-red-400">
                                            Employee ID</th>
                                        <th
                                            class="px-3 py-2 text-left text-xs font-medium text-red-600 dark:text-red-400">
                                            Error</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-red-100 dark:divide-red-900/30">
                                    @foreach ($importErrors as $error)
                                        <tr class="bg-white dark:bg-gray-800 hover:bg-red-50 dark:hover:bg-red-900/10">
                                            <td class="px-3 py-2 text-sm text-gray-900 dark:text-white">
                                                {{ $error['row'] }}</td>
                                            <td class="px-3 py-2 text-sm text-gray-900 dark:text-white">
                                                {{ $error['employee_id'] }}</td>
                                            <td class="px-3 py-2 text-sm text-red-600 dark:text-red-400">
                                                {{ $error['message'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if ($file)
                    <div class="mt-2 p-2 bg-gray-100 dark:bg-gray-700 rounded-md">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Selected file:</p>
                        <p class="text-sm text-gray-800 dark:text-gray-200 break-all overflow-hidden">
                            {{ $file->getClientOriginalName() }}
                        </p>
                    </div>
                @endif

                <div class="mt-6 flex justify-end space-x-2">
                    <button type="button" wire:click="closeImportModal"
                        class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded transition duration-200 disabled:opacity-50"
                        wire:loading.attr="disabled">
                        {{ empty($importErrors) ? 'Cancel' : 'Close' }}
                    </button>

                    <button type="button" wire:click="import" @if (!$file) disabled @endif
                        class="px-8 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded transition duration-200 disabled:opacity-50 flex items-center justify-center min-w-20 relative"
                        wire:loading.attr="disabled">

                        <!-- Single span that changes content -->
                        <span class="flex items-center transition-opacity duration-200" wire:loading.class="opacity-0"
                            wire:loading.remove.class="opacity-100">
                            <i class="fas fa-file-import mr-2"></i> Import
                        </span>

                        <!-- Loading spinner that only shows during import -->
                        <span
                            class="flex items-center absolute inset-0 justify-center transition-opacity duration-200 opacity-0 "
                            wire:loading.class="opacity-100">
                            <i class="fas fa-spinner fa-spin mr-2"></i> Importing...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
