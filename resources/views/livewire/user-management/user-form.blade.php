<x-modal wire:model="isOpen">
    <x-modal-header>
        <x-modal-title>
            {{ $user ? 'Edit Pengguna: ' . $user->name : 'Tambah Pengguna ' . ucfirst($currentType) }}
        </x-modal-title>
    </x-modal-header>

    <form wire:submit.prevent="save">
        <x-modal-body>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-ui.input label="Nama Lengkap" type="text" wire:model.defer="state.name" required />
                
                <x-ui.input label="Username" type="text" wire:model.defer="state.username" required />
                
                <x-ui.input label="Email" type="email" wire:model.defer="state.email" required />
                
                <x-ui.input label="Password" type="password" 
                    wire:model.defer="state.password" 
                    :required="!$user" 
                    :placeholder="$user ? 'Kosongkan jika tidak diubah' : ''" 
                />
            </div>

            @if($currentType === 'student')
                <div class="mt-6 border-t pt-4">
                    <h4 class="text-lg font-semibold mb-3">Data Siswa (Wajib)</h4>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <x-ui.input label="NISN" type="text" wire:model.defer="state.nisn" required />
                        <x-ui.input label="NIS" type="text" wire:model.defer="state.nis" required />
                        <x-ui.input label="Tempat Lahir" type="text" wire:model.defer="state.tempat_lahir" required />
                        <x-ui.input label="Tanggal Lahir" type="date" wire:model.defer="state.tanggal_lahir" required />
                    </div>
                </div>
            @endif
            
            <div class="mt-4">
                <x-label for="avatar">Avatar/Foto (Opsional)</x-label>
                <input type="file" id="avatar" wire:model="state.avatar" class="mt-1 block w-full" />
                @error('state.avatar') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

        </x-modal-body>

        <x-modal-footer>
            <x-button type="button" variant="outline" wire:click="$set('isOpen', false)">Batal</x-button>
            <x-button type="submit" variant="primary">
                <span wire:loading.remove>Simpan</span>
                <span wire:loading>Memproses...</span>
            </x-button>
        </x-modal-footer>
    </form>
</x-modal>