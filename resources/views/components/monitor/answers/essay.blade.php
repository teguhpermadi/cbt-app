@props(['answer', 'isKey' => false])

@if($isKey)
<div class="text-xs text-zinc-600 dark:text-zinc-500 italic font-medium">
    @if(isset($answer['rubric']))
    <span class="flex items-center gap-1.5">
        <span class="w-1.5 h-1.5 rounded-full bg-success"></span>
        Rubrik penilaian tersedia.
    </span>
    @else
    <span class="flex items-center gap-1.5">
        <span class="w-1.5 h-1.5 rounded-full bg-warning"></span>
        Perlu koreksi manual.
    </span>
    @endif
</div>
@else
<div class="text-zinc-900 dark:text-zinc-100">
    {{ $answer ?? '(Tidak menjawab)' }}
</div>
@endif