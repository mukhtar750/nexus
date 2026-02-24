@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-1 mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Users
            </a>
            <h2 class="text-2xl font-bold text-gray-800">User Profile</h2>
        </div>
        
        <div class="flex gap-3">
            @if($user->status !== 'approved')
                <form action="{{ route('admin.users.approve', $user->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 font-medium" onclick="return confirm('Approve this user?')">
                        Approve User
                    </button>
                </form>
            @endif

            @if($user->status !== 'rejected')
                <form action="{{ route('admin.users.reject', $user->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 font-medium" onclick="return confirm('Reject this user?')">
                        Reject User
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Identity Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex flex-col items-center text-center">
                    @if($user->avatar_url)
                        <img src="{{ Str::startsWith($user->avatar_url, 'http') ? $user->avatar_url : Storage::url($user->avatar_url) }}" alt="{{ $user->name }}" class="w-32 h-32 rounded-full object-cover border-4 border-gray-100 shadow-sm mb-4">
                    @else
                        <div class="w-32 h-32 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-4xl font-bold mb-4 shadow-sm border-4 border-white">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                    <h3 class="text-xl font-bold text-gray-900">{{ $user->name }}</h3>
                    <p class="text-gray-500 mb-2">{{ $user->email }}</p>
                    
                    <div class="mt-2 text-sm font-medium uppercase tracking-wider text-gray-500">
                        {{ $user->user_type ?? 'User' }}
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-100 w-full">
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-600 text-sm">Status</span>
                            <span class="px-3 py-1 text-xs font-bold rounded-full 
                                {{ $user->status === 'approved' ? 'bg-green-100 text-green-800' :
                                   ($user->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ ucfirst($user->status ?? 'Pending') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-600 text-sm">Joined</span>
                            <span class="text-gray-900 font-medium text-sm">{{ $user->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact & Location Box -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4">Contact Info</h3>
                <div class="space-y-4">
                    <div>
                        <span class="block text-xs text-gray-500 uppercase">Phone Number</span>
                        <span class="block text-gray-900 font-medium">{{ $user->phone ?? 'Not provided' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500 uppercase">Office Address</span>
                        <span class="block text-gray-900 font-medium">{{ $user->business_address ?? 'Not provided' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Business & Export Details -->
        <div class="lg:col-span-2 space-y-6">
            
            @if($user->user_type === 'exporter')
            <!-- Business Identity -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center gap-2 mb-4 border-b pb-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <h3 class="text-lg font-bold text-gray-800">Business Identity</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
                    <div>
                        <span class="block text-xs text-gray-500 uppercase">Company/Business Name</span>
                        <span class="block text-gray-900 font-medium">{{ $user->company ?? $user->business_name ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500 uppercase">Business Structure</span>
                        <span class="block text-gray-900 font-medium">{{ $user->business_structure ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500 uppercase">Year Established</span>
                        <span class="block text-gray-900 font-medium">{{ $user->year_established ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500 uppercase">Industry Sector</span>
                        <span class="block text-gray-900 font-medium">{{ $user->industry_sector ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Licensing & Certifications -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center gap-2 mb-4 border-b pb-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="text-lg font-bold text-gray-800">Licensing & Certifications</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- CAC -->
                    <div class="border rounded-lg p-4 {{ $user->registered_with_cac ? 'border-green-200 bg-green-50' : 'border-gray-200' }}">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-bold text-gray-900 flex items-center gap-2">
                                    CAC Registration
                                    @if($user->registered_with_cac)<span class="text-green-600"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg></span>@endif
                                </h4>
                                <p class="text-sm text-gray-600 mt-1">RC Number: {{ $user->cac_number ?? 'Not provided' }}</p>
                            </div>
                            @if($user->cac_certificate_path)
                                <a href="{{ Storage::url($user->cac_certificate_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex gap-1 items-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    View
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- NEPC -->
                    <div class="border rounded-lg p-4 {{ $user->registered_with_nepc ? 'border-green-200 bg-green-50' : 'border-gray-200' }}">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-bold text-gray-900 flex items-center gap-2">
                                    NEPC Registration
                                    @if($user->registered_with_nepc)<span class="text-green-600"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg></span>@endif
                                </h4>
                                <p class="text-sm text-gray-600 mt-1">Status: {{ $user->nepc_status ?? 'Not provided' }}</p>
                            </div>
                            @if($user->nepc_certificate_path)
                                <a href="{{ Storage::url($user->nepc_certificate_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex gap-1 items-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    View
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Additional Certs Summary -->
                    <div class="col-span-1 md:col-span-2 mt-2">
                        <span class="block text-xs text-gray-500 uppercase mb-2">Other Certifications Uploaded</span>
                        <div class="flex flex-wrap gap-2">
                            @if($user->haccp_certificate_path)
                                <a href="{{ Storage::url($user->haccp_certificate_path) }}" target="_blank" class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-sm font-medium hover:bg-blue-100 flex items-center gap-1">HACCP <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg></a>
                            @endif
                            @if($user->fda_certificate_path)
                                <a href="{{ Storage::url($user->fda_certificate_path) }}" target="_blank" class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-sm font-medium hover:bg-blue-100 flex items-center gap-1">FDA <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg></a>
                            @endif
                            @if($user->halal_certificate_path)
                                <a href="{{ Storage::url($user->halal_certificate_path) }}" target="_blank" class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-sm font-medium hover:bg-blue-100 flex items-center gap-1">Halal <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg></a>
                            @endif
                            @if($user->son_certificate_path)
                                <a href="{{ Storage::url($user->son_certificate_path) }}" target="_blank" class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-sm font-medium hover:bg-blue-100 flex items-center gap-1">SON <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg></a>
                            @endif
                            
                            @if(!$user->haccp_certificate_path && !$user->fda_certificate_path && !$user->halal_certificate_path && !$user->son_certificate_path)
                                <span class="text-gray-500 text-sm italic">No additional certifications documented.</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Export Readiness -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center gap-2 mb-4 border-b pb-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    <h3 class="text-lg font-bold text-gray-800">Export Capacity & Readiness</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
                    <div>
                        <span class="block text-xs text-gray-500 uppercase">Product Category</span>
                        <span class="block text-gray-900 font-medium">{{ $user->product_category ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500 uppercase">Exported Before?</span>
                        <span class="block text-gray-900 font-medium">{{ $user->exported_before ? 'Yes' : 'No' }}</span>
                    </div>
                    @if($user->exported_before)
                    <div>
                        <span class="block text-xs text-gray-500 uppercase">Recent Export Activity</span>
                        <span class="block text-gray-900 font-medium">{{ $user->recent_export_activity ?? 'N/A' }}</span>
                    </div>
                    @endif
                    <div>
                        <span class="block text-xs text-gray-500 uppercase">Commercial Scale</span>
                        <span class="block text-gray-900 font-medium">{{ $user->commercial_scale ? 'Yes' : 'No' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500 uppercase">Packaged for Retail</span>
                        <span class="block text-gray-900 font-medium">{{ $user->packaged_for_retail ? 'Yes' : 'No' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500 uppercase">Production Location</span>
                        <span class="block text-gray-900 font-medium">{{ $user->production_location ?? 'N/A' }}</span>
                    </div>
                    <div class="md:col-span-2">
                        <span class="block text-xs text-gray-500 uppercase">Export Objective at Event</span>
                        <p class="text-gray-900 font-medium mt-1 bg-gray-50 p-3 rounded border border-gray-100">{{ $user->export_objective ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
            @else
            <!-- Non-Exporter Guest Details placeholder if any -->
            <div class="bg-white rounded-lg shadow-md p-6 text-center text-gray-500">
                This user is a standard guest and does not have strictly collected exporter data.
            </div>
            @endif
        </div>
    </div>
@endsection
