@extends('layouts.public')

@section('title', 'User Profile')

@section('content')
<div class="max-w-4xl mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
    <h2 class="text-3xl font-semibold mb-6 text-gray-800">User Profile</h2>

    <div class="space-y-4">
        <!-- First Name -->
        <div class="flex items-center justify-between border-b pb-2">
            <label class="text-lg text-gray-700">First Name:</label>
            <livewire:update-field field="first_name" :value="Auth::user()->profile->first_name ?? ''" />
        </div>

        <!-- Last Name -->
        <div class="flex items-center justify-between border-b pb-2">
            <label class="text-lg text-gray-700">Last Name:</label>
            <livewire:update-field field="last_name" :value="Auth::user()->profile->last_name ?? ''" />
        </div>

        <!-- Email -->
        <div class="flex items-center justify-between border-b pb-2">
            <label class="text-lg text-gray-700">Email:</label>
            <livewire:update-field field="email" :value="Auth::user()->email ?? ''" />
        </div>
    </div>
</div>
@endsection
