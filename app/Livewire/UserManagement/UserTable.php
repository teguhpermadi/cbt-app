<?php

namespace App\Livewire\UserManagement;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserTable extends Component
{
    use WithPagination;

    public ?string $deletingUserId = null;
    public array $selectedUsers = [];
    public string $activeTab = 'teacher';
    public string $search = '';

    protected $listeners = ['userSaved' => '$refresh'];
    protected $queryString = ['activeTab' => ['except' => 'teacher'], 'search' => ['except' => '']];

    public function getUsers()
    {
        $query = User::query()
            ->where('user_type', $this->activeTab)
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('username', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->with('roles');

        return $query->paginate(10);
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetPage();
        $this->selectedUsers = [];
    }

    public function confirmUserDeletion(string $userId): void
    {
        $this->deletingUserId = $userId;
        $this->dispatch('open-modal', 'confirm-user-deletion');
    }

    public function deleteUser(): void
    {
        if ($this->deletingUserId) {
            $user = User::find($this->deletingUserId);
            if ($user) {
                $userName = $user->name;
                $user->delete();
                $this->selectedUsers = array_diff($this->selectedUsers, [$this->deletingUserId]);
                $this->dispatch('user-deleted', name: $userName);
            }
        }
        $this->deletingUserId = null;
        $this->dispatch('close-modal', 'confirm-user-deletion');
    }

    public function confirmBulkDeletion(): void
    {
        if (count($this->selectedUsers) > 0) {
            $this->dispatch('open-modal', 'confirm-bulk-deletion');
        }
    }

    public function bulkDelete(): void
    {
        $count = count($this->selectedUsers);
        if ($count > 0) {
            User::destroy($this->selectedUsers);
            $this->dispatch('users-deleted', count: $count);
            $this->selectedUsers = [];
        }
        $this->dispatch('close-modal', 'confirm-bulk-deletion');
    }

    public function render()
    {
        $tabs = [
            'admin' => 'Admin',
            'teacher' => 'Guru',
            'student' => 'Siswa',
            'parent' => 'Orang Tua'
        ];

        return view('livewire.user-management.user-table', [
            'users' => $this->getUsers(),
            'tabs' => $tabs,
        ]);
    }
}