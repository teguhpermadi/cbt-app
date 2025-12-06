<div>
    <x-mary-card shadow>
        <!-- header -->
        <div class="flex items-center justify-between">
            {{ $question->id }}
            <x-mary-badge :value="$question->question_type->getLabel()" />
            <livewire:question.selector.difficulty-selector-component :question="$question" wire:key="difficulty_{$question->id}" />
            <livewire:question.selector.timer-selector-component :question="$question" wire:key="timer_{$question->id}" />
            <livewire:question.selector.score-selector-component :question="$question" wire:key="score_{$question->id}" />
            <x-mary-button label="Edit" icon="o-pencil" tooltip="Edit question" wire:click="editQuestion" wire:key="edit_{$question->id}" />
            <x-mary-button label="Delete" icon="o-trash" tooltip="Delete question" wire:click="deleteQuestion" wire:key="delete_{$question->id}" />
        </div>

        <!-- body -->
        <div class="p-4">
            <p>{{ $question->content }}</p>
        </div>

        <!-- footer -->
        <div class="w-full">
            @switch($question->question_type->value)
            @case('multiple_choice')
            <livewire:question.option.multiple-viewer-component :question="$question" wire:key="multiple_choice_{$question->id}" />
            @break
            @case('multiple_selection')
            <livewire:question.option.multiple-viewer-component :question="$question" wire:key="multiple_selection_{$question->id}" />
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
            @case('numerical_input')
            <livewire:question.option.numerical-viewer-component :question="$question" wire:key="numerical_{$question->id}" />
            @break
            @default
            {!! $question->options !!}
            @endswitch
        </div>
    </x-mary-card>
</div>