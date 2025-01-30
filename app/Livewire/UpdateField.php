<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

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
