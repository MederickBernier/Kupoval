<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

/**
 * Class UpdateTitle
 *
 * This Livewire component handles the updating of a user's title.
 *
 * @property string $title The current title of the user.
 * @property array $titles The list of valid titles.
 * @property bool $isEditing Flag to determine if the user is in editing mode.
 *
 * @method void mount() Initializes the component and sets the user's title.
 * @method void edit() Enables the editing mode.
 * @method void save() Saves the updated title to the user's profile.
 * @method \Illuminate\View\View render() Renders the Livewire component view.
 */
class UpdateTitle extends Component
{
    public $title;
    public $titles = ['Mr', 'Mrs', 'Miss', 'Dr', 'Prof'];
    public $isEditing = false;

    public function mount()
    {
        $user = Auth::user();

        if (!$user->profile) {
            $user->profile()->create(['title' => '']);
        }

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
