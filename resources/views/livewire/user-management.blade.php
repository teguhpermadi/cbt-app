<div class="space-y-6">

    <x-heading>Kelola Pengguna Aplikasi</x-heading>
    <x-text>Daftar lengkap pengguna (Admin, Guru, Siswa, Orang Tua) dan manajemen dasar.</x-text>

    <div class="flex flex-col md:flex-row gap-4 items-start md:items-center">
        <div class="flex-grow">
            <x-ui.input type="search" wire:model.live="search" placeholder="Cari nama, email, atau username..." />
        </div>

        <div class="flex-shrink-0 flex items-center gap-3">
            <x-ui.button :disabled="count($selectedUsers) === 0" wire:click="confirmDelete">
                Hapus Massal ({{ count($selectedUsers) }})
            </x-ui.button>
            <x-ui.button>
                Tambah Baru
            </x-ui.button>
        </div>
    </div>

    <x-ui.tabs>
        <x-ui.tab.group>
            @foreach(['all' => 'Semua', 'admin' => 'Admin', 'teacher' => 'Guru', 'student' => 'Siswa', 'parent' => 'Orang Tua'] as $tabValue => $tabLabel)
                <x-ui.tab :label="$tabLabel" :active="$activeTab === $tabValue" wire:click="$set('activeTab', '{{ $tabValue }}')" />
            @endforeach
        </x-ui.tab.group>
    </x-ui.tabs>

    <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left">
                        <x-checkbox wire:model.live="selectAll" />
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Username / Email</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Tipe</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($users as $user)
                    <tr wire:key="user-{{ $user->id }}" class="hover:bg-gray-50 transition duration-150">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <x-checkbox wire:model.live="selectedUsers" value="{{ $user->id }}" />
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                            <div class="text-xs text-gray-500 sm:hidden">{{ $user->email }}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap hidden sm:table-cell">
                             <div class="text-sm text-gray-900">{{ $user->username }}</div>
                            <div class="text-xs text-gray-500">{{ $user->email }}</div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-center hidden md:table-cell">
                            <x-ui.badge variant="secondary">{{ ucfirst($user->user_type) }}</x-ui.badge>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <x-ui.dropdown>
                                <x-slot:button class="justify-center">
                                    <x-ui.button>
                                        Actions
                                    </x-ui.button>
                                </x-slot:button>
                                <x-slot:menu>
                                    <x-ui.dropdown.item>Edit</x-ui.dropdown.item>
                                    <x-ui.dropdown.item wire:click="confirmDelete('{{ $user->id }}')">Hapus</x-ui.dropdown.item>
                                </x-slot:menu>
                            </x-ui.dropdown>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                            Tidak ada data pengguna untuk tipe "{{ $activeTab === 'all' ? 'Semua' : ucfirst($activeTab) }}" yang ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pt-4">
        {{ $users->links() }} 
    </div>

    <x-modal title="Konfirmasi Hapus Pengguna" :show="$showDeleteModal" wire:key="delete-modal">
        <p class="text-gray-700">
            Apakah Anda yakin ingin menghapus 
            @if($userToDelete)
                pengguna ini? Aksi ini tidak dapat dibatalkan.
            @else
                **{{ count($selectedUsers) }}** pengguna yang dipilih? Aksi ini tidak dapat dibatalkan.
            @endif
        </p>
        
        <div class="mt-6 flex justify-end gap-3">
            <x-ui.button wire:click="$set('showDeleteModal', false)">Batal</x-ui.button>
            <x-ui.button wire:click="deleteUsers">
                <x-icon name="trash" class="w-4 h-4 mr-2" />
                Ya, Hapus Sekarang
            </x-ui.button>
        </div>
    </x-modal>

</div>