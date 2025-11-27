<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
    @include('partials.katex-css')
    @include('partials.katex-js')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <div class="mb-6 px-4 py-4">
            <a href="{{ route('question-banks.index') }}" class="flex items-center text-sm text-zinc-500 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-zinc-200 transition-colors" wire:navigate>
                <flux:icon.arrow-left class="w-4 h-4 mr-2" />
                Kembali
            </a>
        </div>

        @livewire('question-bank-sidebar', ['questionBankId' => $questionBank->id])
    </flux:sidebar>

    <flux:header class="border-b border-zinc-200 bg-white dark:border-zinc-700 dark:bg-zinc-900 px-6 py-4">
        <div class="flex items-center justify-between w-full">
            <div class="flex items-center gap-4">
                <flux:sidebar.toggle class="lg:hidden" icon="bars-2" />

                <div>
                    <h1 class="text-xl font-bold text-zinc-900 dark:text-zinc-100">{{ $questionBank->name }}</h1>
                    @if($questionBank->description)
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">{{ Str::limit($questionBank->description, 100) }}</p>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center space-x-2 text-sm text-zinc-500">
                    <span class="flex items-center">
                        <flux:icon.user class="w-4 h-4 mr-1" />
                        {{ $questionBank->teacher->name ?? 'Unknown' }}
                    </span>
                    <span class="flex items-center">
                        <flux:icon.calendar class="w-4 h-4 mr-1" />
                        {{ $questionBank->created_at->format('d M Y') }}
                    </span>
                </div>
            </div>
        </div>
    </flux:header>

    <flux:main class="p-6">
        {{ $slot }}
    </flux:main>

    @livewire('notifications')
    @filamentScripts
    @fluxScripts

    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);

                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });

                    // Optional: Highlight the target
                    targetElement.classList.add('ring-2', 'ring-blue-500', 'ring-offset-2');
                    setTimeout(() => {
                        targetElement.classList.remove('ring-2', 'ring-blue-500', 'ring-offset-2');
                    }, 2000);
                }
            });
        });

        // Listen for page-refreshed event from OrderSelector
        document.addEventListener('livewire:init', () => {
            Livewire.on('page-refreshed', (event) => {
                console.log('Page refreshed event received:', event);

                const questionId = event.questionId || event[0]?.questionId;
                if (!questionId) {
                    console.warn('No question ID in event');
                    return;
                }

                // Function to try scrolling with retries
                function tryScroll(attempts = 0, maxAttempts = 10) {
                    const targetElement = document.getElementById('question-' + questionId);

                    if (targetElement) {
                        console.log('Scrolling to question:', questionId, 'after', attempts, 'attempts');
                        targetElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });

                        // Highlight the target
                        targetElement.classList.add('ring-2', 'ring-blue-500', 'ring-offset-2');
                        setTimeout(() => {
                            targetElement.classList.remove('ring-2', 'ring-blue-500', 'ring-offset-2');
                        }, 2000);
                    } else if (attempts < maxAttempts) {
                        console.log('Element not found yet, retrying... (attempt', attempts + 1, 'of', maxAttempts, ')');
                        setTimeout(() => tryScroll(attempts + 1, maxAttempts), 200);
                    } else {
                        console.warn('Target element not found for question after', maxAttempts, 'attempts:', questionId);
                    }
                }

                // Start trying to scroll after a short delay
                setTimeout(() => tryScroll(), 300);
            });
        });
    </script>
</body>

</html>