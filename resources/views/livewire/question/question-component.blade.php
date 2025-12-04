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
            <livewire:question.option.multiple-viewer-component :question="$question" />
            @break
            @case('multiple_selection')
            <livewire:question.option.multiple-viewer-component :question="$question" />
            @break
            @case('true_false')
            <livewire:question.option.multiple-viewer-component :question="$question" />
            @break
            @case('matching')
            <livewire:question.option.matching-viewer-component :question="$question" />
            @break
            @default
            {!! $question->options !!}
            @endswitch
        </div>
    </x-mary-card>
</div>