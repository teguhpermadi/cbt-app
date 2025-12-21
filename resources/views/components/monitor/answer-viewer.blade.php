@props(['answer', 'type', 'isKey' => false])

<div @class([ 'p-4 rounded-xl border-2 min-h-[60px] flex items-center' , 'bg-zinc-50 dark:bg-zinc-950/50 border-zinc-100 dark:border-zinc-800'=> !$isKey,
    'bg-success/5 dark:bg-success/10 border-success/10' => $isKey,
    ])>
    @if(is_array($answer))
    @if(isset($answer['pairs']))
    <x-monitor.answers.matching :answer="$answer" :is-key="$isKey" />
    @elseif(isset($answer['order']))
    <x-monitor.answers.ordering :answer="$answer" :is-key="$isKey" />
    @elseif(isset($answer['answers']))
    <x-monitor.answers.multiple-selection :answer="$answer" :is-key="$isKey" />
    @elseif(isset($answer['answer']) && $type->value === 'numerical_input')
    <x-monitor.answers.numerical :answer="$answer" :is-key="$isKey" />
    @elseif(isset($answer['answer']))
    <x-monitor.answers.simple :answer="$answer" :is-key="$isKey" />
    @else
    <x-monitor.answers.fallback :answer="$answer" :is-key="$isKey" />
    @endif
    @else
    @if($type->value === 'essay')
    <x-monitor.answers.essay :answer="$answer" :is-key="$isKey" />
    @else
    <x-monitor.answers.simple :answer="$answer" :is-key="$isKey" />
    @endif
    @endif
</div>