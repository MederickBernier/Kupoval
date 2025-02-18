@extends('layouts.admin')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-xl font-semibold mb-4">Create Promotion</h1>

        <div class="bg-white p-6 shadow-md rounded-md">
            <form action="{{ route('admin.promotions.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block font-medium">Promotion Code (Optional)</label>
                    <input type="text" name="code" class="w-full p-2 border rounded-md"
                           value="{{ old('code') }}" maxlength="20"
                           placeholder="Auto-generated if left blank">
                </div>

                <div class="mb-4">
                    <label class="block font-medium">Promotion Name</label>
                    <input type="text" name="name" class="w-full p-2 border rounded-md"
                           value="{{ old('name') }}" required>
                </div>

                <div class="mb-4">
                    <label class="block font-medium">Description</label>
                    <textarea name="description" class="editor w-full p-2 border rounded-md">{{ old('description') }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="block font-medium">Discount (%)</label>
                    <input type="number" name="discount_percentage" class="w-full p-2 border rounded-md"
                           value="{{ old('discount_percentage') }}" min="0" max="100" required>
                </div>

                <div class="mb-4">
                    <label class="block font-medium">Start Date</label>
                    <input type="date" name="start_date" class="w-full p-2 border rounded-md"
                           value="{{ old('start_date') }}" required>
                </div>

                <div class="mb-4">
                    <label class="block font-medium">End Date</label>
                    <input type="date" name="end_date" class="w-full p-2 border rounded-md"
                           value="{{ old('end_date') }}" required>
                </div>

                <div class="mt-6">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md">
                        Save Promotion
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
