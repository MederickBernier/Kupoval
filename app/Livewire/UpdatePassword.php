<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

/**
 * Class UpdatePassword
 *
 * This Livewire component handles the password update functionality.
 *
 * Properties:
 * @property string $current_password The current password of the user.
 * @property string $new_password The new password to be set.
 * @property string $new_password_confirmation Confirmation of the new password.
 * @property bool $showModal Flag to control the visibility of the modal.
 *
 * Methods:
 * @method void openModal() Opens the modal and resets the password fields.
 * @method void closeModal() Closes the modal.
 * @method void updatePassword() Validates and updates the user's password.
 * @method \Illuminate\View\View render() Renders the Livewire component view.
 */
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
