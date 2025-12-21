@props(['answer', 'isKey' => false])

<span @class(['font-black text-base', 'text-zinc-900 dark:text-white'=> !$isKey, 'text-success' => $isKey])>
    {{ is_array($answer) ? ($answer['answer'] ?? '-') : ($answer ?? '-') }}
</span>