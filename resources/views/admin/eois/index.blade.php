@extends('layouts.admin')

@section('title', 'EOI Applications — NESS 2026')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Exporter EOI Applications</h2>
        <p class="text-sm text-gray-500 mt-1">Review, select, or reject expressions of interest for NESS 2026</p>
    </div>
</div>

{{-- Flash messages --}}
@if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-2">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center gap-2">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
@endif

{{-- Stats row --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
        <p class="text-3xl font-bold text-gray-800">{{ $counts['total'] }}</p>
        <p class="text-xs text-gray-500 uppercase tracking-wider mt-1">Total</p>
    </div>
    <div class="bg-amber-50 rounded-xl p-4 shadow-sm border border-amber-100 text-center">
        <p class="text-3xl font-bold text-amber-700">{{ $counts['pending'] }}</p>
        <p class="text-xs text-amber-600 uppercase tracking-wider mt-1">Pending</p>
    </div>
    <div class="bg-green-50 rounded-xl p-4 shadow-sm border border-green-100 text-center">
        <p class="text-3xl font-bold text-green-700">{{ $counts['selected'] }}</p>
        <p class="text-xs text-green-600 uppercase tracking-wider mt-1">Selected</p>
    </div>
    <div class="bg-red-50 rounded-xl p-4 shadow-sm border border-red-100 text-center">
        <p class="text-3xl font-bold text-red-700">{{ $counts['rejected'] }}</p>
        <p class="text-xs text-red-600 uppercase tracking-wider mt-1">Rejected</p>
    </div>
</div>

{{-- Filters --}}
<form action="{{ route('admin.eois.index') }}" method="GET" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
    <div class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs text-gray-500 mb-1">Search</label>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Name, email, business..."
                class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-400 w-56">
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Summit</label>
            <select name="summit_id" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-400">
                <option value="">All Summits</option>
                @foreach($summits as $summit)
                    <option value="{{ $summit->id }}" {{ request('summit_id') == $summit->id ? 'selected' : '' }}>
                        {{ $summit->city }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Status</label>
            <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-blue-400">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="selected" {{ request('status') == 'selected' ? 'selected' : '' }}>Selected</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors">
            <i class="fas fa-search mr-1"></i> Filter
        </button>
        <a href="{{ route('admin.eois.index') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2 text-sm">Clear</a>
    </div>
</form>

{{-- Table --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-100">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Applicant</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Business</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Summit</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Export Status</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
            @forelse($eois as $eoi)
                <tr class="hover:bg-gray-50/80 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 h-9 w-9 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                                {{ substr($eoi->full_name, 0, 1) }}
                            </div>
                            <div>
                                <a href="{{ route('admin.eois.show', $eoi->id) }}"
                                   class="text-sm font-semibold text-blue-600 hover:text-blue-800">{{ $eoi->full_name }}</a>
                                <div class="text-xs text-gray-500">{{ $eoi->email }}</div>
                                <div class="text-xs text-gray-400">{{ $eoi->phone }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-800">{{ $eoi->business_name }}</div>
                        <div class="text-xs text-gray-500">{{ $eoi->sector ? ucfirst(str_replace('_', ' ', $eoi->sector)) : '—' }}</div>
                        <div class="text-xs text-gray-400">{{ $eoi->state }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-700">{{ $eoi->summit->city ?? '—' }}</div>
                        <div class="text-xs text-gray-400">{{ $eoi->preferred_location ? ucfirst(str_replace('_', ' ', $eoi->preferred_location)) : '' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $expMap = [
                                'currently_exporting' => ['Currently Exporting', 'bg-green-100 text-green-700'],
                                'exported_before'     => ['Exported Before', 'bg-blue-100 text-blue-700'],
                                'export_ready'        => ['Export Ready', 'bg-purple-100 text-purple-700'],
                                'exploring'           => ['Exploring', 'bg-gray-100 text-gray-600'],
                            ];
                            $exp = $expMap[$eoi->export_status] ?? ['Unknown', 'bg-gray-100 text-gray-600'];
                        @endphp
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $exp[1] }}">{{ $exp[0] }}</span>
                        <div class="text-xs text-gray-400 mt-1">{{ str_replace('_', ' ', $eoi->recent_export_value ?? '') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $statusMap = [
                                'pending'  => 'bg-amber-100 text-amber-700',
                                'selected' => 'bg-green-100 text-green-700',
                                'rejected' => 'bg-red-100 text-red-700',
                            ];
                        @endphp
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusMap[$eoi->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($eoi->status) }}
                        </span>
                        @if($eoi->status === 'selected' && !$eoi->registered_user_id)
                            <div class="text-xs text-amber-600 mt-1">⏳ Awaiting registration</div>
                        @elseif($eoi->registered_user_id)
                            <div class="text-xs text-green-600 mt-1">✅ Registered</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.eois.show', $eoi->id) }}"
                               class="text-blue-600 hover:text-blue-800 text-xs font-medium">View</a>

                            @if($eoi->status === 'pending')
                                <form action="{{ route('admin.eois.select', $eoi->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="text-green-600 hover:text-green-800 text-xs font-medium"
                                        onclick="return confirm('Select {{ $eoi->full_name }} for NESS 2026?')">
                                        Select
                                    </button>
                                </form>

                                <button onclick="openRejectModal({{ $eoi->id }}, '{{ addslashes($eoi->full_name) }}')"
                                    class="text-red-600 hover:text-red-800 text-xs font-medium">
                                    Reject
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                        <i class="fas fa-inbox text-4xl mb-3 block"></i>
                        No expressions of interest found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $eois->links() }}
    </div>
</div>

{{-- Reject Modal --}}
<div id="rejectModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-xl">
        <h3 class="text-lg font-bold text-gray-800 mb-1">Reject Application</h3>
        <p id="rejectModalName" class="text-sm text-gray-500 mb-4"></p>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Reason (optional)</label>
                <textarea name="reason" rows="3" 
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-red-400"
                    placeholder="Provide a reason for the applicant..."></textarea>
            </div>
            <div class="flex gap-2 justify-end">
                <button type="button" onclick="closeRejectModal()"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200">Cancel</button>
                <button type="submit"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm hover:bg-red-700">Reject Application</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRejectModal(eoiId, name) {
        document.getElementById('rejectForm').action = `/admin/eois/${eoiId}/reject`;
        document.getElementById('rejectModalName').textContent = `Applicant: ${name}`;
        document.getElementById('rejectModal').classList.remove('hidden');
    }
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }
    // Close on backdrop click
    document.getElementById('rejectModal').addEventListener('click', function(e) {
        if (e.target === this) closeRejectModal();
    });
</script>
@endsection
