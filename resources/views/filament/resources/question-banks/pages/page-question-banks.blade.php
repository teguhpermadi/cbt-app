<x-filament-panels::page>
    <div class="space-y-4">
        @forelse ($record->questions as $question)
        <livewire:question.question-component :question="$question" wire:key="question-{{ $question->id }}" />
        @empty
        <div class="text-center p-10 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 dark:bg-gray-800 dark:border-gray-700">
            <x-filament::icon
                icon="heroicon-o-document-text"
                class="w-12 h-12 text-gray-400 mb-2 mx-auto" />
            <p class="text-lg font-bold text-gray-600 dark:text-gray-300">Belum ada soal</p>
            <p class="text-gray-500 mb-4 dark:text-gray-400">Silakan tambahkan soal baru untuk memulai.</p>
            {{-- Button here is redundant if header has it, but good for empty state --}}
            <x-filament::button
                tag="a"
                :href="route('questions.create', ['questionBank' => $record->id])"
                icon="heroicon-o-plus"
                size="sm">
                Buat Soal Pertama
            </x-filament::button>
        </div>
        @endforelse
    </div>
</x-filament-panels::page>