<div class="numerical-input-viewer">
    @if($showCorrectAnswers && !empty($correctAnswer))
        <!-- Answer Header -->
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                Kunci Jawaban
            </h3>
            <div class="flex items-center space-x-2">
                <span class="px-3 py-1 text-xs font-medium rounded-full
                    {{ $this->getAnswerType() === 'latex' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : 
                       $this->getAnswerType() === 'numeric' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 
                       'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200' }}">
                    {{ $this->getAnswerDescription() }}
                </span>
                @if($this->isValidLatex())
                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                        ✅ Valid LaTeX
                    </span>
                @endif
            </div>
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
                
                <!-- Normalized Form -->
                @if($this->getFormattedAnswer() !== $correctAnswer)
                    <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded border border-blue-200 dark:border-blue-800">
                        <p class="text-sm text-blue-800 dark:text-blue-200">
                            <strong>Format Normalisasi:</strong><br>
                            <code class="text-xs">{{ $this->getFormattedAnswer() }}</code>
                        </p>
                    </div>
                @endif
            @else
                <!-- Invalid LaTeX or Simple Answer -->
                <div class="space-y-3">
                    <div class="text-lg font-mono text-gray-900 dark:text-white bg-white dark:bg-gray-900 p-3 rounded border">
                        {{ $correctAnswer }}
                    </div>
                    
                    @if(!empty($this->getValidationError()))
                        <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded border border-red-200 dark:border-red-800">
                            <p class="text-sm text-red-800 dark:text-red-200">
                                <strong>⚠️ Validation Error:</strong><br>
                                {{ $this->getValidationError() }}
                            </p>
                        </div>
                    @endif
                    
                    @if(!empty($this->getValidationSuggestions()))
                        <div class="p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded border border-yellow-200 dark:border-yellow-800">
                            <p class="text-sm text-yellow-800 dark:text-yellow-200 mb-2">
                                <strong>💡 Suggestions:</strong>
                            </p>
                            <ul class="text-sm text-yellow-700 dark:text-yellow-300 space-y-1 list-disc list-inside">
                                @foreach($this->getValidationSuggestions() as $suggestion)
                                    <li>{{ $suggestion }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="mt-4 space-y-3">
            @if($this->getAnswerType() === 'latex')
                <div class="p-3 bg-purple-50 dark:bg-purple-900/20 rounded border border-purple-200 dark:border-purple-800">
                    <p class="text-sm text-purple-800 dark:text-purple-200">
                        <strong>📐 LaTeX Math Expression:</strong> Jawaban ini menggunakan notasi matematika LaTeX yang akan dirender menjadi format matematika yang indah.
                    </p>
                </div>
            @elseif($this->getAnswerType() === 'numeric')
                <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded border border-blue-200 dark:border-blue-800">
                    <p class="text-sm text-blue-800 dark:text-blue-200">
                        <strong>🔢 Numeric Answer:</strong> Jawaban ini berupa angka yang bisa dievaluasi langsung.
                    </p>
                </div>
            @endif

            <!-- LaTeX Examples -->
            @if($this->getAnswerType() === 'latex')
                <div class="p-3 bg-gray-50 dark:bg-gray-800 rounded border border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                        <strong>📚 Contoh LaTeX yang Didukung:</strong>
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-xs">
                        <div class="font-mono bg-white dark:bg-gray-900 p-2 rounded border">
                            \frac{a+b}{c} → \frac{a+b}{c}
                        </div>
                        <div class="font-mono bg-white dark:bg-gray-900 p-2 rounded border">
                            \sqrt{x^2 + y^2} → \sqrt{x^2 + y^2}
                        </div>
                        <div class="font-mono bg-white dark:bg-gray-900 p-2 rounded border">
                            \sin(\theta) + \cos(\theta) → \sin(\theta) + \cos(\theta)
                        </div>
                        <div class="font-mono bg-white dark:bg-gray-900 p-2 rounded border">
                            \sum_{i=1}^{n} x_i → \sum_{i=1}^{n} x_i
                        </div>
                    </div>
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

@push('styles')
<style>
.numerical-input-viewer {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.math-display {
    font-family: 'Latin Modern Math', 'STIX Two Math', 'Cambria Math', serif;
    line-height: 1.4;
}

/* LaTeX syntax highlighting */
.numerical-input-viewer code {
    background: rgba(0, 0, 0, 0.05);
    padding: 0.125rem 0.25rem;
    border-radius: 0.25rem;
    font-size: 0.875rem;
}

.dark .numerical-input-viewer code {
    background: rgba(255, 255, 255, 0.1);
}

/* Responsive grid */
@media (max-width: 768px) {
    .numerical-input-viewer .grid-cols-2 {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
@if($this->shouldUseMathJax())
<script>
// MathJax configuration for LaTeX rendering
window.MathJax = {
    tex: {
        inlineMath: [['$', '$'], ['\\(', '\\)']],
        displayMath: [['$$', '$$'], ['\\[', '\\]']],
        processEscapes: true,
        processEnvironments: true
    },
    options: {
        ignoreHtmlClass: 'tex2jax_ignore',
        processHtmlClass: 'tex2jax_process'
    },
    startup: {
        ready: function() {
            console.log('MathJax is ready');
            MathJax.startup.defaultReady();
            MathJax.startup.promise.then(function() {
                // Re-render when Livewire updates
                if (window.Livewire) {
                    window.Livewire.hook('message.processed', function() {
                        MathJax.typesetPromise([document.querySelector('.math-display')]);
                    });
                }
            });
        }
    }
};
</script>
<script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
@endif
@endpush
