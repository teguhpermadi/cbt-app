<div id="question-{{ $question->id }}">
    <x-mary-card shadow>
        <!-- header -->
        <div class="flex items-center justify-between">
            <x-mary-badge :value="$question->question_type->getLabel()" />
            <livewire:question.selector.difficulty-selector-component
                :questionId="$question->id"
                :difficultyLevel="$question->difficulty_level"
                wire:key="difficulty_{{$question->id}}" />
            <livewire:question.selector.timer-selector-component
                :questionId="$question->id"
                :timer="$question->timer"
                wire:key="timer_{{$question->id}}" />
            <livewire:question.selector.score-selector-component
                :questionId="$question->id"
                :scoreValue="$question->score_value"
                wire:key="score_{{$question->id}}" />
            <livewire:question.selector.order-selector-component
                :questionId="$question->id"
                :order="$question->order"
                wire:key="order_{{$question->id}}" />
            <x-mary-button label="Edit" wire:click="editQuestion" wire:key="edit_{{$question->id}}" />
            <x-mary-button label="Delete" wire:click="deleteQuestion" wire:confirm="Are you sure you want to delete this question?" wire:key="delete_{{$question->id}}" />
        </div>

        <!-- body -->
        <div class="p-4">
            <p>{{ $question->content }}</p>
        </div>

        <!-- footer -->
        <div class="w-full">
            @switch($question->question_type->value)
            @case('multiple_choice')
            <livewire:question.option.multiple-viewer-component
                :options="$question->options"
                wire:key="multiple_choice_{{$question->id}}" />
            @break
            @case('multiple_selection')
            <livewire:question.option.multiple-viewer-component
                :options="$question->options"
                wire:key="multiple_selection_{{$question->id}}" />
            @break
            @case('true_false')
            <livewire:question.option.multiple-viewer-component
                :options="$question->options"
                wire:key="true_false_{{$question->id}}" />
            @break
            @case('matching')
            <livewire:question.option.matching-viewer-component
                :options="$question->options"
                wire:key="matching_{{$question->id}}" />
            @break
            @case('ordering')
            <livewire:question.option.ordering-viewer-component
                :options="$question->options"
                wire:key="ordering_{{$question->id}}" />
            @break
            @case('numerical_input')
            <livewire:question.option.numerical-viewer-component
                :options="$question->options"
                wire:key="numerical_{{$question->id}}" />
            @break
            @case('essay')
            <livewire:question.option.essay-viewer-component
                :options="$question->options"
                wire:key="essay_{{$question->id}}" />
            @break
            @default
            {!! $question->options !!}
            @endswitch
        </div>
    </x-mary-card>
</div>