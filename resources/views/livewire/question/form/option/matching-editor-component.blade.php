<div>
    <div class="card bg-base-100 shadow-sm border border-base-200">
        <div class="card-body p-4">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 class="font-bold text-lg">Pasangan Jawaban (Menjodohkan)</h3>
                    <p class="text-xs text-gray-500">Masukkan pasangan domain (kiri) dan kodomain (kanan) yang benar.</p>
                </div>
                <x-mary-button
                    label="Tambah Pasangan"
                    icon="o-plus"
                    class="btn-sm btn-ghost"
                    wire:click="addPair" />
            </div>

            <div class="space-y-4">
                @foreach($pairs as $index => $pair)
                <div class="flex gap-4 items-start p-3 bg-base-50 rounded-lg border border-base-200" wire:key="pair-{{ $index }}">

                    {{-- Index --}}
                    <div class="pt-10">
                        <span class="font-bold text-gray-400">#{{ $index + 1 }}</span>
                    </div>

                    <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- LEFT SIDE --}}
                        <div class="space-y-2">
                            <span class="text-xs font-bold text-primary uppercase">Domain (Kiri)</span>
                            <x-mary-textarea
                                wire:model="pairs.{{ $index }}.left_content"
                                placeholder="Isi domain..."
                                rows="2"
                                class="w-full" />

                            {{-- Left Media --}}
                            <div class="flex items-center gap-2">
                                @if(isset($pair['new_left_media']) && is_object($pair['new_left_media']))
                                <div class="relative inline-block group h-12 w-12">
                                    <img src="{{ $pair['new_left_media']->temporaryUrl() }}" class="h-full w-full object-cover rounded border" />
                                    <button type="button" class="absolute -top-1 -right-1 btn btn-circle btn-xs btn-error h-4 w-4 min-h-0" wire:click="deletePairMedia({{ $index }}, 'left')">
                                        <x-mary-icon name="o-x-mark" class="w-2 h-2" />
                                    </button>
                                </div>
                                @elseif(isset($pair['left_media_url']) && $pair['left_media_url'])
                                <div class="relative inline-block group h-12 w-12">
                                    <img src="{{ $pair['left_media_url'] }}" class="h-full w-full object-cover rounded border" />
                                    <button type="button" class="absolute -top-1 -right-1 btn btn-circle btn-xs btn-error h-4 w-4 min-h-0" wire:click="deletePairMedia({{ $index }}, 'left')">
                                        <x-mary-icon name="o-x-mark" class="w-2 h-2" />
                                    </button>
                                </div>
                                @else
                                <label class="cursor-pointer text-xs text-gray-500 hover:text-primary flex items-center gap-1">
                                    <x-mary-icon name="o-photo" class="w-4 h-4" />
                                    <span>Gambar Kiri</span>
                                    <input type="file" class="hidden" wire:model="pairs.{{ $index }}.new_left_media" accept="image/*" />
                                </label>
                                @endif
                            </div>
                        </div>

                        {{-- RIGHT SIDE --}}
                        <div class="space-y-2">
                            <span class="text-xs font-bold text-secondary uppercase">Kodomain (Kanan)</span>
                            <x-mary-textarea
                                wire:model="pairs.{{ $index }}.right_content"
                                placeholder="Isi kodomain..."
                                rows="2"
                                class="w-full" />

                            {{-- Right Media --}}
                            <div class="flex items-center gap-2">
                                @if(isset($pair['new_right_media']) && is_object($pair['new_right_media']))
                                <div class="relative inline-block group h-12 w-12">
                                    <img src="{{ $pair['new_right_media']->temporaryUrl() }}" class="h-full w-full object-cover rounded border" />
                                    <button type="button" class="absolute -top-1 -right-1 btn btn-circle btn-xs btn-error h-4 w-4 min-h-0" wire:click="deletePairMedia({{ $index }}, 'right')">
                                        <x-mary-icon name="o-x-mark" class="w-2 h-2" />
                                    </button>
                                </div>
                                @elseif(isset($pair['right_media_url']) && $pair['right_media_url'])
                                <div class="relative inline-block group h-12 w-12">
                                    <img src="{{ $pair['right_media_url'] }}" class="h-full w-full object-cover rounded border" />
                                    <button type="button" class="absolute -top-1 -right-1 btn btn-circle btn-xs btn-error h-4 w-4 min-h-0" wire:click="deletePairMedia({{ $index }}, 'right')">
                                        <x-mary-icon name="o-x-mark" class="w-2 h-2" />
                                    </button>
                                </div>
                                @else
                                <label class="cursor-pointer text-xs text-gray-500 hover:text-secondary flex items-center gap-1">
                                    <x-mary-icon name="o-photo" class="w-4 h-4" />
                                    <span>Gambar Kanan</span>
                                    <input type="file" class="hidden" wire:model="pairs.{{ $index }}.new_right_media" accept="image/*" />
                                </label>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Delete Button --}}
                    @if(count($pairs) > 1)
                    <div class="pt-8">
                        <x-mary-button
                            icon="o-trash"
                            class="btn-sm btn-circle btn-ghost text-error"
                            wire:click="removePair({{ $index }})" />
                    </div>
                    @endif
                </div>
                @endforeach

                <x-mary-button
                    label="Tambah Pasangan"
                    icon="o-plus"
                    class="btn-sm btn-ghost"
                    wire:click="addPair" />
            </div>
        </div>
    </div>
</div>