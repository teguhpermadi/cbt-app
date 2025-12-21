<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800 antialiased" x-data="{ showScrollTop: false }" @scroll.window="showScrollTop = (window.pageYOffset > 500)">
    <!-- Flux Header -->
    <flux:header sticky class="bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md border-b border-zinc-200 dark:border-zinc-700 h-16 flex items-center px-4 md:px-8 justify-between shadow-sm">
        <div class="flex items-center gap-4">
            <x-mary-button
                label="Kembali"
                icon="o-arrow-left"
                link="{{ $backUrl ?? '#' }}"
                class="btn-sm btn-ghost text-zinc-600 dark:text-zinc-400" />

            <flux:separator vertical class="mx-2 h-6" />

            <h1 class="text-md font-bold text-zinc-800 dark:text-zinc-200 truncate max-w-[200px] md:max-w-md">
                {{ $title ?? 'Detail Monitor' }}
            </h1>
        </div>
        <div class="flex items-center gap-3">
            <x-app-logo class="h-8 w-auto hidden md:block" />
        </div>
    </flux:header>

    <flux:main class="pt-6 pb-12 px-4 md:px-8 lg:px-12 max-w-[1600px] mx-auto">
        {{ $slot }}
    </flux:main>

    <!-- Scroll to Top Button -->
    <button
        x-cloak
        x-show="showScrollTop"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-10 scale-90"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-10 scale-90"
        @click="window.scrollTo({top: 0, behavior: 'smooth'})"
        class="fixed bottom-8 right-8 z-50 p-4 bg-primary text-primary-fg rounded-full shadow-2xl hover:opacity-90 transition-all hover:-translate-y-1 active:translate-y-0 focus:outline-none focus:ring-4 focus:ring-primary/30"
        title="Scroll to top">
        <x-heroicon-m-chevron-up class="w-6 h-6" />
    </button>

    @livewire('notifications')
    @filamentScripts
    @fluxScripts
</body>

</html>