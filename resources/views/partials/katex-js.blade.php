{{-- KaTeX JS --}}
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js" integrity="sha384-XjKyOOlGwcjNTAIQHIpgOno0Hl1YQqzUOEleOLALmuqehneUG+vnGctmUb0ZY0l8" crossorigin="anonymous"></script>
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js" integrity="sha384-+VBxd3r6XgURycqtZ117nYw44OOcIax56Z4dCRWbxyPt0Koah1uHoK0o4+/RRE05" crossorigin="anonymous"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Filament KaTeX: DOM loaded, initializing...');

        // Function to render KaTeX in an element - make it globally accessible
        window.renderKaTeX = function(element) {
            if (typeof renderMathInElement !== 'undefined') {
                console.log('Filament KaTeX: Rendering math in element', element);

                // Clone element dan simpan untuk comparison (optional, bisa di-comment jika tidak perlu)
                renderMathInElement(element, {
                    delimiters: [{
                            left: '$$',
                            right: '$$',
                            display: true
                        },
                        {
                            left: '$',
                            right: '$',
                            display: false
                        },
                        {
                            left: '\\(',
                            right: '\\)',
                            display: false
                        },
                        {
                            left: '\\[',
                            right: '\\]',
                            display: true
                        }
                    ],
                    throwOnError: false,
                    strict: false,
                    // Ignore elements yang sudah di-render untuk menghindari double rendering
                    ignoredTags: ['script', 'noscript', 'style', 'textarea', 'pre', 'code'],
                    ignoredClasses: [],
                    // Preserve display mode untuk maintain size
                    displayMode: false,
                    // Trust mode untuk allow all HTML
                    trust: true
                });
            }
        }

        // Initial render after KaTeX loads
        setTimeout(function() {
            if (typeof renderMathInElement !== 'undefined') {
                console.log('Filament KaTeX: Ready, rendering math...');
                window.renderKaTeX(document.body);
                console.log('Filament KaTeX: Auto-render initialized');
            } else {
                console.log('Filament KaTeX: Not ready, retrying...');
                setTimeout(arguments.callee, 500);
            }
        }, 1000);

        // Livewire 3 hook for re-rendering after updates
        document.addEventListener('livewire:init', () => {
            Livewire.hook('morph.updated', ({
                el,
                component
            }) => {
                console.log('Filament KaTeX: Livewire morph updated, re-rendering math');
                window.renderKaTeX(el);
            });

            Livewire.hook('commit', ({
                component,
                commit,
                respond
            }) => {
                console.log('Filament KaTeX: Livewire commit, re-rendering math');
                // Re-render after commit completes
                queueMicrotask(() => {
                    window.renderKaTeX(document.body);
                });
            });
        });
    });
</script>