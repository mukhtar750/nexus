@extends('layouts.admin')

@section('title', isset($summit) ? 'Edit Summit' : 'Create Summit')

@section('content')
    <div class="max-w-3xl">
        <div class="mb-6">
            <a href="{{ route('admin.summits.index') }}"
                class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-4">
                <i class="fas fa-arrow-left"></i> Back to Summits
            </a>
            <h2 class="text-2xl font-bold text-gray-800">{{ isset($summit) ? 'Edit Summit' : 'Create New Summit' }}</h2>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form action="{{ isset($summit) ? route('admin.summits.update', $summit) : route('admin.summits.store') }}"
                method="POST">
                @csrf
                @if(isset($summit))
                    @method('PUT')
                @endif

                <div class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Summit Title *</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $summit->title ?? '') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="e.g., Export Summit Kano" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="city" class="block text-sm font-semibold text-gray-700 mb-2">City *</label>
                        <input type="text" name="city" id="city" value="{{ old('city', $summit->city ?? '') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        @error('city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="zone" class="block text-sm font-semibold text-gray-700 mb-2">Zone *</label>
                        <input type="text" name="zone" id="zone" value="{{ old('zone', $summit->zone ?? '') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="e.g., South-South Zone" required>
                        @error('zone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="date" class="block text-sm font-semibold text-gray-700 mb-2">Date *</label>
                        <input type="text" name="date" id="date" value="{{ old('date', $summit->date ?? '') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="e.g., Oct 15 - 17, 2025" required>
                        @error('date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="venue" class="block text-sm font-semibold text-gray-700 mb-2">Venue *</label>
                        <input type="text" name="venue" id="venue" value="{{ old('venue', $summit->venue ?? '') }}"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="e.g., Hotel Presidential" required>
                        @error('venue')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="flex items-center gap-3">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $summit->is_active ?? true) ? 'checked' : '' }}
                                class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="text-sm font-medium text-gray-700">Active (Show on mobile app)</span>
                        </label>
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-lg transition-colors">
                        {{ isset($summit) ? 'Update Summit' : 'Create Summit' }}
                    </button>
                    <a href="{{ route('admin.summits.index') }}"
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-6 rounded-lg transition-colors text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection