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
                wire:model.live="questionType" />

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
                <div class="mb-4">
                    @if ($questionImage)
                    <div class="flex flex-col gap-2">
                        <img src="{{ $questionImage->temporaryUrl() }}" class="h-32 w-auto object-contain rounded-lg border border-gray-200" />
                        <x-mary-button label="Hapus Gambar" icon="o-trash" class="btn-error btn-sm w-fit" wire:click="deleteQuestionImage" />
                    </div>
                    @elseif($existingQuestionImageUrl)
                    <div class="flex flex-col gap-2">
                        <img src="{{ $existingQuestionImageUrl }}" class="h-32 w-auto object-contain rounded-lg border border-gray-200" />
                        <x-mary-button label="Hapus Gambar" icon="o-trash" class="btn-error btn-sm w-fit" wire:click="deleteQuestionImage" />
                    </div>
                    @else
                    <x-mary-file label="Gambar Soal (Opsional)" wire:model="questionImage" accept="image/*" />
                    @endif
                </div>

                <x-mary-textarea
                    label="Konten Soal"
                    wire:model="content"
                    placeholder="Masukkan konten soal di sini..."
                    rows="8"
                    hint="Tuliskan pertanyaan anda dengan jelas" />

                {{-- Options Section --}}
                @switch($questionType)
                @case('multiple_choice')
                <livewire:question.form.option.multiple-option-editor-component
                    :questionId="$question->id"
                    wire:key="multiple-option-editor-{{ $question->id }}" />
                @break
                @case('true_false')
                <livewire:question.form.option.true-false-editor-component
                    :questionId="$question->id"
                    wire:key="true-false-editor-{{ $question->id }}" />
                @break
                @case('multiple_selection')
                <livewire:question.form.option.multiple-selection-editor-component
                    :questionId="$question->id"
                    wire:key="multiple-selection-editor-{{ $question->id }}" />
                @break
                @case('essay')
                <livewire:question.form.option.essay-editor-component
                    :questionId="$question->id"
                    wire:key="essay-editor-{{ $question->id }}" />
                @break
                @case('ordering')
                <livewire:question.form.option.ordering-editor-component
                    :questionId="$question->id"
                    wire:key="ordering-editor-{{ $question->id }}" />
                @break
                @case('matching')
                <livewire:question.form.option.matching-editor-component
                    :questionId="$question->id"
                    wire:key="matching-editor-{{ $question->id }}" />
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