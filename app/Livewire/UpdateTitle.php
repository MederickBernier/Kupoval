<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class UpdateTitle extends Component
{
    public $title;
    public $titles = ['Mr', 'Mrs', 'Miss', 'Dr', 'Prof'];
    public $isEditing = false;

    public function mount()
    {
        $user = Auth::user();

        // Ensure the profile exists and create if necessary
        if (!$user->profile) {
            $user->profile()->create(['title' => '']);
        }

        // Load the title only if it's valid
        $this->title = in_array(optional($user->profile)->title, $this->titles)
            ? $user->profile->title
            : '';
    }

    public function edit()
    {
        $this->isEditing = true;
    }

    public function save()
    {
        $userProfile = Auth::user()->profile;

        if ($userProfile && in_array($this->title, $this->titles)) {
            $userProfile->update(['title' => $this->title]);
            session()->flash('message', __('public/profile.title_updated'));
        } else {
            session()->flash('error', __('public/profile.invalid_title'));
        }

        $this->isEditing = false;
    }

    public function render()
    {
        return view('livewire.update-title');
    }
}
