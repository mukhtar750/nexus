@extends('layouts.admin')

@section('title', 'Manage Events')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Events</h2>
        <a href="{{ route('admin.events.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i> Create Event
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Event</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date & Location</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($events as $event)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center min-w-[280px]">
                                    @if($event->cover_image_url)
                                        <img class="h-12 w-12 rounded-lg object-cover mr-4 shadow-sm" src="{{ $event->cover_image_url_full }}"
                                            alt="">
                                    @else
                                        <div class="h-12 w-12 rounded-lg bg-gray-100 flex items-center justify-center mr-4 text-gray-400">
                                            <i class="fas fa-calendar text-xl"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                            <span class="truncate">{{ $event->title }}</span>
                                            @if($event->requires_invitation)
                                                <span class="flex-shrink-0 px-2 py-0.5 text-[10px] font-bold bg-amber-100 text-amber-700 rounded-full">
                                                    INVITE
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1 line-clamp-1 max-w-[200px]">{{ $event->description }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <div class="text-sm font-medium text-gray-900 flex items-center gap-1.5">
                                        <i class="far fa-clock text-gray-400 text-xs"></i>
                                        {{ $event->start_time->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1 flex items-center gap-1.5">
                                        <i class="fas fa-map-marker-alt text-gray-400 text-[10px]"></i>
                                        {{ $event->location }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center gap-3">
                                    {{-- Group primary actions --}}
                                    <div class="flex items-center bg-gray-50 rounded-lg p-1">
                                        <a href="{{ route('admin.events.attendees.index', $event->id) }}"
                                            class="p-1.5 text-green-600 hover:bg-white hover:shadow-sm rounded-md transition-all" title="Attendees">
                                            <i class="fas fa-users"></i>
                                        </a>
                                        <a href="{{ route('admin.events.speakers.index', $event->id) }}"
                                            class="p-1.5 text-purple-600 hover:bg-white hover:shadow-sm rounded-md transition-all" title="Speakers">
                                            <i class="fas fa-microphone"></i>
                                        </a>
                                        <a href="{{ route('admin.events.sessions.index', $event->id) }}"
                                            class="p-1.5 text-orange-600 hover:bg-white hover:shadow-sm rounded-md transition-all" title="Schedule">
                                            <i class="fas fa-list-ul"></i>
                                        </a>
                                    </div>

                                    <div class="h-4 w-px bg-gray-200"></div>

                                    <a href="{{ route('admin.events.edit', $event->id) }}"
                                        class="text-gray-400 hover:text-blue-600 transition-colors p-1" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form action="{{ route('admin.events.delete', $event->id) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors p-1" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($events->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $events->links() }}
            </div>
        @endif
    </div>
@endsection