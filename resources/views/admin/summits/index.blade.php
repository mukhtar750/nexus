@extends('layouts.admin')

@section('title', 'Summits Management')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Summits Management</h2>
        <a href="{{ route('admin.summits.create') }}"
            class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-5 rounded-lg transition-colors shadow-sm">
            <i class="fas fa-plus"></i>
            Add New Summit
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Summit</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Schedule</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($summits as $summit)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">{{ $summit->title }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">{{ $summit->city }}</div>
                                <div class="text-[10px] text-gray-500 font-medium tracking-wider">{{ strtoupper($summit->zone) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600 flex items-center gap-1.5">
                                    <i class="far fa-calendar-alt text-gray-400"></i>
                                    {{ $summit->date }}
                                </div>
                                <div class="text-[10px] text-gray-500 mt-0.5 max-w-[150px] truncate underline decoration-gray-200" title="{{ $summit->venue }}">
                                    {{ $summit->venue }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($summit->is_active)
                                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-green-50 text-green-700 border border-green-100">ACTIVE</span>
                                @else
                                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-full bg-gray-50 text-gray-500 border border-gray-100">INACTIVE</span>
                                @endif
                                @if(isset($summit->hasHighlights) && $summit->hasHighlights)
                                    <div class="mt-1">
                                        <span class="text-[9px] font-bold text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded border border-blue-100 uppercase">Highlights Enabled</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.summits.edit', $summit) }}" 
                                       class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit Summit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.summits.delete', $summit) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Are you sure you want to delete this summit?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete Summit">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4 text-gray-300">
                                        <i class="fas fa-mountain text-3xl"></i>
                                    </div>
                                    <p class="text-sm font-medium">No summits found. Create your first summit!</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection