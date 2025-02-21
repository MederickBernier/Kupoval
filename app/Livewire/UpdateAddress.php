<?php

namespace App\Livewire;

use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UpdateAddress extends Component
{
    public $type; // 'billing' or 'shipping'
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

        // Load address if it exists
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
            // If no address exists, go straight into edit mode
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
            // Update existing address
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
            // Create a new address if it doesn't exist
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
