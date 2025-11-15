<div>
    @if ($question)
    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl p-6 space-y-6 border border-gray-200 dark:border-gray-700">

        <div class="flex items-start justify-between border-b pb-4">
            <div class="flex items-center space-x-3">
                <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full capitalize 
                       bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                    {{ $question->question_type->getLabel() }}
                </span>

                <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full capitalize 
                       bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                    {{ $question->difficulty_level->getLabel() }}
                </span>

                <div class="text-xs text-gray-600 dark:text-gray-400 border border-gray-300 dark:border-gray-600 rounded-lg px-2 py-1 bg-gray-50 dark:bg-gray-700">
                    <span class="font-medium">{{ $question->timer?->getLabel() ?? 'N/A' }}</span>
                </div>

                <div class="text-xs text-gray-600 dark:text-gray-400 border border-gray-300 dark:border-gray-600 rounded-lg px-2 py-1 bg-gray-50 dark:bg-gray-700">
                    <span class="font-medium">{{ $question->score_value ?? 'N/A' }}</span> point
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <button type="button"
                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-lg text-gray-700 bg-gray-100 hover:bg-gray-200 
                               dark:text-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 transition duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                </button>
                <button type="button"
                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 
                               dark:bg-red-700 dark:hover:bg-red-800 transition duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        @if ($question->readingMaterial)
        <div class="p-4 rounded-lg border-l-4 border-blue-500 bg-blue-50 dark:bg-blue-950/50 dark:border-blue-700">
            <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200 mb-2">
                Stimulus/Materi Bacaan: {{ $question->readingMaterial->title }}
            </h4>
            <div class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed space-y-4">
                {!! nl2br(e($question->readingMaterial->content)) !!}
            </div>
        </div>
        @endif

        <div class="text-base text-gray-800 dark:text-gray-200 leading-relaxed space-y-4">
            {!! nl2br(e($question->content)) !!}
        </div>

        @if ($question->question_type === 'multiple choice')
        <h3 class="text-xl font-bold text-gray-900 dark:text-white pt-4">
            Pilihan Jawaban (Kunci)
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($this->getOptions() as $key => $option)
            <div @class([ 'p-4 rounded-xl border flex items-start space-x-3 transition duration-150' , '!bg-red-50 !border-red-400 !text-red-800 dark:!bg-red-950 dark:!border-red-700 dark:!text-red-300'=> !$option['is_correct'],
                '!bg-green-50 !border-green-400 !text-green-800 dark:!bg-green-950 dark:!border-green-700 dark:!text-green-300' => $option['is_correct'],
                ])>
                <div class="flex-shrink-0 mt-0.5">
                    @if ($option['is_correct'])
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    @else
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    @endif
                </div>

                <p class="text-sm m-0 leading-relaxed">
                    <strong class="font-bold">{{ $key }}.</strong> {!! $option['text'] !!}
                </p>
            </div>
            @endforeach
        </div>
        @endif

        @if ($question->feedback)
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6 space-y-2">
            <p class="text-sm font-bold text-gray-600 dark:text-gray-400">Umpan Balik Umum:</p>
            <div class="text-base text-gray-800 dark:text-gray-200 leading-relaxed">
                {!! nl2br(e($question->feedback)) !!}
            </div>
        </div>
        @endif
    </div>
    @else
    <div class="p-4 rounded-lg bg-yellow-100 text-yellow-800 border border-yellow-300 dark:bg-yellow-950 dark:text-yellow-300 dark:border-yellow-700">
        Soal tidak ditemukan atau ID soal tidak valid.
    </div>
    @endif
</div>