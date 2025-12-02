<div class="grid gap-3 w-full">
    @foreach($parsedOptions as $key => $option)
    @php
    $isCorrect = in_array($key, $correctKeys ?? []);
    $baseClass = "flex items-start gap-3 p-3 border rounded-lg transition-colors";
    $activeClass = $isCorrect ? "bg-green-50 border-green-500" : "hover:bg-base-200";
    @endphp
    <div class="{{ $baseClass }} {{ $activeClass }}">
        <div class="text-primary">
            {{ is_int($key) ? chr(65 + $key) : $key }}.
        </div>
        <div class="flex-1 prose">
            {!! $option['text'] ?? $option['label'] ?? $option['value'] ?? $option !!}

            @if(!empty($option['media_id']))
            <div class="mt-2">
                {{-- Placeholder for media rendering if needed --}}
                <span class="text-xs text-gray-500">[Media ID: {{ $option['media_id'] }}]</span>
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>