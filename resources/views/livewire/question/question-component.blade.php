<div>
    <x-mary-card shadow>
        <!-- header -->
        <div class="flex items-center justify-between">
            <p>{{ $question->question_type }}</p>
            <p>{{ $question->id }}</p>
        </div>

        <!-- body -->
        <div class="p-4">
            <p>{{ $question->content }}</p>
        </div>

        <!-- footer -->
        <div class="w-full">
            @switch($question->question_type->value)
            @case('multiple_choice')
            <livewire:question.option.multiple-viewer-component :question="$question" wire:key="multiple_choice_{$question->id}"/>
            @break
            @case('multiple_selection')
            <livewire:question.option.multiple-viewer-component :question="$question" wire:key="multiple_selection_{$question->id}"/>
            @break
            @case('true_false')
            <livewire:question.option.multiple-viewer-component :question="$question" wire:key="true_false_{$question->id}" />
            @break
            @case('matching')
            <livewire:question.option.matching-viewer-component :question="$question" wire:key="matching_{$question->id}" />
            @break
            @case('ordering')
            <livewire:question.option.ordering-viewer-component :question="$question" wire:key="ordering_{$question->id}" />
            @break
            @default
            {!! $question->options !!}
            @endswitch
        </div>
    </x-mary-card>
</div>