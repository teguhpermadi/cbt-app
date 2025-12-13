<div>
    <x-mary-header title="{{ $questionBank->name }}" subtitle="{{ $questionBank->description }}" separator>
        <x-slot:actions>
            <x-mary-button label="Kembali" icon="o-arrow-left" link="{{ route('home') }}" />
            <x-mary-button label="Tambah Soal" icon="o-plus" class="btn-primary" wire:click="createQuestion" />
        </x-slot:actions>
    </x-mary-header>

    <div class="space-y-4">
        @forelse ($questionBank->questions as $question)
        <livewire:question.question-component :question="$question" wire:key="question-{{ $question->id }}" />
        @empty
        <div class="text-center p-10 bg-base-200 rounded-lg border-2 border-dashed border-base-300">
            <x-mary-icon name="o-document-text" class="w-12 h-12 text-gray-400 mb-2" />
            <p class="text-lg font-bold">Belum ada soal</p>
            <p class="text-gray-500 mb-4">Silakan tambahkan soal baru untuk memulai.</p>
            <x-mary-button label="Buat Soal Pertama" icon="o-plus" class="btn-primary btn-sm" wire:click="createQuestion" />
        </div>
        @endforelse
    </div>
</div>