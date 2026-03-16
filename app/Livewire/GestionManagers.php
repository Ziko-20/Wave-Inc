<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Livewire\Attributes\Layout;

  #[Layout('layouts.app')]
class GestionManagers extends Component
{  

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public bool $showForm = false;

    protected $rules = [
        'name'     => 'required|string|min:2',
        'email'    => 'required|email|unique:users,email',
        'password' => 'required|min:8',
    ];

    public function addManager(): void
    {
        $this->validate();

        $user = User::create([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => bcrypt($this->password),
        ]);

        $user->assignRole('manager');

        $this->reset(['name', 'email', 'password', 'showForm']);
        session()->flash('success', 'Manager ajouté avec succès.');
    }

    public function deleteManager(int $id): void
    {
        $user = User::findOrFail($id);

        abort_if($user->hasRole('admin'), 403, 'Impossible de supprimer un admin.');

        $user->delete();
        session()->flash('success', 'Manager supprimé.');
    }

    public function render()
    {
        return view('livewire.gestion-managers', [
            'managers' => User::role('manager')->orderBy('name')->get(),
        ]);
    }
}