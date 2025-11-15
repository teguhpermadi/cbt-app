<x-filament-panels::page>
    @foreach ($questions as $question)
        <div>
            {{ $question->content }}
        </div>
    @endforeach
</x-filament-panels::page>
