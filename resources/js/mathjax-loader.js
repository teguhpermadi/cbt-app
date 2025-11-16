// MathJax Loader for LaTeX rendering
export function loadMathJax() {
    return new Promise((resolve, reject) => {
        // Check if MathJax is already loaded
        if (window.MathJax) {
            resolve(window.MathJax);
            return;
        }

        // Configure MathJax before loading
        window.MathJax = {
            tex: {
                inlineMath: [
                    ['$', '$'],
                    ['\\(', '\\)']
                ],
                displayMath: [
                    ['$$', '$$'],
                    ['\\[', '\\]']
                ],
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
                        resolve(MathJax);
                    });
                }
            }
        };

        // Load MathJax script
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js';
        script.async = true;
        script.onload = () => resolve(window.MathJax);
        script.onerror = () => reject(new Error('Failed to load MathJax'));
        document.head.appendChild(script);
    });
}

// Auto-load MathJax when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    loadMathJax().catch(console.error);
});
