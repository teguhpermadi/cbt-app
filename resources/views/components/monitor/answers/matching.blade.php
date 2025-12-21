@props(['answer', 'isKey' => false])

<ul class="w-full space-y-2">
    @foreach($answer['pairs'] ?? [] as $left => $right)
    <li @class([ 'flex items-center justify-between p-2 rounded-lg border text-xs' , 'bg-white dark:bg-zinc-900 border-zinc-100 dark:border-zinc-800'=> !$isKey,
        'bg-white/50 dark:bg-zinc-900/50 border-success/20 text-zinc-900 dark:text-zinc-100' => $isKey,
        ])>
        <span class="font-medium text-zinc-500 shrink-0">{{ $left }}</span>
        <x-heroicon-m-arrow-long-right @class(['w-4 h-4 mx-2', 'text-zinc-300'=> !$isKey, 'text-success/40' => $isKey]) />
            <span @class(['font-bold truncate text-right', 'text-primary'=> !$isKey, 'text-success font-black' => $isKey])>{{ $right ?? '-' }}</span>
    </li>
    @endforeach
</ul>