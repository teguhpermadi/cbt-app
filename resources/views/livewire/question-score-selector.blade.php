<div>
    <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-3">
        <div class="flex items-center space-x-2">
            <div class="relative">
                <select 
                    id="score-selector"
                    wire:model.live="selectedScore"
                    {{ $isLoading ? 'disabled' : '' }}
                    class="block w-full max-w-xs rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm focus:border-blue-500 focus:ring-blue-500 dark:focus:border-blue-400 dark:focus:ring-blue-400 transition-colors {{ $isLoading ? 'opacity-50 cursor-not-allowed' : '' }} {{ 
                        $selectedScore && !$isLoading 
                            ? ($selectedScore->getColor() === 'green' 
                                ? 'border-green-500 dark:border-green-400' 
                                : ($selectedScore->getColor() === 'blue' 
                                    ? 'border-blue-500 dark:border-blue-400'
                                    : ($selectedScore->getColor() === 'yellow'
                                        ? 'border-yellow-500 dark:border-yellow-400'
                                        : ($selectedScore->getColor() === 'orange'
                                            ? 'border-orange-500 dark:border-orange-400'
                                            : 'border-red-500 dark:border-red-400'
                                        )
                                    )
                                )
                            )
                            : ''
                    }}"
                >
                    <option value="">Pilih Score</option>
                    @foreach($this->getScoreOptions() as $value => $label)
                        <option value="{{ $value }}" {{ $selectedScore?->value == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>                
            </div>
        </div>
    </div>
</div>
