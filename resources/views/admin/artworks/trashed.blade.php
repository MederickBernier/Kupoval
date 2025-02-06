@extends('layouts.admin')

@section('title', __('admin/artworks.trashed_title'))

@section('content')
<div class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-xl font-semibold mb-4">{{ __('admin/artworks.trashed_title') }}</h2>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300 text-sm sm:text-base">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 border">{{ __('admin/artworks.name') }}</th>
                    <th class="px-4 py-2 border">{{ __('admin/artworks.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($artworks as $artwork)
                    <tr class="border">
                        <td class="px-4 py-2 border">{{ $artwork->name }}</td>
                        <td class="px-4 py-2 border text-center space-x-2">
                            <form action="{{ route('admin.artworks.restore', $artwork->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-green-500 hover:underline">{{ __('admin/artworks.restore') }}</button>
                            </form>
                            <form action="{{ route('admin.artworks.forceDelete', $artwork->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline">{{ __('admin/artworks.delete_permanently') }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
