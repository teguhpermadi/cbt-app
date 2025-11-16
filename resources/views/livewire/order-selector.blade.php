<div>
    <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-3">
        <div class="flex items-center space-x-2">
            <span class="text-xs font-medium text-gray-600 dark:text-gray-300">
                Urutan
            </span>
            <div class="relative">
                <input
                    id="order-input"
                    type="number"
                    min="1"
                    max="{{ $maxOrder }}"
                    wire:model.lazy="order"
                    {{ $isLoading ? 'disabled' : '' }}
                    class="block w-20 rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:border-blue-500 focus:ring-blue-500 dark:focus:border-blue-400 dark:focus:ring-blue-400 transition-colors {{ $isLoading ? 'opacity-50 cursor-not-allowed' : '' }}"
                />

                @if($isLoading)
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none bg-white/70 dark:bg-gray-800/70 rounded-lg">
                        <svg class="animate-spin h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                @endif
            </div>
            <span class="text-xs text-gray-500 dark:text-gray-400">
                / {{ $maxOrder }}
            </span>
        </div>
    </div>
</div>
