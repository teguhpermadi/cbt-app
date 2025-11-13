<div>
    <div class="border-b border-gray-200">
        <nav class="-mb-px-ui. flex-ui. space-x-ui.8" aria-label="Tabs">
            @foreach ($tabs as $key => $label)
                <button wire:click="setTab('{{ $key }}')"
                    class="@if ($activeTab === $key) border-indigo-500 tex-ui.t-indigo-600 @else border-transparent tex-ui.t-gray-500 hover:tex-ui.t-gray-700 hover:border-gray-300 @endif whitespace-nowrap py-4 px-ui.1 border-b-2 font-medium tex-ui.t-sm">
                    {{ $label }}
                </button>
            @endforeach
        </nav>
    </div>

    <div class="mt-4">
        <div class="flex-ui. items-center justify-between space-x-ui.2 mb-4">
            <div class="flex-ui. space-x-ui.2">
                <div>
                    <x-ui.button variant="primary" wire:click="$dispatch('createUser', { type: '{{ $activeTab }}' })">
                        <x-ui.icon name="plus" class="w-4 h-4 mr-1" />
                        Tambah {{ $tabs[$activeTab] }}
                    </x-ui.button>

                    @forelse ($users as $user)
                        <x-ui.table-row wire:key="{{ $user->id }}">
                            <x-ui.table-cell class="tex-ui.t-right">
                                <x-ui.button variant="ghost" size="sm"
                                    wire:click="$dispatch('editUser', { userId: '{{ $user->id }}' })">
                                    Edit
                                </x-ui.button>
                                <x-ui.button variant="ghost" size="sm">Delete</x-ui.button>
                            </x-ui.table-cell>
                        </x-ui.table-row>
                    @empty
                    @endforelse

                    <livewire:user-management.user-form />
                </div>

                @if (count($selectedUsers) > 0)
                    <x-ui.button wire:click="bulkDelete" variant="destructive" x-ui.data
                        x-ui.on:click.prevent="$dispatch('open-modal', 'confirm-bulk-delete')">
                        <x-ui.icon name="trash" class="w-4 h-4 mr-1" />
                        Hapus ({{ count($selectedUsers) }})
                    </x-ui.button>
                @endif

                <x-ui.dropdown>
                    <x-ui.dropdown-trigger as-child>
                        <x-ui.button variant="outline">
                            Aksi Lain
                            <x-ui.icon name="chevron-down" class="w-4 h-4 ml-1" />
                        </x-ui.button>
                    </x-ui.dropdown-trigger>
                    <x-ui.dropdown-content>
                        <x-ui.dropdown-item wire:click="$dispatch('open-modal', 'import-modal')">Import
                            Data</x-ui.dropdown-item>
                        <x-ui.dropdown-item wire:click="ex-ui.portData">Ex-ui.port Data</x-ui.dropdown-item>
                        <x-ui.separator />
                        <x-ui.dropdown-item wire:click="$dispatch('open-modal', 'manage-role-modal')">Kelola
                            Role</x-ui.dropdown-item>
                    </x-ui.dropdown-content>
                </x-ui.dropdown>
            </div>

            <x-ui.input wire:model.live.debounce.300ms="search" type="tex-ui.t" placeholder="Cari pengguna..."
                class="max-ui.w-x-ui.s" />
        </div>

        <div x-ui.data="{ selectAll: @entangle('selectAll').live }">
            <div class="flex-ui. items-center justify-between space-x-ui.2 mb-4">
                <div class="flex-ui. space-x-ui.2">
                    @if (count($selectedUsers) > 0)
                        <x-ui.button wire:click="confirmBulkDeletion" variant="destructive">
                            <x-ui.icon name="trash" class="w-4 h-4 mr-1" />
                            Hapus ({{ count($selectedUsers) }})
                        </x-ui.button>
                    @endif
                </div>
            </div>

            <x-ui.table-header>
                <x-ui.table-row>
                    <x-ui.table-head class="w-10">
                        <input type="checkbox-ui." x-ui.model="selectAll"
                            x-ui.on:click="$wire.set('selectedUsers', selectAll ? @js($users->pluck('id')->toArray()) : [])" />
                    </x-ui.table-head>
                </x-ui.table-row>
            </x-ui.table-header>
            @forelse ($users as $user)
                <x-ui.table-row wire:key="{{ $user->id }}">
                    <x-ui.table-cell class="tex-ui.t-right">
                        <x-ui.button variant="ghost" size="sm"
                            wire:click="$dispatch('editUser', { userId: '{{ $user->id }}' })">
                            Edit
                        </x-ui.button>
                        <x-ui.button variant="ghost" size="sm"
                            wire:click="confirmUserDeletion('{{ $user->id }}')" variant="destructive">
                            Delete
                        </x-ui.button>
                    </x-ui.table-cell>
                </x-ui.table-row>
            @empty
            @endforelse
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>


    <x-ui.modal name="confirm-user-deletion" focusable wire:model="deletingUserId">
        <div class="p-6">
            <h2 class="tex-ui.t-lg font-medium tex-ui.t-gray-900">
                Konfirmasi Penghapusan Pengguna
            </h2>
            <p class="mt-1 tex-ui.t-sm tex-ui.t-gray-600">
                Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="mt-6 flex-ui. justify-end">
                <x-ui.button variant="secondary"
                    x-ui.on:click="$wire.set('deletingUserId', null); $dispatch('close-modal', 'confirm-user-deletion')">
                    Batal
                </x-ui.button>
                <x-ui.button variant="destructive" class="ml-3" wire:click="deleteUser">
                    Hapus
                </x-ui.button>
            </div>
        </div>
    </x-ui.modal>


    <x-ui.modal name="confirm-bulk-deletion" focusable>
        <div class="p-6">
            <h2 class="tex-ui.t-lg font-medium tex-ui.t-gray-900">
                Konfirmasi Hapus Massal ({{ count($selectedUsers) }} Pengguna)
            </h2>
            <p class="mt-1 tex-ui.t-sm tex-ui.t-gray-600">
                Anda akan menghapus **{{ count($selectedUsers) }}** pengguna terpilih. Apakah Anda yakin?
            </p>
            <div class="mt-6 flex-ui. justify-end">
                <x-ui.button variant="secondary" x-ui.on:click="$dispatch('close-modal', 'confirm-bulk-deletion')">
                    Batal
                </x-ui.button>
                <x-ui.button variant="destructive" class="ml-3" wire:click="bulkDelete">
                    Hapus Sekarang
                </x-ui.button>
            </div>
        </div>
    </x-ui.modal>
</div>
