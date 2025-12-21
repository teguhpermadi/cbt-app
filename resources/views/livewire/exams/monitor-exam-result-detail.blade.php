<div class="px-4 py-6 sm:px-6 lg:px-8">
    <x-mary-header title="Detail Pengerjaan Ujian" subtitle="Analisis jawaban siswa per soal" separator>
        <x-slot:actions>
            <x-mary-button label="Kembali ke Monitor" icon="o-arrow-left" link="{{ route('filament.admin.resources.exams.monitor', ['record' => $record->exam_id]) }}" />
        </x-slot:actions>
    </x-mary-header>

    <div class="space-y-6">
        {{-- Top Section: Info & Map --}}
        <div class="grid grid-cols-1 xl:grid-cols-4 gap-6 items-start">
            {{-- Header Data --}}
            <div class="xl:col-span-3 grid grid-cols-1 md:grid-cols-4 gap-4 text-sm bg-gray-50 dark:bg-zinc-800/40 p-5 rounded-xl border border-gray-200 dark:border-zinc-700 shadow-sm">
                <div>
                    <span class="text-zinc-500 dark:text-zinc-400 text-xs uppercase tracking-wider font-semibold">Siswa</span>
                    <div class="font-bold text-zinc-900 dark:text-white text-xl mt-1">{{ $record->user->name ?? '-' }}</div>
                </div>
                <div>
                    <span class="text-zinc-500 dark:text-zinc-400 text-xs uppercase tracking-wider font-semibold">Nilai Total</span>
                    <div class="font-bold text-primary-600 dark:text-primary-400 text-xl mt-1">{{ $record->total_score }}</div>
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

            {{-- Question Map (Right Side on XL, Bottom on others) --}}
            <div class="xl:col-span-1">
                <div class="bg-white dark:bg-zinc-900/50 rounded-xl p-4 border border-zinc-200 dark:border-zinc-700 shadow-sm sticky top-24">
                    <h3 class="font-bold text-zinc-900 dark:text-white flex items-center gap-2 mb-4 text-sm">
                        <x-heroicon-m-squares-2x2 class="w-4 h-4 text-primary-500" />
                        Peta Soal
                    </h3>

                    <div class="grid grid-cols-5 gap-2">
                        @foreach($record->details as $index => $detail)
                        @php
                        $isCorrect = $detail->is_correct;
                        $colorClass = match($isCorrect) {
                        true => 'bg-success-500 text-white hover:bg-success-600 shadow-success-500/20',
                        false => 'bg-danger-500 text-white hover:bg-danger-600 shadow-danger-500/20',
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
                                <span class="w-2 h-2 rounded-full bg-success-500"></span>
                                <span class="text-zinc-500">Benar</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-danger-500"></span>
                                <span class="text-zinc-500">Salah</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-zinc-300 dark:bg-zinc-700"></span>
                                <span class="text-zinc-500">Pending</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Questions List --}}
        <div class="xl:w-3/4 space-y-8">
            @foreach($record->details as $index => $detail)
            @php
            $question = $detail->examQuestion;
            $isCorrect = $detail->is_correct;
            $bgClass = match($isCorrect) {
            true => 'bg-white dark:bg-zinc-900 border-success-200 dark:border-success-900/30 ring-1 ring-success-50 dark:ring-transparent',
            false => 'bg-white dark:bg-zinc-900 border-danger-200 dark:border-danger-900/30 ring-1 ring-danger-50 dark:ring-transparent',
            default => 'bg-white dark:bg-zinc-900 border-zinc-200 dark:border-zinc-700'
            };
            $keyAnswer = $question->key_answer ?? [];
            @endphp
            <div id="question-{{ $index + 1 }}"
                class="relative p-6 rounded-2xl shadow-sm border-2 {{ $bgClass }} scroll-mt-24 transition-all hover:shadow-md">

                {{-- Question Status Badge --}}
                <div class="absolute -left-3 top-6 flex items-center">
                    <div class="h-10 w-1 rounded-full {{ $isCorrect === true ? 'bg-success-500' : ($isCorrect === false ? 'bg-danger-500' : 'bg-zinc-300 dark:bg-zinc-600') }}"></div>
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
                            <span class="text-xs font-bold px-3 py-1.5 rounded-full shadow-sm
                                        {{ $isCorrect ? 'bg-success-500 text-white' : ($isCorrect === false ? 'bg-danger-500 text-white' : 'bg-zinc-500 text-white') }}">
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
                            <div class="p-1.5 bg-zinc-100 dark:bg-zinc-800 rounded-lg group-hover:bg-primary-100 dark:group-hover:bg-primary-900/30 transition-colors">
                                <x-heroicon-m-user class="w-4 h-4 text-zinc-600 dark:text-zinc-400 group-hover:text-primary-600 dark:group-hover:text-primary-400" />
                            </div>
                            Jawaban Siswa
                        </div>
                        <div class="p-4 bg-zinc-50 dark:bg-zinc-950/50 rounded-xl border-2 border-zinc-100 dark:border-zinc-800 min-h-[60px] flex items-center">
                            @if(is_array($detail->student_answer))
                            @if(isset($detail->student_answer['pairs'])) {{-- Matching --}}
                            <ul class="w-full space-y-2">
                                @foreach($detail->student_answer['pairs'] as $left => $right)
                                <li class="flex items-center justify-between p-2 bg-white dark:bg-zinc-900 rounded-lg border border-zinc-100 dark:border-zinc-800">
                                    <span class="text-sm font-medium text-zinc-500">{{ $left }}</span>
                                    <x-heroicon-m-arrow-long-right class="w-4 h-4 text-zinc-300" />
                                    <span class="text-sm font-bold text-primary-600 dark:text-primary-400">{{ $right ?? '-' }}</span>
                                </li>
                                @endforeach
                            </ul>
                            @elseif(isset($detail->student_answer['order'])) {{-- Ordering --}}
                            <div class="flex flex-wrap gap-2">
                                @foreach($detail->student_answer['order'] as $item)
                                <span class="px-3 py-1.5 bg-white dark:bg-zinc-900 rounded-lg text-sm font-bold border border-zinc-100 dark:border-zinc-800 shadow-sm text-zinc-700 dark:text-zinc-300">
                                    {{ $item }}
                                </span>
                                @endforeach
                            </div>
                            @elseif(isset($detail->student_answer['answers'])) {{-- Multiple Selection --}}
                            <div class="flex flex-wrap gap-2">
                                @foreach($detail->student_answer['answers'] as $ans)
                                <span class="px-3 py-1.5 bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-400 rounded-lg text-sm font-bold border border-primary-100 dark:border-primary-800 shadow-sm">
                                    {{ $ans }}
                                </span>
                                @endforeach
                            </div>
                            @else
                            <ul class="list-none w-full">
                                @foreach($detail->student_answer as $ans)
                                <li class="py-1 flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-primary-400"></span>
                                    <span class="text-sm font-medium">{{ $ans }}</span>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                            @else
                            <div class="text-zinc-900 dark:text-zinc-100 font-medium italic">
                                {{ $detail->student_answer ?? '(Tidak menjawab)' }}
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Kunci Jawaban --}}
                    <div class="group">
                        <div class="font-bold text-success-600 dark:text-success-400 mb-3 flex items-center gap-2 text-xs uppercase tracking-widest">
                            <div class="p-1.5 bg-success-50 dark:bg-success-900/20 rounded-lg group-hover:bg-success-100 dark:group-hover:bg-success-900/40 transition-colors">
                                <x-heroicon-m-check-badge class="w-4 h-4 text-success-600 dark:text-success-400" />
                            </div>
                            Kunci Jawaban
                        </div>
                        <div class="p-4 bg-success-50/30 dark:bg-success-900/10 rounded-xl border-2 border-success-100/50 dark:border-success-900/20 min-h-[60px] flex items-center">
                            @php
                            $type = $question->question_type;
                            @endphp

                            @if($type === \App\Enums\QuestionTypeEnum::MultipleChoice || $type === \App\Enums\QuestionTypeEnum::TrueFalse)
                            <span class="font-black text-success-700 dark:text-success-400 text-3xl">
                                {{ $keyAnswer['answer'] ?? '-' }}
                            </span>
                            @elseif($type === \App\Enums\QuestionTypeEnum::MultipleSelection)
                            <div class="flex flex-wrap gap-2">
                                @foreach($keyAnswer['answers'] ?? [] as $ans)
                                <span class="px-3 py-1.5 bg-success-500 text-white rounded-lg text-sm font-black shadow-sm">
                                    {{ $ans }}
                                </span>
                                @endforeach
                            </div>
                            @elseif($type === \App\Enums\QuestionTypeEnum::Matching)
                            <ul class="w-full space-y-2">
                                @foreach($keyAnswer['pairs'] ?? [] as $left => $right)
                                <li class="flex items-center justify-between p-2 bg-white/50 dark:bg-zinc-900/50 rounded-lg border border-success-100 dark:border-success-900/30">
                                    <span class="text-sm font-medium text-zinc-500">{{ $left }}</span>
                                    <x-heroicon-m-arrow-long-right class="w-4 h-4 text-success-300" />
                                    <span class="text-sm font-black text-success-600 dark:text-success-400">{{ $right }}</span>
                                </li>
                                @endforeach
                            </ul>
                            @elseif($type === \App\Enums\QuestionTypeEnum::Ordering)
                            <div class="flex flex-wrap gap-2">
                                @foreach($keyAnswer['order'] ?? [] as $item)
                                <span class="px-3 py-1.5 bg-success-100 dark:bg-success-900/40 text-success-700 dark:text-success-300 rounded-lg text-sm font-black border border-success-200 dark:border-success-800 shadow-sm">
                                    {{ $item }}
                                </span>
                                @endforeach
                            </div>
                            @elseif($type === \App\Enums\QuestionTypeEnum::NumericalInput)
                            <div class="flex items-baseline gap-1">
                                <span class="font-black text-success-700 dark:text-success-400 text-2xl">{{ $keyAnswer['answer'] ?? '-' }}</span>
                                @if(isset($keyAnswer['unit']))
                                <span class="text-sm font-bold text-zinc-500">{{ $keyAnswer['unit'] }}</span>
                                @endif
                                @if(isset($keyAnswer['tolerance']) && $keyAnswer['tolerance'] > 0)
                                <span class="text-[10px] text-zinc-400 ml-2 uppercase tracking-wide">±{{ $keyAnswer['tolerance'] }}</span>
                                @endif
                            </div>
                            @elseif($type === \App\Enums\QuestionTypeEnum::Essay)
                            <div class="text-xs text-zinc-600 dark:text-zinc-500 italic font-medium">
                                @if(isset($keyAnswer['rubric']))
                                <span class="flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-success-400"></span>
                                    Rubrik penilaian tersedia.
                                </span>
                                @else
                                <span class="flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-warning-400"></span>
                                    Perlu koreksi manual.
                                </span>
                                @endif
                            </div>
                            @else
                            <span class="text-zinc-400 italic">Tidak tersedia</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($detail->correction_notes)
                <div class="mt-6 p-4 bg-warning-50/50 dark:bg-warning-950/10 border-2 border-warning-100 dark:border-warning-900/20 rounded-xl">
                    <div class="font-bold text-warning-700 dark:text-warning-400 mb-2 flex items-center gap-2 text-xs uppercase tracking-widest">
                        <x-heroicon-m-chat-bubble-left-right class="w-4 h-4" />
                        Catatan Koreksi
                    </div>
                    <div class="text-zinc-700 dark:text-zinc-300 text-sm leading-relaxed">
                        {{ $detail->correction_notes }}
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>