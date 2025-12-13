<div>
    <div class="card bg-base-100 shadow-sm border border-base-200">
        <div class="card-body p-4">
            <div class="mb-4">
                <h3 class="font-bold text-lg">Opsi Jawaban (Benar/Salah)</h3>
                <p class="text-xs text-gray-500">Tentukan teks label untuk pilihan Benar dan Salah, serta kunci jawabannya.</p>
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
                            placeholder="Label (Contoh: Benar/Salah)"
                            class="w-full" />

                        {{-- Media Upload --}}
                        <div class="flex items-center gap-4">
                            <div class="flex-1">
                                {{-- Preview --}}
                                @if(isset($option['new_media']) && is_object($option['new_media']))
                                <div class="relative inline-block group">
                                    <img src="{{ $option['new_media']->temporaryUrl() }}" class="h-16 w-16 object-cover rounded border" />
                                    <button type="button"
                                        class="absolute -top-2 -right-2 btn btn-circle btn-xs btn-error"
                                        wire:click="deleteOptionMedia({{ $index }})">
                                        <x-mary-icon name="o-x-mark" class="w-3 h-3" />
                                    </button>
                                </div>
                                @elseif(isset($option['media_url']) && $option['media_url'])
                                <div class="relative inline-block group">
                                    <img src="{{ $option['media_url'] }}" class="h-16 w-16 object-cover rounded border" />
                                    <button type="button"
                                        class="absolute -top-2 -right-2 btn btn-circle btn-xs btn-error"
                                        wire:click="deleteOptionMedia({{ $index }})">
                                        <x-mary-icon name="o-x-mark" class="w-3 h-3" />
                                    </button>
                                </div>
                                @else
                                <x-mary-file
                                    wire:model="options.{{ $index }}.new_media"
                                    accept="image/*"
                                    class="file-input-xs file-input-ghost w-full max-w-xs" />
                                @endif
                            </div>
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
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>