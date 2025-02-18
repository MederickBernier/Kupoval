@extends('layouts.admin')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-xl font-semibold mb-4">{{ __('admin/promotions.edit_promotion') }}</h1>

        <div class="bg-white p-6 shadow-md rounded-md">
            <form action="{{ route('admin.promotions.update', $promotion) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block font-medium">{{ __('admin/promotions.name') }}</label>
                    <input type="text" name="name" class="w-full p-2 border rounded-md"
                           value="{{ old('name', $promotion->name) }}" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block font-medium">{{ __('admin/promotions.description') }}</label>
                    <textarea name="description" class="w-full p-2 border rounded-md">{{ old('description', $promotion->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block font-medium">{{ __('admin/promotions.discount_percentage') }}</label>
                    <input type="number" name="discount_percentage" class="w-full p-2 border rounded-md"
                           value="{{ old('discount_percentage', $promotion->discount_percentage) }}" min="0" max="100" required>
                    @error('discount_percentage')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block font-medium">{{ __('admin/promotions.code') }}</label>
                    <input type="text" name="code" class="w-full p-2 border rounded-md bg-gray-100"
                           value="{{ $promotion->code }}" readonly>
                </div>

                <div class="mb-4">
                    <label class="block font-medium">{{ __('admin/promotions.start_date') }}</label>
                    <input type="date" name="start_date" class="w-full p-2 border rounded-md"
                           value="{{ old('start_date', $promotion->start_date) }}" required>
                    @error('start_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block font-medium">{{ __('admin/promotions.end_date') }}</label>
                    <input type="date" name="end_date" class="w-full p-2 border rounded-md"
                           value="{{ old('end_date', $promotion->end_date) }}" required>
                    @error('end_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6 flex space-x-2">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        {{ __('admin/promotions.update_promotion') }}
                    </button>

                    <a href="{{ route('admin.promotions.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                        {{ __('admin/promotions.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
