<div class="w-full">
    {{-- Toast Notification --}}
    <div x-data="{
        show: false,
        message: '',
        isSuccess: true,
        init() {
            Livewire.on('user-deleted', ({ name }) => {
                this.message = `Pengguna '${name}' berhasil dihapus.`;
                this.isSuccess = true;
                this.show = true;
                setTimeout(() => this.show = false, 3000);
            });
            Livewire.on('users-deleted', ({ count }) => {
                this.message = `${count} pengguna berhasil dihapus.`;
                this.isSuccess = true;
                this.show = true;
                setTimeout(() => this.show = false, 3000);
            });
        }
    }" x-show="show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform translate-y-2"
        class="fixed bottom-4 right-4 z-50 rounded-md px-4 py-3 shadow-lg"
        :class="isSuccess ? 'bg-green-500 text-white' : 'bg-red-500 text-white'">
        <p x-text="message"></p>
    </div>


    {{-- Main Card --}}
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-4">
            {{-- Tabs --}}
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-4" aria-label="Tabs">
                    @foreach ($tabs as $key => $label)
                        <button wire:click="setTab('{{ $key }}')"
                            class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200 @if ($activeTab === $key) border-indigo-500 text-indigo-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif">
                            {{ $label }}
                        </button>
                    @endforeach
                </nav>
            </div>

            {{-- Toolbar --}}
            <div class="flex items-center justify-between space-x-2 my-4">
                <div class="flex space-x-2">
                    <x-ui.button variant="primary" wire:click="$dispatch('createUser', { type: '{{ $activeTab }}' })">
                        <x-ui.icon name="plus" class="w-4 h-4 mr-1" />
                        Tambah {{ $tabs[$activeTab] }}
                    </x-ui.button>

                    @if (count($selectedUsers) > 0)
                        <x-ui.button wire:click="confirmBulkDeletion" variant="destructive">
                            <x-ui.icon name="trash" class="w-4 h-4 mr-1" />
                            Hapus ({{ count($selectedUsers) }})
                        </x-ui.button>
                    @endif
                </div>
                <div class="w-full md:w-1/3">
                    <x-ui.input wire:model.live.debounce.300ms="search" type="text"
                        placeholder="Cari nama, username, atau email..." class="w-full" />
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <x-ui.table>
                <x-ui.table.header>
                    <x-ui.table.row>
                        <x-ui.table.head class="w-10 px-4">
                            <x-ui.checkbox wire:model.live="selectAll"
                                onchange="Livewire.dispatch('toggleSelectAll', {checked: this.checked, ids: @js($users->pluck('id')->toArray())})" />
                        </x-ui.table.head>
                        <x-ui.table.head>Pengguna</x-ui.table.head>
                        <x-ui.table.head class="hidden md:table-cell">Role</x-ui.table.head>
                        <x-ui.table.head class="text-right">Aksi</x-ui.table.head>
                    </x-ui.table.row>
                </x-ui.table.header>
                <x-ui.table.body>
                    @forelse ($users as $user)
                        <x-ui.table.row wire:key="{{ $user->id }}" class="hover:bg-gray-50">
                            <x-ui.table.cell class="px-4">
                                <x-ui.checkbox wire:model.live="selectedUsers" value="{{ $user->id }}" />
                            </x-ui.table.cell>
                            <x-ui.table.cell>
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        @if ($user->getFirstMediaUrl('avatar'))
                                            <img class="h-10 w-10 rounded-full object-cover"
                                                src="{{ $user->getFirstMediaUrl('avatar') }}" alt="{{ $user->name }}">
                                        @else
                                            <span
                                                class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-500">
                                                {{ $user->initials() }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </x-ui.table.cell>
                            <x-ui.table.cell class="hidden md:table-cell">
                                @if ($user->roles->isNotEmpty())
                                    @foreach ($user->roles as $role)
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        No Role
                                    </span>
                                @endif
                            </x-ui.table.cell>
                            <x-ui.table.cell class="text-right px-4">
                                <div class="flex justify-end items-center space-x-1">
                                    <x-ui.button.icon variant="ghost"
                                        wire:click="$dispatch('editUser', { userId: '{{ $user->id }}' })">
                                        <x-ui.icon name="pencil" class="w-4 h-4" />
                                    </x-ui.button.icon>
                                    <x-ui.button.icon variant="ghost" color="destructive"
                                        wire:click="confirmUserDeletion('{{ $user->id }}')">
                                        <x-ui.icon name="trash" class="w-4 h-4" />
                                    </x-ui.button.icon>
                                </div>
                            </x-ui.table.cell>
                        </x-ui.table.row>
                    @empty
                        <x-ui.table.row>
                            <x-ui.table.cell colspan="4" class="text-center py-8 text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <x-ui.icon name="user-group" class="w-12 h-12 text-gray-400 mb-2" />
                                    <p class="font-semibold">Tidak ada data {{ strtolower($tabs[$activeTab]) }}</p>
                                    <p class="text-sm">Coba ubah filter pencarian Anda atau tambahkan pengguna baru.</p>
                                </div>
                            </x-ui.table.cell>
                        </x-ui.table.row>
                    @endforelse
                </x-ui.table.body>
            </x-ui.table>
        </div>

        {{-- Pagination --}}
        @if ($users->hasPages())
            <div class="p-4 border-t border-gray-200">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    {{-- Modals --}}
    <x-ui.modal name="confirm-user-deletion" title="Konfirmasi Hapus Pengguna"
        message="Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan."
        confirm-text="Ya, Hapus" cancel-text="Batal" wire:confirm="deleteUser" />

    <x-ui.modal name="confirm-bulk-deletion" title="Konfirmasi Hapus Massal"
        :message="'Anda akan menghapus ' . count($selectedUsers) . ' pengguna terpilih. Apakah Anda yakin?'" confirm-text="Ya, Hapus Semua"
        cancel-text="Batal" wire:confirm="bulkDelete" />

</div>

@script
    <script>
        Livewire.on('toggleSelectAll', ({ checked, ids }) => {
            let selected = @this.get('selectedUsers');
            if (checked) {
                ids.forEach(id => {
                    if (!selected.includes(id)) {
                        selected.push(id);
                    }
                });
            } else {
                ids.forEach(id => {
                    let index = selected.indexOf(id);
                    if (index > -1) {
                        selected.splice(index, 1);
                    }
                });
            }
            @this.set('selectedUsers', selected);
        });

        Livewire.on('user-saved', () => {
            // Optionally close a form modal if you have one
            // $dispatch('close-modal', 'user-form-modal');
        });
    </script>
@endscript
