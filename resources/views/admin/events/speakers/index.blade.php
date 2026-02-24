@extends('layouts.admin')

@section('title', 'Manage Speakers - ' . $event->title)

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.events.index') }}" class="text-blue-600 hover:text-blue-800 mb-2 inline-block">
            &larr; Back to Events
        </a>
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Speakers</h2>
                <p class="text-gray-600">Managing speakers for: {{ $event->title }}</p>
            </div>
            <a href="{{ route('admin.events.speakers.create', $event->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i> Add Speaker
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Speaker</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title/Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Schedule</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($speakers as $speaker)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center">
                            @if($speaker->avatar_url)
                                <img class="h-10 w-10 rounded-full object-cover mr-3" src="{{ $speaker->avatar_url }}" alt="">
                            @else
                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center mr-3 text-gray-500 font-bold">
                                    {{ substr($speaker->name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $speaker->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $speaker->title ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($speaker->schedule_time)
                            {{ \Carbon\Carbon::parse($speaker->schedule_time)->format('M d, h:i A') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('admin.events.speakers.edit', [$event->id, $speaker->id]) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                        <form action="{{ route('admin.events.speakers.delete', [$event->id, $speaker->id]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                        <p class="text-lg mb-2">No speakers added yet</p>
                        <p class="text-sm">Click "Add Speaker" to announce speakers for this event.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $speakers->links() }}
        </div>
    </div>
@endsection
