{{-- KaTeX JS --}}
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js" integrity="sha384-XjKyOOlGwcjNTAIQHIpgOno0Hl1YQqzUOEleOLALmuqehneUG+vnGctmUb0ZY0l8" crossorigin="anonymous"></script>
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js" integrity="sha384-+VBxd3r6XgURycqtZ117nYw44OOcIax56Z4dCRWbxyPt0Koah1uHoK0o4+/RRE05" crossorigin="anonymous"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Filament KaTeX: DOM loaded, initializing...');
    
    setTimeout(function() {
        if (typeof renderMathInElement !== 'undefined') {
            console.log('Filament KaTeX: Ready, rendering math...');
            renderMathInElement(document.body, {
                delimiters: [
                    {left: '$$', right: '$$', display: true},
                    {left: '$', right: '$', display: false},
                    {left: '\\(', right: '\\)', display: false},
                    {left: '\\[', right: '\\]', display: true}
                ],
                throwOnError: false,
                strict: false
            });
            console.log('Filament KaTeX: Auto-render initialized');
        } else {
            console.log('Filament KaTeX: Not ready, retrying...');
            setTimeout(arguments.callee, 500);
        }
    }, 1000);
    
    if (window.Livewire) {
        window.Livewire.hook('message.processed', function() {
            console.log('Filament KaTeX: Livewire update, re-rendering');
            if (typeof renderMathInElement !== 'undefined') {
                renderMathInElement(document.body, {
                    delimiters: [
                        {left: '$$', right: '$$', display: true},
                        {left: '$', right: '$', display: false},
                        {left: '\\(', right: '\\)', display: false},
                        {left: '\\[', right: '\\]', display: true}
                    ],
                    throwOnError: false,
                    strict: false
                });
            }
        });
    }
});
</script>
