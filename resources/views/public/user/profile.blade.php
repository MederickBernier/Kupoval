@extends('layouts.public')

@section('title', 'User Profile')

@section('content')
<div class="max-w-4xl mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
    <h2 class="text-3xl font-semibold mb-6 text-gray-800">User Profile</h2>

    <form action="{{ route('user.profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-4">
            <!-- First Name -->
            <div class="flex items-center justify-between border-b pb-2">
                <label class="text-lg text-gray-700">First Name:</label>
                <div id="first_name-container">
                    <span id="first_name-text">{{ Auth::user()->profile->first_name }}</span>
                    <i class="bi bi-pencil text-gray-500 hover:text-gray-700 cursor-pointer"
                       hx-get="{{ route('user.edit-field', ['field' => 'first_name']) }}"
                       hx-target="#first_name-container"
                       hx-swap="outerHTML"></i>
                </div>
            </div>


            <!-- Last Name -->
            <div class="flex items-center justify-between border-b pb-2">
                <label class="text-lg text-gray-700">Last Name:</label>
                <input type="text" name="last_name" value="{{ Auth::user()->profile->last_name }}"
                       class="border px-2 py-1 rounded w-full">
            </div>

            <!-- Email -->
            <div class="flex items-center justify-between border-b pb-2">
                <label class="text-lg text-gray-700">Email:</label>
                <input type="email" name="email" value="{{ Auth::user()->email }}"
                       class="border px-2 py-1 rounded w-full">
            </div>

            <!-- Bouton de sauvegarde -->
            <div class="mt-4 text-right">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Save</button>
            </div>
        </div>
    </form>
</div>
@endsection
