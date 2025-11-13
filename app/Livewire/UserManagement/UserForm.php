<?php

namespace App\Livewire\UserManagement;

use App\Models\User;
use Livewire\Component;

class UserForm extends Component
{
    // Properti untuk Modal dan Data
    public bool $isOpen = false;
    public ?User $user = null;
    public array $state = [];
    public string $currentType = 'teacher'; // Tipe default

    protected $listeners = ['createUser', 'editUser'];

    // Aturan validasi
    protected function rules()
    {
        $rules = [
            'state.name' => 'required|string|max:255',
            'state.username' => ['required', 'string', 'max:255', 'unique:users,username,' . optional($this->user)->id],
            'state.email' => ['required', 'email', 'max:255', 'unique:users,email,' . optional($this->user)->id],
            'state.password' => [optional($this->user)->id ? 'nullable' : 'required', 'string', 'min:8'],
            'state.user_type' => 'required|in:admin,teacher,student,parent', // Pastikan tipe valid
        ];

        // Tambahkan aturan wajib jika tipe adalah 'student'
        if ($this->currentType === 'student') {
            $rules['state.nisn'] = 'required|string|max:50';
            $rules['state.nis'] = 'required|string|max:50';
            $rules['state.tempat_lahir'] = 'required|string|max:255';
            $rules['state.tanggal_lahir'] = 'required|date';
        }
        return $rules;
    }

    // --- Listeners/Actions ---

    // Dipanggil dari UserTable untuk mode Create
    public function createUser(string $type)
    {
        $this->reset(['user', 'state']);
        $this->currentType = $type;
        $this->state['user_type'] = $type;
        $this->isOpen = true;
    }

    // Dipanggil dari UserTable untuk mode Edit
    public function editUser(string $userId)
    {
        $this->user = User::findOrFail($userId);
        $this->currentType = $this->user->user_type;
        // Load data user ke state, termasuk data student jika ada
        $this->state = $this->user->toArray();
        $this->state['password'] = ''; // Jangan tampilkan password
        $this->isOpen = true;
    }

    // Aksi Simpan
    public function save()
    {
        $this->validate();

        $data = $this->state;

        // Hash password jika diisi atau saat mode create
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        if ($this->user) {
            // Update
            $this->user->update($data);
            $message = 'Pengguna berhasil diperbarui!';
        } else {
            // Create
            $user = User::create($data);
            // Tambahkan role default sesuai user_type menggunakan Spatie
            $user->assignRole($user->user_type);
            $message = 'Pengguna baru berhasil ditambahkan!';
        }

        $this->isOpen = false;
        // Kirim event ke UserTable untuk refresh data
        $this->dispatch('userSaved')->to(UserTable::class);
        session()->flash('success', $message);
    }

    public function render()
    {
        return view('livewire.user-management.user-form');
    }
}