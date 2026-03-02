@extends('layouts.admin')

@section('title', 'Generate Invitation Token')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.invitations.index') }}"
                class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-blue-600 hover:border-blue-100 transition-all">
                <i class="fas fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Generate Token</h2>
                <p class="text-sm text-gray-500">Create a unique invitation for a NESS 2026 attendee.</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100 bg-gray-50/30">
                <h3 class="font-bold text-gray-800">Invitation Details</h3>
            </div>

            <form action="{{ route('admin.invitations.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <div class="space-y-4">
                    {{-- Invitation Type --}}
                    <div class="grid grid-cols-2 gap-4">
                        <label
                            class="relative flex flex-col p-4 border rounded-xl cursor-pointer hover:border-blue-200 transition-colors group">
                            <input type="radio" name="type" value="delegate" checked
                                class="absolute top-4 right-4 text-blue-600 focus:ring-blue-500">
                            <span
                                class="text-xs font-semibold uppercase tracking-wider text-gray-400 group-hover:text-blue-500 mb-1">Type</span>
                            <span class="text-sm font-bold text-gray-800">Delegate</span>
                            <p class="text-[10px] text-gray-500 mt-1 italic leading-tight">Standard attendee invitation for
                                the summit.</p>
                        </label>

                        <label
                            class="relative flex flex-col p-4 border rounded-xl cursor-pointer hover:border-blue-200 transition-colors group">
                            <input type="radio" name="type" value="speaker"
                                class="absolute top-4 right-4 text-blue-600 focus:ring-blue-500">
                            <span
                                class="text-xs font-semibold uppercase tracking-wider text-gray-400 group-hover:text-amber-500 mb-1">Type</span>
                            <span class="text-sm font-bold text-gray-800">Speaker / Panelist</span>
                            <p class="text-[10px] text-gray-500 mt-1 italic leading-tight">Special invitation for guest
                                speakers and keynote presenters.</p>
                        </label>
                    </div>

                    {{-- Summit Selection --}}
                    <div class="space-y-1.5">
                        <label for="summit_id" class="text-xs font-bold text-gray-500 uppercase tracking-wider">Select
                            Summit</label>
                        <select name="summit_id" id="summit_id" required
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                            <option value="">-- Choose a Summit --</option>
                            @foreach($summits as $summit)
                                <option value="{{ $summit->id }}">{{ $summit->title }} ({{ $summit->city }})</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Name and Email --}}
                    <div class="space-y-1.5">
                        <label for="full_name" class="text-xs font-bold text-gray-500 uppercase tracking-wider">Attendee
                            Full Name</label>
                        <input type="text" name="full_name" id="full_name" placeholder="e.g. John Doe" required
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    </div>

                    <div class="space-y-1.5">
                        <label for="email" class="text-xs font-bold text-gray-500 uppercase tracking-wider">Attendee Email
                            Address</label>
                        <input type="email" name="email" id="email" placeholder="e.g. john@example.com" required
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                    </div>
                </div>

                <div class="pt-4 flex items-center gap-4">
                    <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-blue-500/20 transition-all">
                        Generate Invitation Token
                    </button>
                    <a href="{{ route('admin.invitations.index') }}"
                        class="px-6 py-3 border border-gray-200 rounded-xl text-sm font-bold text-gray-500 hover:bg-gray-50 transition-all">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

        <!-- Instructions Card -->
        <div class="bg-amber-50 rounded-xl p-5 border border-amber-100 flex gap-4">
            <div class="w-10 h-10 rounded-lg bg-amber-500/10 flex items-center justify-center text-amber-600 flex-shrink-0">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="space-y-1">
                <h4 class="text-sm font-bold text-amber-900">How to use this token</h4>
                <p class="text-xs text-amber-800 leading-relaxed">
                    Once generated, copy the token and send it to the attendee. They can enter this token in the mobile app
                    under the <span class="font-bold">"Delegate / Speaker"</span> section to confirm their attendance and
                    create their account.
                </p>
            </div>
        </div>
    </div>
@endsection