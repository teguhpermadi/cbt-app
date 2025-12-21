@props(['detail', 'index'])

@php
$question = $detail->examQuestion;
$isCorrect = $detail->is_correct;
$borderClass = match($isCorrect) {
true => 'border-success/30 ring-success/5',
false => 'border-danger/30 ring-danger/5',
default => 'border-zinc-200 dark:border-zinc-700'
};
@endphp

<div id="question-{{ $index + 1 }}"
    class="relative p-6 rounded-2xl shadow-sm border-2 bg-white dark:bg-zinc-900 scroll-mt-24 transition-all hover:shadow-md {{ $borderClass }}">

    {{-- Question Status Side Indicator --}}
    <div class="absolute -left-3 top-6 flex items-center">
        <div class="h-10 w-1.5 rounded-full {{ $isCorrect === true ? 'bg-success' : ($isCorrect === false ? 'bg-danger' : 'bg-zinc-300 dark:bg-zinc-600') }}"></div>
    </div>

    <div class="mb-6 flex justify-between items-center bg-zinc-50 dark:bg-zinc-800/50 -mx-6 -mt-6 p-4 rounded-t-2xl border-b border-inherit">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-zinc-200 dark:bg-zinc-800 flex items-center justify-center font-black text-zinc-900 dark:text-white shadow-inner">
                {{ $index + 1 }}
            </div>
            <div>
                <span class="text-xs font-bold text-zinc-500 uppercase tracking-widest block">Tipe Soal</span>
                <span class="text-sm font-semibold text-zinc-900 dark:text-white">
                    {{ $question->question_type->getLabel() }}
                </span>
            </div>
        </div>

        <div class="text-right">
            <span class="text-xs font-bold text-zinc-500 uppercase tracking-widest block mb-1">Status & Poin</span>
            <div class="flex items-center gap-2">
                <span class="text-[10px] font-black px-3 py-1.5 rounded-full shadow-sm
                        {{ $isCorrect ? 'bg-success text-success-fg' : ($isCorrect === false ? 'bg-danger text-danger-fg' : 'bg-zinc-500 text-white') }}">
                    {{ $isCorrect ? 'BENAR' : ($isCorrect === false ? 'SALAH' : 'PENDING') }}
                </span>
                <span class="text-sm font-bold text-zinc-700 dark:text-zinc-300">
                    {{ $detail->score_earned }} / {{ $question->score_value }}
                </span>
            </div>
        </div>
    </div>

    <div class="prose dark:prose-invert max-w-none text-zinc-800 dark:text-zinc-200 text-base leading-relaxed mb-8" wire:ignore>
        {!! $question->content !!}
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8 pt-8 border-t-2 border-zinc-50 dark:border-zinc-800/50">
        {{-- Jawaban Siswa --}}
        <div class="group">
            <div class="font-bold text-zinc-500 dark:text-zinc-400 mb-3 flex items-center gap-2 text-xs uppercase tracking-widest">
                <div class="p-1.5 bg-zinc-100 dark:bg-zinc-800 rounded-lg group-hover:bg-primary/10 transition-colors">
                    <x-heroicon-m-user class="w-4 h-4 text-zinc-600 dark:text-zinc-400 group-hover:text-primary" />
                </div>
                Jawaban Siswa
            </div>

            <x-monitor.answer-viewer
                :answer="$detail->student_answer"
                :type="$question->question_type" />
        </div>

        {{-- Kunci Jawaban --}}
        <div class="group">
            <div class="font-bold text-success/80 mb-3 flex items-center gap-2 text-xs uppercase tracking-widest">
                <div class="p-1.5 bg-success/10 rounded-lg group-hover:bg-success/20 transition-colors">
                    <x-heroicon-m-check-badge class="w-4 h-4 text-success" />
                </div>
                Kunci Jawaban
            </div>

            <x-monitor.answer-viewer
                :answer="$question->key_answer"
                :type="$question->question_type"
                is-key />
        </div>
    </div>

    @if($detail->correction_notes)
    <div class="mt-6 p-4 bg-warning/5 border-2 border-warning/10 rounded-xl">
        <div class="font-bold text-warning/80 mb-2 flex items-center gap-2 text-xs uppercase tracking-widest">
            <x-heroicon-m-chat-bubble-left-right class="w-4 h-4" />
            Catatan Koreksi
        </div>
        <div class="text-zinc-700 dark:text-zinc-300 text-sm leading-relaxed">
            {{ $detail->correction_notes }}
        </div>
    </div>
    @endif
</div>