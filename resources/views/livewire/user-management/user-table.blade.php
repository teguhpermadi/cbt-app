<div>
    <div class="border-b border-gray-200">
        {{-- Tabs --}}
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            @foreach ($tabs as $key => $label)
                <button wire:click="setTab('{{ $key }}')"
                    class="@if ($activeTab === $key) border-indigo-500 text-indigo-600 @else border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    {{ $label }}
                </button>
            @endforeach
        </nav>
    </div>

    <div class="mt-4">
        {{-- Toolbar: Tombol Aksi & Pencarian --}}
        <div class="flex items-center justify-between space-x-2 mb-4">
            <div class="flex space-x-2">
                
                {{-- Tombol Tambah --}}
                <x-ui.button variant="primary" wire:click="$dispatch('createUser', { type: '{{ $activeTab }}' })">
                    <x-ui.icon name="plus" class="w-4 h-4 mr-1" />
                    Tambah {{ $tabs[$activeTab] }}
                </x-ui.button>

                {{-- Komponen ini Dihapus/Dipotong karena tidak memiliki struktur tabel yang benar dan terulang di bawah --}}
                
                @if (count($selectedUsers) > 0)
                    {{-- Tombol Hapus Massal --}}
                    <x-ui.button wire:click="bulkDelete" variant="destructive" x-data
                        x-on:click.prevent="$dispatch('open-modal', 'confirm-bulk-delete')">
                        <x-ui.icon name="trash" class="w-4 h-4 mr-1" />
                        Hapus ({{ count($selectedUsers) }})
                    </x-ui.button>
                @endif

            </div>

            {{-- Input Pencarian --}}
            <x-ui.input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari pengguna..."
                class="max-w-xs" />
        </div>

        {{-- Struktur Tabel Inti --}}
        <div x-data="{ selectAll: @entangle('selectAll').live }">
            
            {{-- Wrapper Komponen Tabel Kustom --}}
            <x-ui.table> 
                
                {{-- Table Header --}}
                <x-ui.table.header>
                    <x-ui.table.row>
                        <x-ui.table.head class="w-10">
                            <input type="checkbox" x-model="selectAll"
                                x-on:click="$wire.set('selectedUsers', selectAll ? @js($users->pluck('id')->toArray()) : [])" />
                        </x-ui.table.head>
                        {{-- Tambahkan kolom Judul lainnya di sini --}}
                        <x-ui.table.head>Nama</x-ui.table.head>
                        <x-ui.table.head>Username</x-ui.table.head>
                        <x-ui.table.head>Email</x-ui.table.head>
                        <x-ui.table.head class="text-right">Aksi</x-ui.table.head>
                    </x-ui.table.row>
                </x-ui.table.header>

                {{-- Table Body --}}
                <x-ui.table.body>
                    @forelse ($users as $user)
                        <x-ui.table.row wire:key="{{ $user->id }}">
                            {{-- Checkbox --}}
                            <x-ui.table.cell class="w-10">
                                <input type="checkbox" wire:model.live="selectedUsers" value="{{ $user->id }}" />
                            </x-ui.table.cell>
                            {{-- Data Pengguna --}}
                            <x-ui.table.cell>{{ $user->name }}</x-ui.table.cell>
                            <x-ui.table.cell>{{ $user->username }}</x-ui.table.cell>
                            <x-ui.table.cell>{{ $user->email }}</x-ui.table.cell>
                            
                            {{-- Kolom Aksi --}}
                            <x-ui.table.cell class="text-right">
                                <x-ui.button variant="ghost" size="sm"
                                    wire:click="$dispatch('editUser', { userId: '{{ $user->id }}' })">
                                    Edit
                                </x-ui.button>
                                <x-ui.button variant="ghost" size="sm"
                                    wire:click="confirmUserDeletion('{{ $user->id }}')" variant="destructive">
                                    Delete
                                </x-ui.button>
                            </x-ui.table.cell>
                        </x-ui.table.row>
                    @empty
                        {{-- Baris kosong --}}
                        <x-ui.table.row>
                            <x-ui.table.cell colspan="5" class="text-center py-4">
                                Tidak ada data {{ $tabs[$activeTab] }}.
                            </x-ui.table.cell>
                        </x-ui.table.row>
                    @endforelse
                </x-ui.table.body>
                
            </x-ui.table>

            {{-- <livewire:user-management.user-form /> --}}
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus Pengguna Tunggal --}}
    {{-- <x-ui.modal name="confirm-user-deletion" focusable wire:model="deletingUserId">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Konfirmasi Penghapusan Pengguna
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="mt-6 flex justify-end">
                <x-ui.button variant="secondary"
                    x-on:click="$wire.set('deletingUserId', null); $dispatch('close-modal', 'confirm-user-deletion')">
                    Batal
                </x-ui.button>
                <x-ui.button variant="destructive" class="ml-3" wire:click="deleteUser">
                    Hapus
                </x-ui.button>
            </div>
        </div>
    </x-ui.modal> --}}


    {{-- Modal Konfirmasi Hapus Massal --}}
    {{-- <x-ui.modal name="confirm-bulk-deletion" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Konfirmasi Hapus Massal ({{ count($selectedUsers) }} Pengguna)
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                Anda akan menghapus **{{ count($selectedUsers) }}** pengguna terpilih. Apakah Anda yakin?
            </p>
            <div class="mt-6 flex justify-end">
                <x-ui.button variant="secondary" x-on:click="$dispatch('close-modal', 'confirm-bulk-deletion')">
                    Batal
                </x-ui.button>
                <x-ui.button variant="destructive" class="ml-3" wire:click="bulkDelete">
                    Hapus Sekarang
                </x-ui.button>
            </div>
        </div>
    </x-ui.modal> --}}
</div>