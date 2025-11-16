<div>
    @if ($question)
    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl p-6 space-y-6 border border-gray-200 dark:border-gray-700">

        <div class="flex items-start justify-between border-b pb-4">
            <div class="flex-1">
                <div class="flex items-center space-x-3 mb-3">
                    <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full capitalize 
                           bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                        {{ $question->question_type->getLabel() }}
                    </span>

                    <livewire:timer-selector :question="$question" />
                    
                    <livewire:difficulty-selector :question="$question" />
                    
                    <livewire:question-score-selector :question="$question" />
                </div>
            </div>

            <div class="flex items-center space-x-2 ml-4">
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
        @if ($question->question_type->value === 'multiple_choice')
        <h3 class="text-xl font-bold text-gray-900 dark:text-white pt-4">
            Pilihan Jawaban (Kunci)
        </h3>
        <livewire:multiple-choice-viewer 
            :options="$this->getOptions()"
            :correct-answers="$this->getCorrectAnswers()"
            :show-correct-answers="true"
        />
        @elseif ($question->question_type->value === 'true_false')
        <h3 class="text-xl font-bold text-gray-900 dark:text-white pt-4">
            Pilihan Jawaban (Kunci)
        </h3>
        <livewire:true-false-viewer 
            :options="$this->getOptions()"
            :correct-answers="$this->getCorrectAnswers()"
            :show-correct-answers="true"
        />
        @elseif ($question->question_type->value === 'multiple_selection')
        <h3 class="text-xl font-bold text-gray-900 dark:text-white pt-4">
            Pilihan Jawaban (Kunci)
        </h3>
        <livewire:multiple-selection-viewer 
            :options="$this->getOptions()"
            :correct-answers="$this->getCorrectAnswers()"
            :show-correct-answers="true"
        />
        @elseif ($question->question_type->value === 'matching')
        <h3 class="text-xl font-bold text-gray-900 dark:text-white pt-4">
            Pilihan Jawaban (Kunci)
        </h3>
        <livewire:matching-viewer 
            :options="$this->getOptions()"
            :correct-answers="$this->getCorrectAnswers()"
            :show-correct-answers="true"
        />
        @elseif ($question->question_type->value === 'ordering')
        <h3 class="text-xl font-bold text-gray-900 dark:text-white pt-4">
            Kunci Jawaban Urutan
        </h3>
        <livewire:ordering-viewer 
            :options="$this->getOptions()"
            :correct-answers="$this->getCorrectAnswers()"
            :show-correct-answers="true"
        />
        @endif

        @if ($question->question_type->value === 'essay')
        <h3 class="text-xl font-bold text-gray-900 dark:text-white pt-4">
            Rubrik Penilaian
        </h3>
        <livewire:essay-answer-viewer 
            :key-answer="$this->getCorrectAnswers()"
            :question-type="$question->question_type"
            :show-correct-answers="true"
        />
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
    
    <!-- Notification Script -->
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('notify', (event) => {
                // Create a simple notification
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 px-4 py-2 rounded-lg text-white font-medium z-50 transition-all duration-300 ${
                    event.type === 'success' ? 'bg-green-500' : 'bg-red-500'
                }`;
                notification.textContent = event.message;
                
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.style.opacity = '0';
                    setTimeout(() => {
                        document.body.removeChild(notification);
                    }, 300);
                }, 2000);
            });
        });
    </script>
</div>