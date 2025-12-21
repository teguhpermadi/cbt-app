@props(['answer', 'isKey' => false])

<div class="flex flex-wrap gap-2">
    @foreach($answer['order'] ?? [] as $item)
    <span @class([ 'px-3 py-1.5 rounded-lg font-bold border shadow-sm' , 'bg-white dark:bg-zinc-900 border-zinc-100 dark:border-zinc-800 text-zinc-700 dark:text-zinc-300'=> !$isKey,
        'bg-success/10 text-success border-success/20 font-black' => $isKey,
        ])>
        {{ $item }}
    </span>
    @endforeach
</div>