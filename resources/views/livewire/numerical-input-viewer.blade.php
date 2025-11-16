<div class="numerical-input-viewer">
    @if($showCorrectAnswers && !empty($correctAnswer))
        <!-- Answer Header -->
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                Kunci Jawaban
            </h3>
        </div>

        <!-- Answer Display -->
        <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
            @if($this->isValidLatex())
                <!-- LaTeX Expression Display -->
                <div class="text-center py-4">
                    @if($this->shouldUseMathJax())
                        <div class="math-display text-2xl font-mono text-gray-900 dark:text-white">
                            $$ {{ $this->getDisplayFormat() }} $$
                        </div>
                    @else
                        <div class="text-lg font-mono text-gray-900 dark:text-white bg-white dark:bg-gray-900 p-3 rounded border">
                            {{ $this->getDisplayFormat() }}
                        </div>
                    @endif
                </div>
            @else
                <!-- Simple Answer -->
                <div class="text-lg font-mono text-gray-900 dark:text-white bg-white dark:bg-gray-900 p-3 rounded border">
                    {{ $correctAnswer }}
                </div>
            @endif
        </div>
    @else
        <!-- No Answer Available -->
        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
            <p class="text-sm">Tidak ada kunci jawaban yang tersedia</p>
        </div>
    @endif
</div>

@push('scripts')
@if($this->shouldUseMathJax())
<script>
    // MathJax is already loaded via Vite, just configure and render
    document.addEventListener('DOMContentLoaded', function() {
        if (window.MathJax) {
            // Re-render when Livewire updates
            if (window.Livewire) {
                window.Livewire.hook('message.processed', function() {
                    MathJax.typesetPromise([document.querySelector('.math-display')]);
                });
            }
        }
    });
</script>
@endif
@endpush
