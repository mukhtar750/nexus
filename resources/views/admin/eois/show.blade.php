@extends('layouts.admin')

@section('title', 'EOI Application — ' . $eoi->full_name)

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('admin.eois.index') }}"
                class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-1 mb-2">
                <i class="fas fa-arrow-left"></i> Back to Applications
            </a>
            <h2 class="text-2xl font-bold text-gray-800">{{ $eoi->full_name }}</h2>
            <p class="text-sm text-gray-500">Submitted {{ $eoi->created_at->diffForHumans() }} · Summit:
                {{ $eoi->summit->city ?? 'N/A' }}</p>
        </div>
        {{-- Status badge --}}
        @php
            $statusMap = [
                'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                'selected' => 'bg-green-100 text-green-700 border-green-200',
                'rejected' => 'bg-red-100 text-red-700 border-red-200',
            ];
        @endphp
        <span
            class="px-4 py-2 rounded-full text-sm font-semibold border {{ $statusMap[$eoi->status] ?? 'bg-gray-100 text-gray-600' }}">
            {{ ucfirst($eoi->status) }}
        </span>
    </div>

    {{-- Flash --}}
    @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Full details --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Section A --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-500">
                    <h3 class="text-white font-bold text-sm uppercase tracking-wider">Section A — Contact & Business
                        Identity</h3>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @php
                        $sectionA = [
                            'Full Name' => $eoi->full_name,
                            'Phone' => $eoi->phone,
                            'Email' => $eoi->email,
                            'Business Name' => $eoi->business_name,
                            'State' => $eoi->state,
                            'Preferred Location' => ucwords(str_replace('_', ' ', $eoi->preferred_location ?? '')),
                            'How They Heard' => ucwords(str_replace('_', ' ', $eoi->how_heard ?? '')),
                        ];
                    @endphp
                    @foreach($sectionA as $label => $value)
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">{{ $label }}</p>
                            <p class="text-sm font-medium text-gray-800">{{ $value ?: '—' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Section B --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-purple-500">
                    <h3 class="text-white font-bold text-sm uppercase tracking-wider">Section B — Business Profile & Export
                        Status</h3>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @php
                        $expStatusMap = [
                            'currently_exporting' => 'Currently Exporting (last 12 months)',
                            'exported_before' => 'Exported Before (1–3 years ago)',
                            'export_ready' => 'Export-Ready (no export yet)',
                            'exploring' => 'Exploring / Learning',
                        ];
                        $exportValueMap = [
                            'above_50m' => 'Above ₦50m',
                            '10m_to_50m' => '₦10m – ₦50m',
                            'below_10m' => 'Below ₦10m',
                            'no_export_yet' => 'No Export Yet',
                        ];
                        $sectionB = [
                            'Sector' => ucwords(str_replace('_', ' ', $eoi->sector ?? '')),
                            'Primary Products' => $eoi->primary_products,
                            'CAC Registration' => ucwords(str_replace('_', ' ', $eoi->cac_registration ?? '')),
                            'NEPC Registration' => ucwords(str_replace('_', ' ', $eoi->nepc_registration ?? '')),
                            'Export Status' => $expStatusMap[$eoi->export_status] ?? $eoi->export_status,
                            'Recent Export Value' => $exportValueMap[$eoi->recent_export_value] ?? $eoi->recent_export_value,
                        ];
                    @endphp
                    @foreach($sectionB as $label => $value)
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">{{ $label }}</p>
                            <p class="text-sm font-medium text-gray-800">{{ $value ?: '—' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Section C --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-teal-600 to-teal-500">
                    <h3 class="text-white font-bold text-sm uppercase tracking-wider">Section C — Additional Information
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Commercial Scale</p>
                            <span
                                class="inline-flex items-center gap-1 text-sm font-medium {{ $eoi->commercial_scale ? 'text-green-600' : 'text-gray-500' }}">
                                <i class="fas {{ $eoi->commercial_scale ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                {{ $eoi->commercial_scale ? 'Yes' : 'No' }}
                            </span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Regulatory Registration</p>
                            <span
                                class="inline-flex items-center gap-1 text-sm font-medium {{ $eoi->regulatory_registration ? 'text-green-600' : 'text-gray-500' }}">
                                <i
                                    class="fas {{ $eoi->regulatory_registration ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                {{ $eoi->regulatory_registration ? 'Yes' : 'No' }}
                            </span>
                        </div>
                    </div>

                    @if($eoi->regulatory_body)
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Regulatory Body</p>
                            <p class="text-sm font-medium text-gray-800">{{ $eoi->regulatory_body }}</p>
                        </div>
                    @endif

                    {{-- Certifications --}}
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Certifications Held</p>
                        @if(!empty($eoi->certifications))
                            <div class="flex flex-wrap gap-2">
                                @foreach($eoi->certifications as $cert)
                                    <span
                                        class="px-2.5 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-medium border border-blue-100">
                                        {{ $cert }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">None specified</p>
                        @endif
                    </div>

                    {{-- Seminar Goals --}}
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-wide mb-2">Seminar Goals</p>
                        @if(!empty($eoi->seminar_goals))
                            <div class="flex flex-wrap gap-2">
                                @foreach($eoi->seminar_goals as $goal)
                                    <span
                                        class="px-2.5 py-1 bg-purple-50 text-purple-700 rounded-full text-xs font-medium border border-purple-100">
                                        {{ $goal }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">None specified</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Action panel --}}
        <div class="space-y-4">

            {{-- Action card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-800 mb-4">Actions</h3>

                @if($eoi->status === 'pending')
                    <form action="{{ route('admin.eois.select', $eoi->id) }}" method="POST" class="mb-3">
                        @csrf
                        <button type="submit"
                            class="w-full bg-green-600 hover:bg-green-700 text-white py-2.5 rounded-lg text-sm font-semibold transition-colors"
                            onclick="return confirm('Select {{ addslashes($eoi->full_name) }} for NESS 2026?')">
                            <i class="fas fa-check-circle mr-2"></i> Select This Applicant
                        </button>
                    </form>

                    <form action="{{ route('admin.eois.reject', $eoi->id) }}" method="POST">
                        @csrf
                        <div class="mb-2">
                            <textarea name="reason" rows="2" placeholder="Rejection reason (optional)"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-red-400"></textarea>
                        </div>
                        <button type="submit"
                            class="w-full bg-red-50 hover:bg-red-600 text-red-600 hover:text-white border border-red-200 py-2.5 rounded-lg text-sm font-semibold transition-colors"
                            onclick="return confirm('Reject this application?')">
                            <i class="fas fa-times-circle mr-2"></i> Reject Application
                        </button>
                    </form>

                @elseif($eoi->status === 'selected')
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                        <i class="fas fa-trophy text-green-500 text-2xl mb-2"></i>
                        <p class="text-sm font-semibold text-green-700">Selected</p>
                        <p class="text-xs text-green-600 mt-1">{{ $eoi->selected_at?->format('d M Y, h:i A') }}</p>
                    </div>

                    @if($eoi->registration_token && !$eoi->registered_user_id)
                        <div class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                            <p class="text-xs text-amber-700 font-semibold mb-1">Registration Token</p>
                            <code class="text-xs text-amber-800 break-all bg-amber-100 px-2 py-1 rounded block">
                                        {{ $eoi->registration_token }}
                                    </code>
                            <p class="text-xs text-amber-600 mt-1">Share registration link with participant.</p>
                        </div>
                    @elseif($eoi->registered_user_id)
                        <div class="mt-3 p-3 bg-green-50 border border-green-200 rounded-lg text-center">
                            <i class="fas fa-user-check text-green-500 mb-1"></i>
                            <p class="text-xs text-green-700 font-semibold">Account Created</p>
                            @if($eoi->registeredUser)
                                <a href="{{ route('admin.users.show', $eoi->registered_user_id) }}"
                                    class="text-xs text-blue-600 hover:underline">View User Account →</a>
                            @endif
                        </div>
                    @endif

                @elseif($eoi->status === 'rejected')
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <p class="text-sm font-semibold text-red-700 mb-1">Rejected</p>
                        @if($eoi->rejection_reason)
                            <p class="text-xs text-red-600">{{ $eoi->rejection_reason }}</p>
                        @else
                            <p class="text-xs text-gray-500 italic">No reason provided.</p>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Summary card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-800 mb-3 text-sm">Quick Summary</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Summit</span>
                        <span class="font-medium text-gray-800">{{ $eoi->summit->city ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Preferred Location</span>
                        <span
                            class="font-medium text-gray-800">{{ ucwords(str_replace('_', ' ', $eoi->preferred_location ?? '')) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Sector</span>
                        <span
                            class="font-medium text-gray-800">{{ ucwords(str_replace('_', ' ', $eoi->sector ?? '')) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">CAC</span>
                        <span
                            class="font-medium text-gray-800">{{ ucwords(str_replace('_', ' ', $eoi->cac_registration ?? '')) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">NEPC</span>
                        <span
                            class="font-medium text-gray-800">{{ ucwords(str_replace('_', ' ', $eoi->nepc_registration ?? '')) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Certifications</span>
                        <span class="font-medium text-gray-800">{{ count($eoi->certifications ?? []) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Submitted</span>
                        <span class="font-medium text-gray-800">{{ $eoi->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection