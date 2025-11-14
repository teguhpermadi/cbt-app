<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User; // Pastikan Anda mengimpor model User Anda
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    // Properti untuk state
    public string $user_type = 'student'; // Tab default
    public string $search = '';

    // Properti untuk Bulk Delete
    public array $selectedUsers = [];
    public bool $selectAll = false;

    // Properti untuk Modal Konfirmasi
    public bool $showDeleteModal = false;
    public bool $showBulkDeleteModal = false;
    public ?User $userToDelete = null;

    /**
     * Menyimpan state tab di URL untuk UX yang lebih baik.
     */
    protected $queryString = [
        'user_type' => ['except' => 'student'],
        'search' => ['except' => ''],
    ];

    /**
     * Dijalankan saat properti $user_type diubah.
     * Mereset paginasi dan seleksi.
     */
    public function updatingUserType(): void
    {
        $this->resetPage();
        $this->resetSelection();
    }

    /**
     * Dijalankan saat properti $search diubah.
     * Mereset paginasi.
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
        $this->resetSelection();
    }

    /**
     * Dijalankan saat berpindah halaman paginasi.
     * Mereset seleksi.
     */
    public function updatingPage(): void
    {
        $this->resetSelection();
    }

    /**
     * Dijalankan saat checkbox "Select All" diklik.
     */
    public function updatedSelectAll(bool $value): void
    {
        if ($value) {
            // Ambil semua ID pengguna di halaman saat ini dan masukkan ke $selectedUsers
            $this->selectedUsers = $this->users
                ->pluck('id')
                ->map(fn ($id) => (string) $id) // Konversi ke string agar konsisten dengan checkbox
                ->toArray();
        } else {
            $this->resetSelection();
        }
    }

    /**
     * Helper untuk mereset state seleksi.
     */
    public function resetSelection(): void
    {
        $this->selectedUsers = [];
        $this->selectAll = false;
    }

    /**
     * Mengubah tab user type yang aktif.
     */
    public function changeUserType(string $type): void
    {
        $this->user_type = $type;
    }

    /**
     * Menampilkan modal konfirmasi untuk HAPUS SATUAN.
     */
    public function confirmDelete(User $user): void
    {
        $this->userToDelete = $user;
        $this->showDeleteModal = true;
    }

    /**
     * Menjalankan proses HAPUS SATUAN.
     */
    public function delete(): void
    {
        if ($this->userToDelete) {
            $this->userToDelete->delete();
        }

        $this->showDeleteModal = false;
        $this->userToDelete = null;
        session()->flash('success', 'User deleted successfully.');
    }

    /**
     * Menampilkan modal konfirmasi untuk HAPUS MASSAL (BULK).
     */
    public function confirmBulkDelete(): void
    {
        $this->showBulkDeleteModal = true;
    }

    /**
     * Menjalankan proses HAPUS MASSAL (BULK).
     */
    public function deleteSelected(): void
    {
        // Hapus pengguna berdasarkan ID yang ada di $selectedUsers
        User::whereIn('id', $this->selectedUsers)->delete();

        $this->showBulkDeleteModal = false;
        $this->resetSelection();
        session()->flash('success', 'Selected users deleted successfully.');
    }

    /**
     * Mengambil data pengguna secara dinamis dengan paginasi.
     * Menggunakan Computed Property untuk caching.
     */
    public function getUsersProperty()
    {
        return User::query()
            ->where('user_type', $this->user_type)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('username', 'like', '%' . $this->search . '%');
                });
            })
            ->latest() // Urutkan berdasarkan yang terbaru
            ->paginate(10); // 10 data per halaman
    }

    /**
     * Render view komponen.
     */
    public function render()
    {
        return view('livewire.user-management', [
            'users' => $this->users, // Menggunakan computed property
        ]);
    }
}