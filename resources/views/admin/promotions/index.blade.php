@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-6">
            <h1 class="text-2xl font-semibold">{{ __('admin/promotions.title') }}</h1>
            <a href="{{ route('admin.promotions.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">
                <i class="bi bi-plus-lg"></i> {{ __('admin/promotions.create_promotion') }}
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
                        <th class="p-3 text-left">{{ __('admin/promotions.created_by') }}</th>
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
                            <td class="p-3">
                                @if($promotion->creator && $promotion->creator->profile)
                                    {{ $promotion->creator->profile->first_name }} {{ $promotion->creator->profile->last_name }} ({{ $promotion->creator->email }})
                                @else
                                    {{ __('admin/promotions.system_generated') }}
                                @endif
                            </td>
                            <td class="p-3 flex space-x-2">
                                <!-- View Button -->
                                <a href="{{ route('admin.promotions.show', $promotion) }}"
                                   class="px-3 py-1 bg-blue-500 text-white rounded-md shadow hover:bg-blue-600 transition flex items-center">
                                    <i class="bi bi-eye mr-1"></i> {{ __('admin/promotions.view') }}
                                </a>

                                <!-- Edit Button -->
                                <a href="{{ route('admin.promotions.edit', $promotion) }}"
                                   class="px-3 py-1 bg-yellow-500 text-white rounded-md shadow hover:bg-yellow-600 transition flex items-center">
                                    <i class="bi bi-pencil-square mr-1"></i> {{ __('admin/promotions.edit') }}
                                </a>

                                <!-- Delete Form -->
                                <form action="{{ route('admin.promotions.destroy', $promotion) }}" method="POST"
                                      onsubmit="return confirm('{{ __('admin/promotions.confirm_delete') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-1 bg-red-600 text-white rounded-md shadow hover:bg-red-700 transition flex items-center">
                                        <i class="bi bi-trash mr-1"></i> {{ __('admin/promotions.delete') }}
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
