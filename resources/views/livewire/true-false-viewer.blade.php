<div class="space-y-3">
    @foreach($options as $key => $option)
        <div class="flex items-start space-x-3 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
            <div class="flex-shrink-0 mt-1">
                <div class="w-5 h-5 rounded-full border-2 {{ 
                    $showCorrectAnswers && $this->isOptionCorrect($key) 
                        ? 'border-green-500 bg-green-500' 
                        : 'border-gray-400' 
                }}">
                    @if($showCorrectAnswers && $this->isOptionCorrect($key))
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="flex-grow">
                <div class="flex items-center space-x-2">
                    <span class="font-semibold text-gray-700 dark:text-gray-300">
                        {{ $this->getOptionLabel($key) }}.
                    </span>
                    @if($mediaUrl = $this->getOptionMediaUrl($key))
                        <img src="{{ $mediaUrl }}" alt="Option {{ $key }}" class="h-16 w-16 object-cover rounded">
                    @endif
                    <span class="text-gray-700 dark:text-gray-300">
                        {{ $this->getOptionText($key) }}
                    </span>
                </div>
            </div>
        </div>
    @endforeach
</div>
