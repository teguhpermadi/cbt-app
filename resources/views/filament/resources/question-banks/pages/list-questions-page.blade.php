<x-filament-panels::page>
    @foreach ($questions as $question)
        <div>
            <livewire:question-detail-viewer :question="$question" />
        </div>
    @endforeach
</x-filament-panels::page>
