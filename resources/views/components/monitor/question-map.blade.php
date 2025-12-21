@props(['record'])

<div class="bg-white dark:bg-zinc-900/50 rounded-xl p-4 border border-zinc-200 dark:border-zinc-700 shadow-sm sticky top-24">
    <h3 class="font-bold text-zinc-900 dark:text-white flex items-center gap-2 mb-4 text-sm">
        <x-heroicon-m-squares-2x2 class="w-4 h-4 text-primary" />
        Peta Soal
    </h3>

    <div class="grid grid-cols-5 gap-2">
        @foreach($record->details as $index => $detail)
        @php
        $isCorrect = $detail->is_correct;
        $colorClass = match($isCorrect) {
        true => 'bg-success text-success-fg hover:opacity-90',
        false => 'bg-danger text-danger-fg hover:opacity-90',
        default => 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200 dark:hover:bg-zinc-700'
        };
        @endphp
        <a href="#question-{{ $index + 1 }}"
            class="aspect-square flex items-center justify-center rounded-lg text-xs font-bold transition-all shadow-sm hover:scale-110 active:scale-95 border border-transparent {{ $colorClass }}">
            {{ $index + 1 }}
        </a>
        @endforeach
    </div>

    <div class="mt-6 pt-4 border-t border-zinc-100 dark:border-zinc-800 space-y-2">
        <div class="flex items-center justify-between text-[10px] font-medium uppercase tracking-tighter">
            <div class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-success"></span>
                <span class="text-zinc-500">Benar</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-danger"></span>
                <span class="text-zinc-500">Salah</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-zinc-300 dark:bg-zinc-700"></span>
                <span class="text-zinc-500">Pending</span>
            </div>
        </div>
    </div>
</div>