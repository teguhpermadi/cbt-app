<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main>
        {{ $slot }}
    </flux:main>
    {{-- Include Sheaf UI JavaScript --}}
</x-layouts.app.sidebar>
