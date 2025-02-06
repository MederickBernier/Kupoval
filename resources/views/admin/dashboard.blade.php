@extends('layouts.admin')

@section('title', __('admin/dashboard.title'))

@section('page-title', __('admin/dashboard.page_title'))

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div class="p-4 bg-white shadow rounded-lg">
        <h3 class="text-lg font-semibold">{{ __('admin/dashboard.total_artworks') }}</h3>
        <p class="text-2xl font-bold text-green-600">{{ $totalArtworks }}</p>
    </div>

    <div class="p-4 bg-white shadow rounded-lg">
        <h3 class="text-lg font-semibold">{{ __('admin/dashboard.total_users') }}</h3>
        <p class="text-2xl font-bold text-blue-600">{{ $totalUsers }}</p>
    </div>

    <div class="p-4 bg-white shadow rounded-lg">
        <h3 class="text-lg font-semibold">{{ __('admin/dashboard.total_events') }}</h3>
        <p class="text-2xl font-bold text-purple-600">{{ $totalEvents }}</p>
    </div>
</div>
@endsection
