@extends('layouts.admin')

@section('title', 'Edit Poll - ' . $event->title)

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Poll</h2>
            <p class="text-gray-600">For Event: <span class="font-semibold">{{ $event->title }}</span></p>
        </div>
        <a href="{{ route('admin.events.polls.index', $event->id) }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            <i class="fas fa-arrow-left mr-2"></i> Back to Polls
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 max-w-2xl mx-auto">
        <form action="{{ route('admin.events.polls.update', [$event->id, $poll->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="question" class="block text-gray-700 font-medium mb-2">Question</label>
                <input type="text" name="question" id="question" value="{{ old('question', $poll->question) }}" 
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('question') border-red-500 @enderror" required>
                @error('question')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Options (Read-only)</label>
                <p class="text-sm text-gray-500 mb-3">Options cannot be edited to preserve vote integrity. Delete and recreate the poll if options need to change.</p>
                <ul class="list-disc list-inside text-gray-700 bg-gray-50 p-4 rounded-lg">
                    @foreach($poll->options as $option)
                        <li>{{ $option->option_text }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="mb-6">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_active" class="form-checkbox h-5 w-5 text-blue-600" {{ $poll->is_active ? 'checked' : '' }}>
                    <span class="ml-2 text-gray-700">Active (Visible to attendees)</span>
                </label>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Update Poll
                </button>
            </div>
        </form>
    </div>
@endsection
