<div>
    <x-mary-header title="Edit Soal" separator />

    <div class="grid gap-6">
        {{-- Settings Section --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            {{-- Order Selector --}}
            <livewire:question.selector.order-selector-component
                :questionId="$question->id"
                :order="$question->order"
                wire:key="order-selector-{{ $question->id }}" />

            {{-- Question Type (Primary Form Field) --}}
            <x-mary-select
                label="Tipe Soal"
                :options="$questionTypes"
                wire:model="questionType" />

            {{-- Difficulty Selector --}}
            <livewire:question.selector.difficulty-selector-component
                :questionId="$question->id"
                :difficultyLevel="$question->difficulty_level->value"
                wire:key="difficulty-selector-{{ $question->id }}" />

            {{-- Timer Selector --}}
            <livewire:question.selector.timer-selector-component
                :questionId="$question->id"
                :timer="$question->timer->value"
                wire:key="timer-selector-{{ $question->id }}" />

            {{-- Score Selector --}}
            <livewire:question.selector.score-selector-component
                :questionId="$question->id"
                :scoreValue="$question->score_value"
                wire:key="score-selector-{{ $question->id }}" />
        </div>

        {{-- Content Section --}}
        <x-mary-form wire:submit="update">
            <div class="mb-4">
                <x-mary-file label="Gambar Soal (Opsional)" wire:model="questionImage" accept="image/*" />
                @if ($questionImage)
                <div class="mt-2">
                    <img src="{{ $questionImage->temporaryUrl() }}" class="h-32 rounded-lg border border-gray-200" />
                </div>
                @elseif($existingQuestionImageUrl)
                <div class="mt-2 relative inline-block">
                    <img src="{{ $existingQuestionImageUrl }}" class="h-32 rounded-lg border border-gray-200" />
                </div>
                @endif
            </div>

            <x-mary-textarea
                label="Konten Soal"
                wire:model="content"
                placeholder="Masukkan konten soal di sini..."
                rows="8"
                hint="Tuliskan pertanyaan anda dengan jelas" />

            {{-- Options Section --}}
            @switch($question->question_type->value)
            @case('multiple_choice')
            <livewire:question.form.option.multiple-option-editor-component
                :questionId="$question->id"
                wire:key="multiple-option-editor-{{ $question->id }}" />
            @break
            @default
            {!! $question->options !!}
            @endswitch

            <x-slot:actions>
                <x-mary-button label="Batal" wire:click="cancel" />
                <x-mary-button label="Simpan Perubahan" class="btn-primary" type="submit" spinner="save2" />
            </x-slot:actions>
        </x-mary-form>
    </div>
</div>