@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-6">
            <h1 class="text-2xl font-semibold">{{ __('admin/promotions.trashed_title') }}</h1>
            <a href="{{ route('admin.promotions.index') }}"
               class="bg-gray-600 text-white px-4 py-2 rounded shadow hover:bg-gray-700 transition">
                <i class="bi bi-arrow-left"></i> {{ __('admin/promotions.back_to_list') }}
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="p-3 text-left">#</th>
                        <th class="p-3 text-left">{{ __('admin/promotions.name') }}</th>
                        <th class="p-3 text-left">{{ __('admin/promotions.discount_percentage') }}</th>
                        <th class="p-3 text-left">{{ __('admin/promotions.code') }}</th>
                        <th class="p-3 text-left">{{ __('admin/promotions.start_date') }}</th>
                        <th class="p-3 text-left">{{ __('admin/promotions.end_date') }}</th>
                        <th class="p-3 text-left">{{ __('admin/promotions.deleted_at') }}</th>
                        <th class="p-3 text-left">{{ __('admin/promotions.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($promotions as $promotion)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="p-3">{{ $promotion->id }}</td>
                            <td class="p-3">{{ $promotion->name }}</td>
                            <td class="p-3">{{ $promotion->discount_percentage }}%</td>
                            <td class="p-3">{{ $promotion->code }}</td>
                            <td class="p-3">{{ $promotion->start_date }}</td>
                            <td class="p-3">{{ $promotion->end_date }}</td>
                            <td class="p-3">{{ $promotion->deleted_at }}</td>
                            <td class="p-3 flex space-x-2">
                                <!-- Restore Button -->
                                <form action="{{ route('admin.promotions.restore', $promotion->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit"
                                            class="px-3 py-1 bg-green-600 text-white rounded-md shadow hover:bg-green-700 transition flex items-center">
                                        <i class="bi bi-arrow-counterclockwise mr-1"></i> {{ __('admin/promotions.restore') }}
                                    </button>
                                </form>

                                <!-- Permanent Delete Form -->
                                <form action="{{ route('admin.promotions.forceDelete', $promotion->id) }}" method="POST"
                                      onsubmit="return confirm('{{ __('admin/promotions.confirm_permanent_delete') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-1 bg-red-600 text-white rounded-md shadow hover:bg-red-700 transition flex items-center">
                                        <i class="bi bi-trash mr-1"></i> {{ __('admin/promotions.permanent_delete') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $promotions->links() }}
        </div>
    </div>
@endsection
