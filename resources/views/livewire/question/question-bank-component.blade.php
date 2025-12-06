<div>
    <h1>{{ $questionBank->name }}</h1>
    <p>{{ $questionBank->description }}</p>
    @foreach ($questionBank->questions as $question)
    <livewire:question.question-component :question="$question" class="py-2" wire:key="question-{{ $question->id }}" />
    @endforeach
</div>