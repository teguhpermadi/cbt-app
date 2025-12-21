@props(['detail', 'index'])

@php
$question = $detail->examQuestion;
$isCorrect = $detail->is_correct;

$borderClass = match($isCorrect) {
true => 'border-success/30 ring-success/5',
false => 'border-danger/30 ring-danger/5',
default => 'border-zinc-200 dark:border-zinc-700'
};

$headerClass = match($isCorrect) {
true => 'bg-success/10 dark:bg-success/20 border-success/20',
false => 'bg-danger/10 dark:bg-danger/20 border-danger/20',
default => 'bg-zinc-50 dark:bg-zinc-800/50 border-zinc-200 dark:border-zinc-700'
};
@endphp

<div id="question-{{ $index + 1 }}"
    class="relative p-6 rounded-2xl shadow-sm border-2 bg-white dark:bg-zinc-900 scroll-mt-24 transition-all hover:shadow-md {{ $borderClass }}">

    <div class="mb-6 flex justify-between items-center -mx-6 -mt-6 p-4 rounded-t-2xl border-b {{ $headerClass }}">
        <div class="flex items-center gap-3">
            <div @class([ 'w-10 h-10 rounded-xl flex items-center justify-center font-black shadow-inner active:scale-95 transition-transform' , 'bg-success/20 text-success'=> $isCorrect === true,
                'bg-danger/20 text-danger' => $isCorrect === false,
                'bg-zinc-200 dark:bg-zinc-800 text-zinc-900 dark:text-white' => $isCorrect === null,
                ])>
                {{ $index + 1 }}
            </div>
            <div>
                <span @class([ 'text-xs font-bold uppercase tracking-widest block' , 'text-success/70'=> $isCorrect === true,
                    'text-danger/70' => $isCorrect === false,
                    'text-zinc-500' => $isCorrect === null,
                    ])>Tipe Soal</span>
                <span @class([ 'font-semibold' , 'text-success-content dark:text-success'=> $isCorrect === true,
                    'text-danger-content dark:text-danger' => $isCorrect === false,
                    'text-zinc-900 dark:text-white' => $isCorrect === null,
                    ])>
                    {{ $question->question_type->getLabel() }}
                </span>
            </div>
        </div>

        <div class="text-right">
            <span @class([ 'text-xs font-bold uppercase tracking-widest block mb-1' , 'text-success/70'=> $isCorrect === true,
                'text-danger/70' => $isCorrect === false,
                'text-zinc-500' => $isCorrect === null,
                ])>Status & Poin</span>
            <div class="flex items-center gap-2">
                <span @class([ 'text-xs font-black px-3 py-1.5 rounded-full shadow-sm' , 'bg-success text-success-fg'=> $isCorrect === true,
                    'bg-danger text-danger-fg' => $isCorrect === false,
                    'bg-zinc-500 text-white' => $isCorrect === null,
                    ])>
                    {{ $isCorrect ? 'BENAR' : ($isCorrect === false ? 'SALAH' : 'PENDING') }}
                </span>
                <span @class([ 'font-bold text-base' , 'text-success'=> $isCorrect === true,
                    'text-danger' => $isCorrect === false,
                    'text-zinc-700 dark:text-zinc-300' => $isCorrect === null,
                    ])>
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
        <div class="text-zinc-700 dark:text-zinc-300 leading-relaxed">
            {{ $detail->correction_notes }}
        </div>
    </div>
    @endif
</div>