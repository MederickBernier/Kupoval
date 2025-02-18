@extends('layouts.admin')

@section('content')
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center">
            <h1 class="text-xl font-semibold">{{ __('admin/promotions.show_promotion') }}</h1>
            <a href="{{ route('admin.promotions.index') }}"
               class="bg-gray-500 text-white px-4 py-2 rounded shadow hover:bg-gray-600 transition">
                <i class="bi bi-arrow-left"></i> {{ __('admin/promotions.back_to_list') }}
            </a>
        </div>

        <div class="bg-white p-6 shadow-md rounded-md mt-6">
            <h2 class="text-lg font-semibold">{{ $promotion->name }}</h2>

            <div class="mt-4">
                <p><strong>{{ __('admin/promotions.code') }}:</strong> {{ $promotion->code }}</p>
                <p><strong>{{ __('admin/promotions.discount_percentage') }}:</strong> {{ $promotion->discount_percentage }}%</p>
                <p><strong>{{ __('admin/promotions.start_date') }}:</strong> {{ $promotion->start_date }}</p>
                <p><strong>{{ __('admin/promotions.end_date') }}:</strong> {{ $promotion->end_date }}</p>
                <p><strong>{{ __('admin/promotions.created_by') }}:</strong>
                    @if($promotion->creator && $promotion->creator->profile)
                        {{ $promotion->creator->profile->first_name }} {{ $promotion->creator->profile->last_name }} ({{ $promotion->creator->email }})
                    @else
                        {{ __('admin/promotions.system_generated') }}
                    @endif
                </p>
            </div>

            <div class="mt-6 flex space-x-3">
                <a href="{{ route('admin.promotions.edit', $promotion) }}"
                   class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">
                    <i class="bi bi-pencil"></i> {{ __('admin/promotions.edit_promotion') }}
                </a>

                <form action="{{ route('admin.promotions.destroy', $promotion) }}" method="POST"
                      onsubmit="return confirm('{{ __('admin/promotions.confirm_delete') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded shadow hover:bg-red-700 transition">
                        <i class="bi bi-trash"></i> {{ __('admin/promotions.delete_promotion') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
