@props(['answer', 'isKey' => false])

<div class="flex flex-wrap gap-2">
    @foreach($answer['answers'] ?? [] as $ans)
    <span @class([ 'px-3 py-1.5 rounded-lg font-bold border shadow-sm' , 'bg-primary/10 text-primary border-primary/20'=> !$isKey,
        'bg-success text-success-fg font-black' => $isKey,
        ])>
        {{ $ans }}
    </span>
    @endforeach
</div>