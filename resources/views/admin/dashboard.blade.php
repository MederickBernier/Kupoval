@extends('layouts.admin')

@section('title', 'Dashboard')

@section('page-title', 'Dashboard Overview')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div class="p-4 bg-white shadow rounded-lg">
        <h3 class="text-lg font-semibold">Total Orders</h3>
        <p class="text-2xl font-bold text-green-600">250</p>
    </div>
    <div class="p-4 bg-white shadow rounded-lg">
        <h3 class="text-lg font-semibold">New Users</h3>
        <p class="text-2xl font-bold text-blue-600">45</p>
    </div>
    <div class="p-4 bg-white shadow rounded-lg">
        <h3 class="text-lg font-semibold">Revenue</h3>
        <p class="text-2xl font-bold text-purple-600">$12,345</p>
    </div>
</div>
@endsection
