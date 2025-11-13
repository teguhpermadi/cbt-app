<?php

namespace App\Livewire\UserManagement;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserTable extends Component
{
    use WithPagination;
    
    // Properti baru untuk single delete
    public ?string $deletingUserId = null;

    protected $listeners = ['userSaved' => 'render']; // Refresh tabel setelah event

    // Properti untuk Tab navigasi
    public string $activeTab = 'teacher'; // Default tab

    // Properti untuk Search dan Bulk Action
    public string $search = '';
    public array $selectedUsers = [];

    protected $queryString = ['activeTab' => ['except' => 'teacher']];

    // Method untuk mengambil data
    public function getUsers()
    {
        return User::query()
            ->where('user_type', $this->activeTab)
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('username', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->with('roles') // Relasi untuk Spatie Role/Permission
            ->paginate(10);
    }

    // Ganti Tab
    public function setTab(string $tab)
    {
        $this->activeTab = $tab;
        $this->resetPage(); // Reset halaman ketika tab berubah
        $this->selectedUsers = []; // Reset pilihan
    }

    // Method baru: Mengatur ID pengguna yang akan dihapus (untuk konfirmasi modal)
    public function confirmUserDeletion(string $userId)
    {
        $this->deletingUserId = $userId;
        // Dispatch event untuk membuka modal konfirmasi
        $this->dispatch('open-modal', 'confirm-user-deletion');
    }
    
    // Method baru: Menghapus pengguna tunggal
    public function deleteUser()
    {
        if ($this->deletingUserId) {
            $user = User::find($this->deletingUserId);
            if ($user) {
                $user->delete();
                // Hapus juga dari array selectedUsers jika ada
                $this->selectedUsers = array_diff($this->selectedUsers, [$this->deletingUserId]); 
                session()->flash('success', 'Pengguna ' . $user->name . ' berhasil dihapus.');
            }
        }
        $this->deletingUserId = null;
        $this->dispatch('close-modal', 'confirm-user-deletion');
        $this->render(); // Refresh tabel
    }

    // Method Bulk Delete (Diperbarui untuk menggunakan modal)
    public function confirmBulkDeletion()
    {
        if (count($this->selectedUsers) > 0) {
            // Dispatch event untuk membuka modal konfirmasi bulk
            $this->dispatch('open-modal', 'confirm-bulk-deletion');
        }
    }

    // Method Bulk Delete (Aksi Penghapusan)
    public function bulkDelete()
    {
        $count = count($this->selectedUsers);
        if ($count > 0) {
            User::destroy($this->selectedUsers);
            $this->selectedUsers = [];
            session()->flash('success', "{$count} pengguna berhasil dihapus secara massal.");
        }
        $this->dispatch('close-modal', 'confirm-bulk-deletion');
        $this->render(); // Refresh tabel
    }

    public function render()
    {
        // Sesuaikan nama role
        $tabs = ['admin' => 'Admin', 'teacher' => 'Guru', 'student' => 'Siswa', 'parent' => 'Orang Tua'];

        return view('livewire.user-management.user-table', [
            'users' => $this->getUsers(),
            'tabs' => $tabs,
        ]);
    }
}