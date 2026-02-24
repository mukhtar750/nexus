@extends('layouts.admin')

@section('title', 'Manage Events')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Events</h2>
        <a href="{{ route('admin.events.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i> Create Event
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date &
                        Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($events as $event)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($event->cover_image_url)
                                    <img class="h-10 w-10 rounded object-cover mr-3" src="{{ $event->cover_image_url_full }}"
                                        alt="">
                                @else
                                    <div class="h-10 w-10 rounded bg-gray-200 flex items-center justify-center mr-3">
                                        <i class="fas fa-calendar text-gray-400"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">
                                    {{ $event->title }}
                                    @if($event->requires_invitation)
                                        <span class="ml-2 px-2 py-0.5 text-xs font-semibold bg-amber-100 text-amber-800 rounded-full">
                                            <i class="fas fa-lock mr-1"></i> Invite Only
                                        </span>
                                    @endif
                                </div>
                                    <div class="text-sm text-gray-500 truncate w-64">{{ $event->description }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $event->start_time->format('M d, Y h:i A') }}</div>
                            <div class="text-sm text-gray-500">{{ $event->location }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.events.attendees.index', $event->id) }}"
                                class="text-green-600 hover:text-green-900 mr-3">Attendees</a>
                            <a href="{{ route('admin.events.speakers.index', $event->id) }}"
                                class="text-purple-600 hover:text-purple-900 mr-3">Speakers</a>
                            <a href="{{ route('admin.events.sessions.index', $event->id) }}"
                                class="text-orange-600 hover:text-orange-900 mr-3">Schedule</a>
                            <a href="{{ route('admin.events.polls.index', $event->id) }}"
                                class="text-blue-600 hover:text-blue-900 mr-3">Polls</a>
                            <a href="{{ route('admin.events.questions.index', $event->id) }}"
                                class="text-blue-600 hover:text-blue-900 mr-3">Q&A</a>
                            <a href="{{ route('admin.events.invitations', $event->id) }}"
                                class="text-amber-600 hover:text-amber-900 mr-3">Invitations</a>
                            <a href="{{ route('admin.events.edit', $event->id) }}"
                                class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                            <form action="{{ route('admin.events.delete', $event->id) }}" method="POST" class="inline"
                                onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $events->links() }}
        </div>
    </div>
@endsection