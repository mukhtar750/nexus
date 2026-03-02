@extends('layouts.admin')

@section('title', 'Invitation Tokens')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Invitation Tokens</h2>
                <p class="text-sm text-gray-500">Manage unique tokens for NESS 2026 Delegates and Speakers.</p>
            </div>
            <a href="{{ route('admin.invitations.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2">
                <i class="fas fa-plus"></i>
                <span>Generate New Token</span>
            </a>
        </div>

        <!-- Delegate Invitations -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-users text-blue-500"></i>
                    Delegate Invitations
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                            <th class="px-6 py-3 font-semibold">Attendee</th>
                            <th class="px-6 py-3 font-semibold">Summit</th>
                            <th class="px-6 py-3 font-semibold">Token</th>
                            <th class="px-6 py-3 font-semibold">Status</th>
                            <th class="px-6 py-3 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($delegateInvitations as $invite)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-800">{{ $invite->full_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $invite->email }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $invite->summit->title ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    <code class="text-xs bg-gray-100 px-2 py-1 rounded border border-gray-200 text-blue-600">
                                        {{ $invite->token }}
                                    </code>
                                </td>
                                <td class="px-6 py-4">
                                    @if($invite->user_id)
                                        <span
                                            class="px-2 py-1 text-xs font-bold bg-green-100 text-green-700 rounded-full">Confirmed</span>
                                    @else
                                        <span
                                            class="px-2 py-1 text-xs font-bold bg-amber-100 text-amber-700 rounded-full">Pending</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form
                                        action="{{ route('admin.invitations.destroy', ['type' => 'delegate', 'id' => $invite->id]) }}"
                                        method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 p-2"
                                            onclick="return confirm('Scale back this invitation?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">No delegate tokens generated yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $delegateInvitations->links() }}
            </div>
        </div>

        <!-- Speaker Invitations -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-microphone text-emerald-500"></i>
                    Speaker Invitations
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                            <th class="px-6 py-3 font-semibold">Speaker</th>
                            <th class="px-6 py-3 font-semibold">Summit</th>
                            <th class="px-6 py-3 font-semibold">Token</th>
                            <th class="px-6 py-3 font-semibold">Status</th>
                            <th class="px-6 py-3 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($speakerInvitations as $invite)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-800">{{ $invite->full_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $invite->email }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $invite->summit->title ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    <code class="text-xs bg-gray-100 px-2 py-1 rounded border border-gray-200 text-emerald-600">
                                        {{ $invite->token }}
                                    </code>
                                </td>
                                <td class="px-6 py-4">
                                    @if($invite->user_id)
                                        <span
                                            class="px-2 py-1 text-xs font-bold bg-green-100 text-green-700 rounded-full">Confirmed</span>
                                    @else
                                        <span
                                            class="px-2 py-1 text-xs font-bold bg-amber-100 text-amber-700 rounded-full">Pending</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form
                                        action="{{ route('admin.invitations.destroy', ['type' => 'speaker', 'id' => $invite->id]) }}"
                                        method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 p-2"
                                            onclick="return confirm('Revoke this speaker invitation?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">No speaker tokens generated yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $speakerInvitations->links() }}
            </div>
        </div>
    </div>
@endsection