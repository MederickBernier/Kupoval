<?php

namespace App\Livewire;

use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * Class UpdateAddress
 *
 * This Livewire component handles the updating and creation of user addresses.
 *
 * @property string $type The type of address (e.g., home, work).
 * @property int|null $addressId The ID of the address being edited, or null if creating a new address.
 * @property string $address The address line.
 * @property string $city The city of the address.
 * @property string $state The state of the address.
 * @property string $country The country of the address.
 * @property string $zipcode The zipcode of the address.
 * @property bool $isEditing Flag to indicate if the form is in editing mode.
 *
 * @method void mount(string $type, int|null $addressId = null) Initializes the component with the given address type and optional address ID.
 * @method void edit() Sets the component to editing mode.
 * @method void save() Saves the address to the database, either creating a new address or updating an existing one.
 * @method \Illuminate\View\View render() Renders the Livewire component view.
 */
class UpdateAddress extends Component
{
    public $type;
    public $addressId;
    public $address = '';
    public $city = '';
    public $state = '';
    public $country = '';
    public $zipcode = '';
    public $isEditing = false;

    public function mount($type, $addressId = null)
    {
        $this->type = $type;
        $this->addressId = $addressId;

        if ($addressId) {
            $address = Address::where('id', $addressId)
                ->where('type', $type)
                ->whereHas('userProfile', function ($query) {
                    $query->where('user_id', Auth::id());
                })
                ->first();

            if ($address) {
                $this->address = $address->address;
                $this->city = $address->city;
                $this->state = $address->state;
                $this->country = $address->country;
                $this->zipcode = $address->zipcode;
            }
        } else {
            $this->isEditing = true;
        }
    }

    public function edit()
    {
        $this->isEditing = true;
    }

    public function save()
    {
        $userProfile = Auth::user()->profile;

        if (!$userProfile) {
            session()->flash('error', __('public/profile.profile_not_found'));
            return;
        }

        if ($this->addressId) {
            Address::where('id', $this->addressId)
                ->where('type', $this->type)
                ->where('user_profile_id', $userProfile->id)
                ->update([
                    'address' => $this->address,
                    'city' => $this->city,
                    'state' => $this->state,
                    'country' => $this->country,
                    'zipcode' => $this->zipcode,
                ]);
        } else {
            $address = Address::create([
                'user_profile_id' => $userProfile->id,
                'type' => $this->type,
                'address' => $this->address,
                'city' => $this->city,
                'state' => $this->state,
                'country' => $this->country,
                'zipcode' => $this->zipcode,
            ]);

            $this->addressId = $address->id;
        }

        $this->isEditing = false;
        session()->flash('message', __('public/profile.address_updated'));
    }

    public function render()
    {
        return view('livewire.update-address');
    }
}
