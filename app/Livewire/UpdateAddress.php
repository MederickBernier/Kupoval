<?php

namespace App\Livewire;

use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UpdateAddress extends Component
{

    public $addressId;
    public $type;
    public $address;
    public $city;
    public $state;
    public $country;
    public $zipcode;
    public $isEditing = false;

    public function mount($addressId, $type){
        $address = Address::where('id', $addressId)
            ->where('type', $type)
            ->whereHas('userProfile', function ($query) {
                $query->where('user_id', Auth::id());
            })->first();

        if ($address) {
            $this->addressId = $address->id;
            $this->type = $type;
            $this->address = $address->address;
            $this->city = $address->city;
            $this->state = $address->state;
            $this->country = $address->country;
            $this->zipcode = $address->zipcode;
        }
    }

    public function edit(){
        $this->isEditing = true;
    }

    public function save(){
        $address = Address::where('id', $this->addressId)
            ->where('type', $this->type)
            ->whereHas('userProfile', function ($query) {
                $query->where('user_id', Auth::id());
            })->first();

        if ($address) {
            $address->update([
                'address' => $this->address,
                'city' => $this->city,
                'state' => $this->state,
                'country' => $this->country,
                'zipcode' => $this->zipcode,
            ]);

            $this->isEditing = false;
        }
    }

    public function render()
    {
        return view('livewire.update-address');
    }
}
