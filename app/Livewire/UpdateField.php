<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

/**
 * Class UpdateField
 *
 * This Livewire component handles the updating of a user's profile field.
 *
 * @property string $field The profile field to be updated.
 * @property string $value The new value for the profile field.
 * @property bool $isEditing Indicates if the field is currently being edited.
 *
 * @method void mount(string $field, string $value = '') Initializes the component with the given field and value.
 * @method void edit() Sets the component to editing mode.
 * @method void save() Saves the updated field value to the user's profile.
 * @method \Illuminate\View\View render() Renders the Livewire component view.
 */
class UpdateField extends Component
{
    public $field;
    public $value;
    public $isEditing = false;

    public function mount($field, $value = ''){
        $this->field = $field;
        $this->value = $value;
    }

    public function edit(){
        $this->isEditing = true;
    }

    public function save(){
        $user = Auth::user()->profile;

        if($user){
            $user->update([$this->field => $this->value]);
            $this->isEditing = false;
        }
    }

    public function render()
    {
        return view('livewire.update-field');
    }
}
