@props(['answer', 'isKey' => false])

@if(is_array($answer))
<ul class="list-none w-full space-y-1">
    @foreach($answer as $ans)
    <li class="py-1 flex items-center gap-2">
        <span @class(['w-1.5 h-1.5 rounded-full', 'bg-primary/40'=> !$isKey, 'bg-success/40' => $isKey])></span>
        <span>{{ $ans }}</span>
    </li>
    @endforeach
</ul>
@else
<div @class(['text-zinc-900 dark:text-zinc-100'=> !$isKey, 'text-success' => $isKey])>
    {{ $answer ?? ($isKey ? 'Tidak tersedia' : '(Tidak menjawab)') }}
</div>
@endif