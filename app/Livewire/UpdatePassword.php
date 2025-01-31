<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UpdatePassword extends Component
{
    public $current_password;
    public $new_password;
    public $new_password_confirmation;
    public $showModal = false;

    public function openModal(){
        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        $this->showModal = true;
    }

    public function closeModal(){
        $this->showModal = false;
    }

    public function updatePassword(){
        $this->validate([
            'current_password' => ['required', 'current_password:api'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($this->new_password)
        ]);

        session()->flash('success', 'Password updated successfully!');
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.update-password');
    }
}
