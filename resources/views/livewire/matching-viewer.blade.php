<div class="matching-viewer" x-data="matchingViewer({{ json_encode($correctPairs) }}, {{ $showCorrectAnswers ? 'true' : 'false' }})">
    <!-- Container for matching items -->
    <div class="space-y-4">
        @foreach($leftColumn as $leftKey => $leftOption)
            <div class="flex items-center space-x-4 relative" x-data="{ leftKey: '{{ $leftKey }}' }">
                <!-- Left Card -->
                <div class="flex-1 max-w-xs">
                    <div class="bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg p-3 shadow-sm">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-6 h-6 bg-blue-100 dark:bg-blue-900 rounded flex items-center justify-center">
                                    <span class="text-blue-600 dark:text-blue-400 font-bold text-xs">
                                        {{ $leftKey }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow">
                                @if($mediaUrl = $this->getOptionMediaUrl($leftKey))
                                    <img src="{{ $mediaUrl }}" alt="Option {{ $leftKey }}" class="h-12 w-12 object-cover rounded mb-1">
                                @endif
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    {{ $this->getOptionText($leftKey) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Connection Line -->
                <div class="flex-shrink-0 flex items-center justify-center w-16">
                    @if($showCorrectAnswers && isset($correctPairs[$leftKey]))
                        <div class="relative">
                            <!-- Line -->
                            <div class="w-12 h-0.5 bg-green-500"></div>
                            <!-- Arrow -->
                            <div class="absolute right-0 top-1/2 transform translate-x-1/2 -translate-y-1/2 w-0 h-0 border-t-4 border-t-transparent border-b-4 border-b-transparent border-l-4 border-l-green-500"></div>
                        </div>
                    @else
                        <div class="w-12 h-0.5 bg-gray-300"></div>
                    @endif
                </div>

                <!-- Right Card (Correct Answer) -->
                <div class="flex-1 max-w-xs">
                    @if($showCorrectAnswers && isset($correctPairs[$leftKey]))
                        @php
                        $rightKey = $correctPairs[$leftKey];
                        @endphp
                        <div class="bg-green-50 dark:bg-green-900/20 border border-green-300 dark:border-green-600 rounded-lg p-3 shadow-sm">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-6 h-6 bg-green-100 dark:bg-green-900 rounded flex items-center justify-center">
                                        <span class="text-green-600 dark:text-green-400 font-bold text-xs">
                                            {{ $rightKey }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow">
                                    @if($mediaUrl = $this->getOptionMediaUrl($rightKey))
                                        <img src="{{ $mediaUrl }}" alt="Option {{ $rightKey }}" class="h-12 w-12 object-cover rounded mb-1">
                                    @endif
                                    <p class="text-sm text-gray-700 dark:text-gray-300">
                                        {{ $this->getOptionText($rightKey) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Empty placeholder for non-matching items -->
                        <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-3">
                            <div class="text-center text-gray-400 dark:text-gray-500 text-sm">
                                -
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

@push('styles')
<style>
.matching-viewer {
    position: relative;
}

/* Simple styling for the new layout */
.matching-viewer .border-green-300 {
    border-color: #86efac !important;
}

.matching-viewer .dark .border-green-600 {
    border-color: #16a34a !important;
}

.matching-viewer .bg-green-50 {
    background-color: #f0fdf4 !important;
}

.matching-viewer .dark .bg-green-900\/20 {
    background-color: rgba(21, 128, 61, 0.2) !important;
}
</style>
@endpush
