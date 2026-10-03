<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UserManager extends Component
{
    public $users;

    public $name;

    public $email;

    public $password;

    public $selectedUserId = null;

    public $isEditMode = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
    ];

    public function mount()
    {
        $this->loadUsers();
    }

    public function loadUsers()
    {
        $this->users = User::all();
    }

    public function saveUser()
    {
        $rules = $this->rules;

        if ($this->isEditMode) {
            $rules['email'] = 'required|email|max:255|unique:users,email,'.$this->selectedUserId;
            if ($this->password) {
                $rules['password'] = 'min:6';
            }
        } else {
            $rules['email'] = 'required|email|max:255|unique:users,email';
            $rules['password'] = 'required|min:6';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->isEditMode) {
            User::find($this->selectedUserId)->update($data);
            session()->flash('message', 'Pengguna berhasil diperbarui.');
        } else {
            User::create($data);
            session()->flash('message', 'Pengguna berhasil ditambahkan.');
        }

        $this->resetInputFields();
        $this->loadUsers();
    }

    public function editUser($id)
    {
        $user = User::find($id);
        $this->selectedUserId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->isEditMode = true;
    }

    public function deleteUser($id)
    {
        if (User::count() > 1) {
            User::find($id)->delete();
            session()->flash('message', 'Pengguna berhasil dihapus.');
            $this->loadUsers();
        } else {
            session()->flash('error', 'Gagal menghapus! Aplikasi minimal membutuhkan 1 pengguna.');
        }
    }

    public function resetInputFields()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->selectedUserId = null;
        $this->isEditMode = false;
    }

    public function render()
    {
        return view('livewire.user-manager')->layout('layouts.app');
    }
}
