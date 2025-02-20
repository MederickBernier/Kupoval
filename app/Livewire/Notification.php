<?php

namespace App\Livewire;

use Livewire\Component;

class Notification extends Component
{
    public $message;
    public $type;
    public $show = false;

    protected $listeners = ['flashMessage'];

    public function flashMessage($type, $message)
    {
        $this->message = $message;
        $this->type = $type;
        $this->show = true;

        $this->dispatch('auto-hide');
    }

    public function close()
    {
        $this->show = false;
    }

    public function render()
    {
        return view('livewire.notification');
    }
}
