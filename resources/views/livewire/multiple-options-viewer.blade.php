<div class="space-y-3">
    @foreach($options as $key => $option)
        <div 
            class="p-2 rounded-lg border transition-all duration-200 {{ 
                $showCorrectAnswers && $this->isOptionCorrect($key) 
                    ? 'bg-green-100 border-green-300' 
                    : 'bg-gray-50 border-gray-200 hover:bg-gray-100' 
            }}"
        >
            <div class="flex items-start space-x-3">
                @if($questionType->value === 'multiple_choice')
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
                @elseif($questionType->value === 'true_false')
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
                @elseif($questionType->value === 'multiple_selection')
                    <div class="flex-shrink-0 mt-1">
                        <div class="w-5 h-5 rounded border-2 {{ 
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
                @endif
                
                <div class="flex-1">
                    <div class="text-gray-800 font-medium">
                        {{ $this->getOptionLabel($key) }}
                    </div>
                    @if($this->getOptionText($key))
                        <div class="text-gray-600 mt-1">
                            {{ $this->getOptionText($key) }}
                        </div>
                    @endif
                    
                    @if($this->getOptionMedia($key))
                        <div class="mt-2">
                            @if($mediaUrl = $this->getOptionMediaUrl($key))
                                <img src="{{ $mediaUrl }}" alt="Option {{ $key }}" class="max-w-full h-auto rounded-lg border border-gray-200" style="max-height: 200px;">
                            @else
                                <div class="text-xs text-gray-500 italic">
                                    Media ID: {{ $this->getOptionMedia($key) }} (URL tidak tersedia)
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
                
                @if($showCorrectAnswers && $this->isOptionCorrect($key))
                    <div class="flex-shrink-0">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Benar
                        </span>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>
