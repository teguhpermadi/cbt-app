@props(['options'])

<div class="grid gap-3 w-full">
    @foreach($options as $option)
    @php
    $isCorrect = $option->is_correct;
    $baseClass = "flex items-start gap-3 p-3 border rounded-lg transition-colors";
    $activeClass = $isCorrect ? "bg-green-50 border-green-500" : "hover:bg-base-200";
    @endphp
    <div class="{{ $baseClass }} {{ $activeClass }}">
        <div class="flex-1 prose">
            {!! $option->content !!}

            @if($option->hasOptionMedia())
            <div class="mt-2">
                <img src="{{ $option->getFirstMediaUrl('option_media') }}" alt="Option Media" class="max-w-xs rounded-lg shadow-sm">
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>