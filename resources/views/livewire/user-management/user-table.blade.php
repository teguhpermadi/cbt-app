<div>
    <div class="border-b border-gray-200">
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
        <div class="flex items-center justify-between space-x-2 mb-4">
            <div class="flex space-x-2">
                <div>
                    <x-button variant="primary" wire:click="$dispatch('createUser', { type: '{{ $activeTab }}' })">
                        <x-icon name="plus" class="w-4 h-4 mr-1" />
                        Tambah {{ $tabs[$activeTab] }}
                    </x-button>

                    @forelse ($users as $user)
                        <x-table-row wire:key="{{ $user->id }}">
                            <x-table-cell class="text-right">
                                <x-button variant="ghost" size="sm"
                                    wire:click="$dispatch('editUser', { userId: '{{ $user->id }}' })">
                                    Edit
                                </x-button>
                                <x-button variant="ghost" size="sm">Delete</x-button>
                            </x-table-cell>
                        </x-table-row>
                    @empty
                    @endforelse

                    <livewire:user-management.user-form />
                </div>

                @if (count($selectedUsers) > 0)
                    <x-button wire:click="bulkDelete" variant="destructive" x-data
                        x-on:click.prevent="$dispatch('open-modal', 'confirm-bulk-delete')">
                        <x-icon name="trash" class="w-4 h-4 mr-1" />
                        Hapus ({{ count($selectedUsers) }})
                    </x-button>
                @endif

                <x-dropdown>
                    <x-dropdown-trigger as-child>
                        <x-button variant="outline">
                            Aksi Lain
                            <x-icon name="chevron-down" class="w-4 h-4 ml-1" />
                        </x-button>
                    </x-dropdown-trigger>
                    <x-dropdown-content>
                        <x-dropdown-item wire:click="$dispatch('open-modal', 'import-modal')">Import
                            Data</x-dropdown-item>
                        <x-dropdown-item wire:click="exportData">Export Data</x-dropdown-item>
                        <x-dropdown-separator />
                        <x-dropdown-item wire:click="$dispatch('open-modal', 'manage-role-modal')">Kelola
                            Role</x-dropdown-item>
                    </x-dropdown-content>
                </x-dropdown>
            </div>

            <x-input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari pengguna..."
                class="max-w-xs" />
        </div>

        <div x-data="{ selectAll: @entangle('selectAll').live }">
            <div class="flex items-center justify-between space-x-2 mb-4">
                <div class="flex space-x-2">
                    @if (count($selectedUsers) > 0)
                        <x-button wire:click="confirmBulkDeletion" variant="destructive">
                            <x-icon name="trash" class="w-4 h-4 mr-1" />
                            Hapus ({{ count($selectedUsers) }})
                        </x-button>
                    @endif
                </div>
            </div>

            <x-table-header>
                <x-table-row>
                    <x-table-head class="w-10">
                        <input type="checkbox" x-model="selectAll"
                            x-on:click="$wire.set('selectedUsers', selectAll ? @js($users->pluck('id')->toArray()) : [])" />
                    </x-table-head>
                </x-table-row>
            </x-table-header>
            @forelse ($users as $user)
                <x-table-row wire:key="{{ $user->id }}">
                    <x-table-cell class="text-right">
                        <x-button variant="ghost" size="sm"
                            wire:click="$dispatch('editUser', { userId: '{{ $user->id }}' })">
                            Edit
                        </x-button>
                        <x-button variant="ghost" size="sm"
                            wire:click="confirmUserDeletion('{{ $user->id }}')" variant="destructive">
                            Delete
                        </x-button>
                    </x-table-cell>
                </x-table-row>
            @empty
            @endforelse
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>


    <x-modal name="confirm-user-deletion" focusable wire:model="deletingUserId">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Konfirmasi Penghapusan Pengguna
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="mt-6 flex justify-end">
                <x-button variant="secondary"
                    x-on:click="$wire.set('deletingUserId', null); $dispatch('close-modal', 'confirm-user-deletion')">
                    Batal
                </x-button>
                <x-button variant="destructive" class="ml-3" wire:click="deleteUser">
                    Hapus
                </x-button>
            </div>
        </div>
    </x-modal>


    <x-modal name="confirm-bulk-deletion" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Konfirmasi Hapus Massal ({{ count($selectedUsers) }} Pengguna)
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                Anda akan menghapus **{{ count($selectedUsers) }}** pengguna terpilih. Apakah Anda yakin?
            </p>
            <div class="mt-6 flex justify-end">
                <x-button variant="secondary" x-on:click="$dispatch('close-modal', 'confirm-bulk-deletion')">
                    Batal
                </x-button>
                <x-button variant="destructive" class="ml-3" wire:click="bulkDelete">
                    Hapus Sekarang
                </x-button>
            </div>
        </div>
    </x-modal>
</div>
