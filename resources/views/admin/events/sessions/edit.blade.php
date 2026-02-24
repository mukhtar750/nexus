@extends('layouts.admin')

@section('title', 'Edit Session - ' . $event->title)

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.events.sessions.index', $event->id) }}" class="text-blue-600 hover:text-blue-800 mb-2 inline-block">
            &larr; Back to Schedule
        </a>
        <h2 class="text-2xl font-bold text-gray-800">Edit Session</h2>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl">
        <form action="{{ route('admin.events.sessions.update', [$event->id, $session->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="title" class="block text-gray-700 font-bold mb-2">Session Title</label>
                <input type="text" name="title" id="title" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('title', $session->title) }}" required>
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="speaker" class="block text-gray-700 font-bold mb-2">Speaker (Optional)</label>
                <input type="text" name="speaker" id="speaker" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('speaker', $session->speaker) }}" placeholder="e.g. John Doe">
                @error('speaker')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="start_time" class="block text-gray-700 font-bold mb-2">Start Time</label>
                    <input type="datetime-local" name="start_time" id="start_time" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('start_time', $session->start_time->format('Y-m-d\TH:i')) }}" required>
                    @error('start_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="end_time" class="block text-gray-700 font-bold mb-2">End Time</label>
                    <input type="datetime-local" name="end_time" id="end_time" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('end_time', $session->end_time->format('Y-m-d\TH:i')) }}" required>
                    @error('end_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="location" class="block text-gray-700 font-bold mb-2">Location</label>
                <input type="text" name="location" id="location" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" value="{{ old('location', $session->location) }}" required placeholder="e.g. Main Hall">
                @error('location')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Update Session
                </button>
            </div>
        </form>
    </div>
@endsection
