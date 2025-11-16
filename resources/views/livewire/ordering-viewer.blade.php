<div class="ordering-viewer">
    <!-- Header -->
    <div class="mb-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
            Kunci Jawaban Urutan
        </h3>
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Berikut adalah urutan yang benar dari item-item di atas:
        </p>
    </div>
    
    <!-- Correct Order Display -->
    <div class="space-y-3">
        @if($showCorrectAnswers && !empty($correctOrder))
            @php
            $sortedOptions = $getSortedOptions();
            @endphp
            
            @foreach($sortedOptions as $optionKey => $optionData)
                @php
                $orderNumber = $loop->index + 1;
                $isCorrect = true; // In correct order display, all are correct
                @endphp
                
                <div class="flex items-start space-x-3">
                    <!-- Order Number Badge -->
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                            <span class="text-green-600 dark:text-green-400 font-bold text-sm">
                                {{ $orderNumber }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Option Content -->
                    <div class="flex-grow">
                        <div class="bg-green-50 dark:bg-green-900/20 border border-green-300 dark:border-green-600 rounded-lg p-3 shadow-sm">
                            <div class="flex items-center space-x-3">
                                <!-- Option Key Badge -->
                                <div class="flex-shrink-0">
                                    <div class="w-6 h-6 bg-green-100 dark:bg-green-900 rounded flex items-center justify-center">
                                        <span class="text-green-600 dark:text-green-400 font-bold text-xs">
                                            {{ $optionKey }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Option Text and Media -->
                                <div class="flex-grow">
                                    @if($mediaUrl = $getOptionMediaUrl($optionKey))
                                        <img src="{{ $mediaUrl }}" alt="Option {{ $optionKey }}" class="h-16 w-16 object-cover rounded mb-2">
                                    @endif
                                    <p class="text-sm text-gray-700 dark:text-gray-300">
                                        {{ $getOptionText($optionKey) }}
                                    </p>
                                </div>
                                
                                <!-- Checkmark for correct order -->
                                <div class="flex-shrink-0">
                                    <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <!-- No correct answers available -->
            <div class="text-center py-8">
                <div class="text-gray-400 dark:text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p class="text-sm">Kunci jawaban tidak tersedia</p>
                </div>
            </div>
        @endif
    </div>
    
    <!-- Summary -->
    @if($showCorrectAnswers && !empty($correctOrder))
        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
            <div class="flex items-center space-x-2">
                <div class="flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-grow">
                    <p class="text-sm text-blue-800 dark:text-blue-200">
                        <strong>Urutan yang benar:</strong> {{ implode(' → ', array_keys($sortedOptions)) }}
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
.ordering-viewer {
    position: relative;
}

.ordering-viewer .bg-green-50 {
    background-color: #f0fdf4 !important;
}

.ordering-viewer .dark .bg-green-900\/20 {
    background-color: rgba(21, 128, 61, 0.2) !important;
}

.ordering-viewer .border-green-300 {
    border-color: #86efac !important;
}

.ordering-viewer .dark .border-green-600 {
    border-color: #16a34a !important;
}

.ordering-viewer .bg-blue-50 {
    background-color: #eff6ff !important;
}

.ordering-viewer .dark .bg-blue-900\/20 {
    background-color: rgba(37, 99, 235, 0.2) !important;
}

.ordering-viewer .border-blue-200 {
    border-color: #bfdbfe !important;
}

.ordering-viewer .dark .border-blue-800 {
    border-color: #1e40af !important;
}
</style>
@endpush
