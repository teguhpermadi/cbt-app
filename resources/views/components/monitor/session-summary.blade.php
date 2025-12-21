@props(['record'])

<div class="xl:col-span-3 grid grid-cols-1 md:grid-cols-4 gap-4 text-base bg-zinc-50 dark:bg-zinc-800/40 p-5 rounded-xl border border-zinc-200 dark:border-zinc-700 shadow-sm transition-colors">
    <div>
        <span class="text-zinc-500 dark:text-zinc-400 text-xs uppercase tracking-wider font-semibold">Siswa</span>
        <div class="font-bold text-zinc-900 dark:text-white text-xl mt-1">{{ $record->user->name ?? '-' }}</div>
    </div>
    <div>
        <span class="text-zinc-500 dark:text-zinc-400 text-xs uppercase tracking-wider font-semibold">Nilai Total</span>
        <div class="font-bold text-primary text-xl mt-1">{{ $record->total_score }}</div>
    </div>
    <div>
        <span class="text-zinc-500 dark:text-zinc-400 text-xs uppercase tracking-wider font-semibold">Waktu Mulai</span>
        <div class="font-bold text-zinc-900 dark:text-white mt-1">{{ $record->start_time?->format('d M Y H:i') ?? '-' }}</div>
    </div>
    <div>
        <span class="text-zinc-500 dark:text-zinc-400 text-xs uppercase tracking-wider font-semibold">Durasi</span>
        <div class="font-bold text-zinc-900 dark:text-white mt-1">{{ $record->duration_taken }} menit</div>
    </div>
</div>