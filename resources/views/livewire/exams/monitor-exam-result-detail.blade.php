<div class="px-4 py-6 sm:px-6 lg:px-8">
    <x-mary-header title="Detail Pengerjaan Ujian" subtitle="Analisis jawaban siswa per soal" separator>
        <x-slot:actions>
            <x-mary-button label="Kembali ke Monitor" icon="o-arrow-left" link="{{ route('filament.admin.resources.exams.monitor', ['record' => $record->exam_id]) }}" />
        </x-slot:actions>
    </x-mary-header>

    <div class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm bg-gray-50 dark:bg-gray-800/50 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
            <div>
                <span class="text-gray-500 dark:text-gray-400">Siswa:</span>
                <div class="font-bold text-gray-900 dark:text-white text-lg">{{ $record->user->name ?? '-' }}</div>
            </div>
            <div>
                <span class="text-gray-500 dark:text-gray-400">Nilai Total:</span>
                <div class="font-bold text-gray-900 dark:text-white text-lg">{{ $record->total_score }}</div>
            </div>
            <div>
                <span class="text-gray-500 dark:text-gray-400">Waktu Mulai:</span>
                <div class="font-bold text-gray-900 dark:text-white">{{ $record->start_time?->format('d M Y H:i') ?? '-' }}</div>
            </div>
            <div>
                <span class="text-gray-500 dark:text-gray-400">Durasi:</span>
                <div class="font-bold text-gray-900 dark:text-white">{{ $record->duration_taken }} menit</div>
            </div>
        </div>

        <div class="space-y-6">
            @foreach($record->details as $index => $detail)
            @php
            $question = $detail->examQuestion;
            $isCorrect = $detail->is_correct;
            $borderClass = match($isCorrect) {
            true => 'border-l-4 border-success-500 bg-success-50 dark:bg-success-950/10',
            false => 'border-l-4 border-danger-500 bg-danger-50 dark:bg-danger-950/10',
            default => 'border-l-4 border-gray-300 bg-gray-50 dark:bg-gray-800/50'
            };
            $keyAnswer = $question->key_answer ?? [];
            @endphp
            <div class="{{ $borderClass }} p-4 rounded-r-lg shadow-sm border border-gray-100 dark:border-gray-800">
                <div class="mb-4 flex justify-between items-start border-b border-gray-100 dark:border-gray-800 pb-2">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-gray-900 dark:text-white">No. {{ $question->question_number ?? $index + 1 }}</span>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                            {{ $question->question_type->getLabel() }}
                        </span>
                    </div>
                    <span class="text-xs font-semibold px-2 py-1 rounded
                            {{ $isCorrect ? 'bg-success-100 text-success-700 dark:bg-success-900/30 dark:text-success-400' : ($isCorrect === false ? 'bg-danger-100 text-danger-700 dark:bg-danger-900/30 dark:text-danger-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-700/50 dark:text-gray-400') }}">
                        {{ $isCorrect ? 'Benar' : ($isCorrect === false ? 'Salah' : 'Belum Dikoreksi') }}
                        ({{ $detail->score_earned }} / {{ $question->score_value }} poin)
                    </span>
                </div>

                <div class="prose dark:prose-invert max-w-none text-sm mb-4" wire:ignore>
                    {!! $question->content !!}
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-100 dark:border-gray-800">
                    {{-- Jawaban Siswa --}}
                    <div class="text-sm">
                        <div class="font-bold text-gray-600 dark:text-gray-400 mb-2 flex items-center gap-1">
                            <x-heroicon-m-user class="w-4 h-4" />
                            Jawaban Siswa:
                        </div>
                        <div class="p-3 bg-white dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700 min-h-[50px]">
                            @if(is_array($detail->student_answer))
                            @if(isset($detail->student_answer['pairs'])) {{-- Matching --}}
                            <ul class="space-y-1">
                                @foreach($detail->student_answer['pairs'] as $left => $right)
                                <li class="flex items-center gap-2">
                                    <span class="font-medium text-gray-500">{{ $left }}:</span>
                                    <span class="text-primary-600 dark:text-primary-400">{{ $right ?? '-' }}</span>
                                </li>
                                @endforeach
                            </ul>
                            @elseif(isset($detail->student_answer['order'])) {{-- Ordering --}}
                            <div class="flex flex-wrap gap-2">
                                @foreach($detail->student_answer['order'] as $item)
                                <span class="px-2 py-1 bg-gray-100 dark:bg-gray-800 rounded text-xs border border-gray-200 dark:border-gray-700">
                                    {{ $item }}
                                </span>
                                @endforeach
                            </div>
                            @elseif(isset($detail->student_answer['answers'])) {{-- Multiple Selection --}}
                            <div class="flex flex-wrap gap-2">
                                @foreach($detail->student_answer['answers'] as $ans)
                                <span class="px-2 py-1 bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-400 rounded text-xs font-bold border border-primary-100 dark:border-primary-800">
                                    {{ $ans }}
                                </span>
                                @endforeach
                            </div>
                            @else
                            <ul class="list-disc list-inside">
                                @foreach($detail->student_answer as $ans)
                                <li>{{ $ans }}</li>
                                @endforeach
                            </ul>
                            @endif
                            @else
                            <div class="text-gray-900 dark:text-gray-100">
                                {{ $detail->student_answer ?? '(Tidak menjawab)' }}
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Kunci Jawaban --}}
                    <div class="text-sm">
                        <div class="font-bold text-success-600 dark:text-success-400 mb-2 flex items-center gap-1">
                            <x-heroicon-m-check-badge class="w-4 h-4" />
                            Kunci Jawaban:
                        </div>
                        <div class="p-3 bg-success-50/50 dark:bg-success-950/10 rounded-lg border border-success-200 dark:border-success-800/50 min-h-[50px]" wire:ignore>
                            @php
                            $type = $question->question_type;
                            @endphp

                            @if($type === \App\Enums\QuestionTypeEnum::MultipleChoice || $type === \App\Enums\QuestionTypeEnum::TrueFalse)
                            <span class="font-bold text-success-700 dark:text-success-400 text-lg">
                                {{ $keyAnswer['answer'] ?? '-' }}
                            </span>
                            @elseif($type === \App\Enums\QuestionTypeEnum::MultipleSelection)
                            <div class="flex flex-wrap gap-2">
                                @foreach($keyAnswer['answers'] ?? [] as $ans)
                                <span class="px-2 py-1 bg-success-200 dark:bg-success-800 text-success-800 dark:text-success-100 rounded text-xs font-bold">
                                    {{ $ans }}
                                </span>
                                @endforeach
                            </div>
                            @elseif($type === \App\Enums\QuestionTypeEnum::Matching)
                            <ul class="space-y-1">
                                @foreach($keyAnswer['pairs'] ?? [] as $left => $right)
                                <li class="flex items-center gap-2">
                                    <span class="font-medium text-gray-500">{{ $left }}:</span>
                                    <span class="text-success-600 dark:text-success-400">{{ $right }}</span>
                                </li>
                                @endforeach
                            </ul>
                            @elseif($type === \App\Enums\QuestionTypeEnum::Ordering)
                            <div class="flex flex-wrap gap-2">
                                @foreach($keyAnswer['order'] ?? [] as $item)
                                <span class="px-2 py-1 bg-success-100 dark:bg-success-900/40 text-success-700 dark:text-success-300 rounded text-xs border border-success-200 dark:border-success-800">
                                    {{ $item }}
                                </span>
                                @endforeach
                            </div>
                            @elseif($type === \App\Enums\QuestionTypeEnum::NumericalInput)
                            <div>
                                <span class="font-bold text-success-700 dark:text-success-400">{{ $keyAnswer['answer'] ?? '-' }}</span>
                                @if(isset($keyAnswer['unit']))
                                <span class="text-gray-500 ml-1">{{ $keyAnswer['unit'] }}</span>
                                @endif
                                @if(isset($keyAnswer['tolerance']) && $keyAnswer['tolerance'] > 0)
                                <span class="text-xs text-gray-400 ml-2">(Toleransi: ±{{ $keyAnswer['tolerance'] }})</span>
                                @endif
                            </div>
                            @elseif($type === \App\Enums\QuestionTypeEnum::Essay)
                            <div class="text-xs text-gray-600 dark:text-gray-400 italic">
                                @if(isset($keyAnswer['rubric']))
                                Rubrik penilaian tersedia.
                                @else
                                Perlu koreksi manual.
                                @endif
                            </div>
                            @else
                            <span class="text-gray-400 italic">Tidak tersedia</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($detail->correction_notes)
                <div class="mt-4 p-3 bg-warning-50 dark:bg-warning-950/10 border border-warning-200 dark:border-warning-800/50 rounded-lg text-sm">
                    <div class="font-bold text-warning-700 dark:text-warning-400 mb-1 flex items-center gap-1">
                        <x-heroicon-m-chat-bubble-left-right class="w-4 h-4" />
                        Catatan Koreksi:
                    </div>
                    <div class="text-gray-700 dark:text-gray-300">
                        {{ $detail->correction_notes }}
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>