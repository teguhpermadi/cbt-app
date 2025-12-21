@props(['answer', 'isKey' => false])

<div class="flex items-baseline gap-1">
    <span @class(['font-black text-2xl', 'text-zinc-900 dark:text-white'=> !$isKey, 'text-success' => $isKey])>
        {{ $answer['answer'] ?? '-' }}
    </span>
    @if(isset($answer['unit']))
    <span class="text-sm font-bold text-zinc-500">{{ $answer['unit'] }}</span>
    @endif
    @if($isKey && isset($answer['tolerance']) && $answer['tolerance'] > 0)
    <span class="text-[10px] text-zinc-400 ml-2 uppercase tracking-wide">±{{ $answer['tolerance'] }}</span>
    @endif
</div>