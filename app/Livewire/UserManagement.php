<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    // Properti utama untuk filter dan state
    public $activeTab = 'all'; // Default: 'all', 'admin', 'teacher', 'student', 'parent'
    public $search = '';
    public $selectedUsers = []; // Array untuk Bulk Delete
    public $selectAll = false;
    public $showDeleteModal = false;
    public $userToDelete = null; // Untuk single delete

    // Mapping tab ke tipe pengguna yang valid
    protected $validUserTypes = ['admin', 'teacher', 'student', 'parent'];

    // Atur ulang pagination saat ada perubahan properti
    public function updatedActiveTab()
    {
        $this->resetPage();
        $this->selectedUsers = []; // Reset pilihan saat tab berubah
        $this->selectAll = false;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    // Toggle select all
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedUsers = $this->getUsersQuery()->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedUsers = [];
        }
    }

    // Query dasar untuk mengambil data user
    protected function getUsersQuery()
    {
        $query = User::query();

        // 1. Filter berdasarkan Active Tab
        if ($this->activeTab !== 'all' && in_array($this->activeTab, $this->validUserTypes)) {
            $query->where('user_type', $this->activeTab);
        }

        // 2. Filter berdasarkan Pencarian (Search)
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'ilike', '%' . $this->search . '%') // 'ilike' for case-insensitive in Postgres
                  ->orWhere('email', 'ilike', '%' . $this->search . '%');
            });
        }

        return $query->orderBy('name');
    }

    // Fungsionalitas Hapus (Single & Bulk)

    public function confirmDelete($userId = null)
    {
        // Untuk single delete
        if ($userId) {
            $this->userToDelete = $userId;
            $this->selectedUsers = [];
        } 
        // Untuk bulk delete
        else if (count($this->selectedUsers) > 0) {
            $this->userToDelete = null; 
        } else {
            // Tidak ada yang dipilih
            return; 
        }

        $this->showDeleteModal = true;
    }

    public function deleteUsers()
    {
        if ($this->userToDelete) {
            // Single delete
            User::destroy($this->userToDelete);
        } else if (count($this->selectedUsers) > 0) {
            // Bulk delete
            User::destroy($this->selectedUsers);
        } else {
            // Hanya untuk berjaga-jaga
            $this->showDeleteModal = false;
            return;
        }

        // Reset state
        $this->showDeleteModal = false;
        $this->userToDelete = null;
        $this->selectedUsers = [];
        $this->selectAll = false;
        $this->dispatch('toast', ['message' => 'Pengguna berhasil dihapus!', 'variant' => 'success']); // Menggunakan component Toast

        // Refresh halaman/view
        $this->render();
    }

    // Render view
    public function render()
    {
        $users = $this->getUsersQuery()->paginate(10); // 10 data per halaman
        
        return view('livewire.user-management', [
            'users' => $users,
        ]);
    }
}