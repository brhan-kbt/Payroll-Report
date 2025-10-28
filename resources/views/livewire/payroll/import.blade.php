<div>
    <!-- Import Button in Parent Component -->
    <button type="button" wire:click="openImportModal"
        class="bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold py-1.5 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center gap-2 group">
        <div class="p-1.5 bg-white/20 rounded-lg group-hover:scale-110 transition-transform">
            <i class="fas fa-file-import text-white text-sm"></i>
        </div>
        <span>Import</span>
    </button>

    <!-- Modal -->
    @if ($showModal)
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity z-40 animate-fade-in"></div>

        <!-- Modal Container -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 animate-scale-in">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl w-full max-w-2xl transform transition-all duration-300 border border-white/20 dark:border-gray-700/50">

                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-8 py-6 rounded-t-3xl text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-white/20 rounded-xl backdrop-blur-sm">
                                <i class="fas fa-file-import text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold">Import Payroll Data</h2>
                                <p class="text-blue-100 text-sm mt-1">Upload payroll data for selected month</p>
                            </div>
                        </div>
                        <button type="button" wire:click="closeImportModal"
                                class="p-2 hover:bg-white/10 rounded-xl transition-colors duration-200">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="p-8 space-y-6">
                    <!-- Payroll Month Selection -->
                    <div class="space-y-3">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                            <i class="fas fa-calendar-alt text-blue-500"></i>
                            Payroll Month
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-calendar text-gray-400"></i>
                            </div>
                            <input type="month" wire:model="payrollMonth"
                                class="w-full pl-12 pr-4 py-3.5 bg-white/50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 dark:text-white transition-all duration-300 shadow-sm">
                        </div>
                        @error('payrollMonth')
                            <div class="p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                                <div class="flex items-center gap-2 text-red-600 dark:text-red-400">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span class="text-sm font-medium">{{ $message }}</span>
                                </div>
                            </div>
                        @enderror
                    </div>

                    <!-- File Upload Section -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <i class="fas fa-file-excel text-green-500"></i>
                                Select Excel/CSV File
                            </label>
                            <button type="button" wire:click="downloadSample"
                                class="text-sm bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-4 py-2 rounded-lg transition-all duration-200 transform hover:scale-105 flex items-center gap-2">
                                <i class="fas fa-download text-xs"></i>
                                Download Sample
                            </button>
                        </div>

                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-2xl p-6 text-center transition-all duration-300 hover:border-blue-400 dark:hover:border-blue-500 bg-gray-50/50 dark:bg-gray-700/30">
                            <input type="file" wire:model="file"
                                   class="w-full px-3 py-2 border-0 bg-transparent file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900/30 dark:file:text-blue-300 cursor-pointer"
                                   wire:loading.attr="disabled"
                                   accept=".xlsx,.xls,.csv">

                            @error('file')
                                <div class="mt-3 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl">
                                    <div class="flex items-center gap-2 text-red-600 dark:text-red-400">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span class="text-sm font-medium">{{ $message }}</span>
                                    </div>
                                </div>
                            @enderror
                        </div>

                        @if ($file)
                            <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-2xl">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-green-100 dark:bg-green-800 rounded-xl">
                                        <i class="fas fa-file-excel text-green-600 dark:text-green-400"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-green-700 dark:text-green-300 text-sm">File Selected</p>
                                        <p class="text-green-600 dark:text-green-400 text-sm truncate">{{ $file->getClientOriginalName() }}</p>
                                    </div>
                                    <span class="text-xs text-green-600 dark:text-green-400 font-medium">
                                        {{ number_format($file->getSize() / 1024, 2) }} KB
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Session Messages -->
                    @if (session()->has('message'))
                        <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-2xl">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-check-circle text-green-500 text-lg"></i>
                                <span class="text-green-700 dark:text-green-300 text-sm font-medium">{{ session('message') }}</span>
                            </div>
                        </div>
                    @endif

                    @if (session()->has('warning'))
                        <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-2xl">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-exclamation-triangle text-yellow-500 text-lg"></i>
                                <span class="text-yellow-700 dark:text-yellow-300 text-sm font-medium">{{ session('warning') }}</span>
                            </div>
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-2xl">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-times-circle text-red-500 text-lg"></i>
                                <span class="text-red-700 dark:text-red-300 text-sm font-medium">{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Import Statistics -->
                    @if (!empty($importStats))
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-2xl p-6">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 text-center">Import Summary</h3>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center mx-auto mb-2">
                                        <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $importStats['total'] }}</span>
                                    </div>
                                    <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Total</p>
                                </div>
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-2xl flex items-center justify-center mx-auto mb-2">
                                        <span class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $importStats['success'] }}</span>
                                    </div>
                                    <p class="text-sm font-medium text-green-600 dark:text-green-400">Success</p>
                                </div>
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-2xl flex items-center justify-center mx-auto mb-2">
                                        <span class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $importStats['errors'] }}</span>
                                    </div>
                                    <p class="text-sm font-medium text-red-600 dark:text-red-400">Errors</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Import Errors -->
                    @if (!empty($importErrors))
                        <div class="border border-red-200 dark:border-red-800 rounded-2xl overflow-hidden">
                            <div class="bg-red-50 dark:bg-red-900/20 px-6 py-4 border-b border-red-200 dark:border-red-800">
                                <h3 class="text-lg font-semibold text-red-700 dark:text-red-300 flex items-center gap-2">
                                    <i class="fas fa-exclamation-circle"></i>
                                    Import Errors ({{ count($importErrors) }})
                                </h3>
                            </div>
                            <div class="max-h-48 overflow-y-auto">
                                <table class="w-full">
                                    <thead class="bg-red-100 dark:bg-red-900/30">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-red-700 dark:text-red-300 uppercase tracking-wider">Row</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-red-700 dark:text-red-300 uppercase tracking-wider">Employee ID</th>
                                            <th class="px-6 py-3 text-left text-xs font-semibold text-red-700 dark:text-red-300 uppercase tracking-wider">Error</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-red-200 dark:divide-red-800">
                                        @foreach ($importErrors as $error)
                                            <tr class="hover:bg-red-50 dark:hover:bg-red-900/10 transition-colors">
                                                <td class="px-6 py-3 text-sm text-gray-900 dark:text-white font-medium">{{ $error['row'] }}</td>
                                                <td class="px-6 py-3 text-sm text-gray-900 dark:text-white">{{ $error['employee_id'] }}</td>
                                                <td class="px-6 py-3 text-sm text-red-600 dark:text-red-400">{{ $error['message'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Modal Footer -->
                <div class="px-8 py-6 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-200 dark:border-gray-700 rounded-b-3xl">
                    <div class="flex justify-end gap-3">
                        <button type="button" wire:click="closeImportModal"
                            class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-xl transition-all duration-200 transform hover:scale-105 disabled:opacity-50"
                            wire:loading.attr="disabled">
                            {{ empty($importErrors) ? 'Cancel' : 'Close' }}
                        </button>

                        <button type="button" wire:click="import" @if (!$file) disabled @endif
                            class="px-8 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-xl transition-all duration-200 transform hover:scale-105 disabled:opacity-50 flex items-center gap-2 min-w-32 justify-center relative"
                            wire:loading.attr="disabled">

                            <span class="flex items-center gap-2 transition-opacity duration-200" wire:loading.class="opacity-0" wire:loading.remove.class="opacity-100">
                                <i class="fas fa-file-import"></i>
                                Import
                            </span>

                            <span class="flex items-center gap-2 absolute inset-0 justify-center transition-opacity duration-200 opacity-0" wire:loading.class="opacity-100">
                                <i class="fas fa-spinner fa-spin"></i>
                                Importing...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <style>
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        .animate-scale-in {
            animation: scaleIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        input[type="file"]::file-selector-button {
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            color: white;
            border: none;
            border-radius: 0.75rem;
            padding: 0.5rem 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        input[type="file"]::file-selector-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
    </style>
</div>
