<div>
    <div class="card bg-base-100 shadow-sm border border-base-200">
        <div class="card-body p-4">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h3 class="font-bold text-lg">Urutan Jawaban</h3>
                    <p class="text-xs text-gray-500">Tentukan urutan yang Bena. Siswa harus mengurutkan item sesuai urutan ini.</p>
                </div>
                <x-mary-button
                    label="Tambah Item"
                    icon="o-plus"
                    class="btn-sm btn-ghost"
                    wire:click="addOption" />
            </div>

            <div
                id="ordering-list-{{ $questionId }}"
                class="space-y-3"
                x-data
                x-init="
                    new Sortable(document.getElementById('ordering-list-{{ $questionId }}'), {
                        animation: 150,
                        handle: '.drag-handle',
                        ghostClass: 'bg-base-200',
                        onEnd: function (evt) {
                            let orderedIndices = [];
                            document.querySelectorAll('#ordering-list-{{ $questionId }} .ordering-item').forEach((el, index) => {
                                orderedIndices.push(parseInt(el.getAttribute('data-index')));
                            });
                            $wire.updateOptionOrder(orderedIndices);
                        }
                    });
                ">

                @foreach($options as $index => $option)
                {{-- Note: data-index tracking old index to reconstruction on server side --}}
                <div class="ordering-item flex gap-4 items-center bg-base-50 p-3 rounded-lg border border-base-200"
                    wire:key="option-{{ $index }}"
                    data-index="{{ $index }}">

                    {{-- Drag Handle --}}
                    <div class="drag-handle cursor-move text-gray-400 hover:text-gray-600">
                        <x-mary-icon name="o-bars-3" class="w-6 h-6" />
                    </div>

                    {{-- Order Label --}}
                    <div class="font-bold w-8 text-center bg-white rounded-lg aspect-square flex items-center justify-center border border-base-200 shadow-sm">
                        {{ $loop->iteration }}
                    </div>

                    {{-- Content Input --}}
                    <div class="flex-1 space-y-2">
                        <x-mary-input
                            wire:model="options.{{ $index }}.content"
                            placeholder="Isi item yang akan diurutkan..."
                            class="w-full" />

                        {{-- Media Upload (Compact) --}}
                        <div class="flex items-center gap-2">
                            @if(isset($option['new_media']) && is_object($option['new_media']))
                            <div class="relative inline-block group h-10 w-10">
                                <img src="{{ $option['new_media']->temporaryUrl() }}" class="h-full w-full object-cover rounded border" />
                                <button type="button" class="absolute -top-1 -right-1 btn btn-circle btn-xs btn-error h-4 w-4 min-h-0" wire:click="deleteOptionMedia({{ $index }})">
                                    <x-mary-icon name="o-x-mark" class="w-2 h-2" />
                                </button>
                            </div>
                            @elseif(isset($option['media_url']) && $option['media_url'])
                            <div class="relative inline-block group h-10 w-10">
                                <img src="{{ $option['media_url'] }}" class="h-full w-full object-cover rounded border" />
                                <button type="button" class="absolute -top-1 -right-1 btn btn-circle btn-xs btn-error h-4 w-4 min-h-0" wire:click="deleteOptionMedia({{ $index }})">
                                    <x-mary-icon name="o-x-mark" class="w-2 h-2" />
                                </button>
                            </div>
                            @else
                            <label class="cursor-pointer text-xs text-primary hover:underline flex items-center gap-1">
                                <x-mary-icon name="o-photo" class="w-4 h-4" />
                                <span>Gambar</span>
                                <input type="file" class="hidden" wire:model="options.{{ $index }}.new_media" accept="image/*" />
                            </label>
                            @endif
                        </div>
                    </div>

                    {{-- Delete Button --}}
                    <x-mary-button
                        icon="o-trash"
                        class="btn-sm btn-circle btn-ghost text-error"
                        wire:click="removeOption({{ $index }})" />
                </div>
                @endforeach

                @if(empty($options))
                <div class="text-center py-8 text-gray-500 italic bg-base-50 rounded-lg border border-dashed">
                    Belum ada item. Klik "Tambah Item" untuk memulai.
                </div>
                @endif
            </div>

            {{-- SortableJS CDN --}}
            <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
        </div>
    </div>
</div>