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
        <div class="p-4" wire:ignore>
            <p>{{ $question->content }}</p>
        </div>

        <!-- footer -->
        <div class="w-full">
            @switch($question->question_type->value)
            @case('multiple_choice')
            @case('multiple_selection')
            @case('true_false')
            <x-option-multiple-viewer :options="$question->options" />
            @break
            @case('matching')
            <x-option-matching-viewer :options="$question->options" />
            @break
            @case('ordering')
            <x-option-ordering-viewer :options="$question->options" />
            @break
            @case('numerical_input')
            <x-option-numerical-viewer :options="$question->options" />
            @break
            @case('essay')
            <x-option-essay-viewer :options="$question->options" />
            @break
            @default
            {!! $question->options !!}
            @endswitch
        </div>
    </x-mary-card>
</div>