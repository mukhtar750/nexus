@extends('layouts.admin')

@section('title', 'Add Speaker - ' . $event->title)

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.events.speakers.index', $event->id) }}" class="text-blue-600 hover:text-blue-800 mb-2 inline-block">
            &larr; Back to Speakers
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Add Speaker</h2>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl">
        <form action="{{ route('admin.events.speakers.store', $event->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-medium mb-2">Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="title" class="block text-gray-700 font-medium mb-2">Title / Role</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" placeholder="e.g. CEO, Keynote Speaker">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="avatar" class="block text-gray-700 font-medium mb-2">Avatar (Image)</label>
                <input type="file" name="avatar" id="avatar" class="w-full text-gray-700 border border-gray-300 rounded-md p-2 focus:outline-none focus:border-blue-500">
                <p class="text-sm text-gray-500 mt-1">Recommended: Square image, max 2MB.</p>
                @error('avatar')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="schedule_time" class="block text-gray-700 font-medium mb-2">Schedule Time</label>
                <input type="datetime-local" name="schedule_time" id="schedule_time" value="{{ old('schedule_time') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                <p class="text-sm text-gray-500 mt-1">Optional. When is this speaker presenting?</p>
                @error('schedule_time')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="bio" class="block text-gray-700 font-medium mb-2">Bio</label>
                <textarea name="bio" id="bio" rows="4" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">{{ old('bio') }}</textarea>
                @error('bio')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <a href="{{ route('admin.events.speakers.index', $event->id) }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded mr-2 hover:bg-gray-400">Cancel</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add Speaker</button>
            </div>
        </form>
    </div>
@endsection
