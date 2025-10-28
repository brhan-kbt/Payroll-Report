<div class="space-y-3">
    <label class="flex items-center text-gray-700 dark:text-gray-300 font-semibold">
        <i class="fas fa-mobile-alt text-blue-500 mr-2"></i>
        Phone Numbers
    </label>
    <textarea wire:model="manualNumbers"
              placeholder="Enter phone numbers separated by commas&#10;Example: +251911223344, +251922334455"
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
