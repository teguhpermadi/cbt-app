<div>
    <div class="card bg-base-100 shadow-sm border border-base-200">
        <div class="card-body p-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg">Opsi Jawaban</h3>
                <x-mary-button
                    label="Tambah Opsi"
                    icon="o-plus"
                    class="btn-sm btn-ghost"
                    wire:click="addOption" />
            </div>

            <div class="space-y-3">
                @foreach($options as $index => $option)
                <div class="flex gap-4 items-start" wire:key="option-{{ $index }}">
                    {{-- Key Label --}}
                    <div class="pt-3 font-bold w-8 text-center bg-base-200 rounded-lg aspect-square flex items-center justify-center">
                        {{ $option['option_key'] ?? chr(65 + $index) }}
                    </div>

                    {{-- Content Input & Media --}}
                    <div class="flex-1 space-y-2">
                        <x-mary-input
                            wire:model="options.{{ $index }}.content"
                            placeholder="Isi teks opsi jawaban..."
                            class="w-full" />

                        {{-- Media Upload --}}
                        <div class="flex items-center gap-4">
                            <div class="flex-1">
                                <x-mary-file
                                    wire:model="options.{{ $index }}.new_media"
                                    accept="image/*"
                                    class="file-input-xs file-input-ghost w-full max-w-xs" />
                            </div>

                            {{-- Preview --}}
                            @if(isset($option['new_media']) && is_object($option['new_media']))
                            <img src="{{ $option['new_media']->temporaryUrl() }}" class="h-10 w-10 object-cover rounded border" />
                            @elseif(isset($option['media_url']) && $option['media_url'])
                            <img src="{{ $option['media_url'] }}" class="h-10 w-10 object-cover rounded border" />
                            @endif
                        </div>
                    </div>

                    {{-- Correct Answer Toggle --}}
                    <div class="pt-2" title="Tandai sebagai kunci jawaban">
                        <input
                            type="radio"
                            name="correct_answer"
                            class="radio radio-success"
                            {{ $option['is_correct'] ? 'checked' : '' }}
                            wire:click="setCorrectAnswer({{ $index }})" />
                    </div>

                    {{-- Delete Button --}}
                    <div class="pt-1">
                        <x-mary-button
                            icon="o-trash"
                            class="btn-sm btn-circle btn-ghost text-error"
                            wire:click="removeOption({{ $index }})" />
                    </div>
                </div>
                @endforeach

                @if(empty($options))
                <div class="text-center py-8 text-gray-500 italic bg-base-50 rounded-lg border border-dashed">
                    Belum ada opsi jawaban. Klik "Tambah Opsi" untuk memulai.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>