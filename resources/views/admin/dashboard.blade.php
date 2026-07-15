@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Dashboard Overview</h2>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Stats Cards -->
        <div
            class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-1">Total Users</h3>
                    <p class="text-3xl font-bold text-gray-800">{{ \App\Models\User::count() }}</p>
                </div>
                <div class="bg-blue-50 p-3 rounded-lg">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-gray-500">
                <span class="text-green-500 font-medium flex items-center mr-2">
                    <i class="fas fa-arrow-up mr-1"></i> 12%
                </span>
                <span>since last month</span>
            </div>
        </div>

        <div
            class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-1">Total Events</h3>
                    <p class="text-3xl font-bold text-gray-800">{{ \App\Models\Event::count() }}</p>
                </div>
                <div class="bg-green-50 p-3 rounded-lg">
                    <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-gray-500">
                <span class="text-green-500 font-medium flex items-center mr-2">
                    <i class="fas fa-arrow-up mr-1"></i> 5%
                </span>
                <span>since last month</span>
            </div>
        </div>

        <div
            class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-1">Active Roles</h3>
                    <p class="text-3xl font-bold text-gray-800">{{ \App\Models\Role::count() }}</p>
                </div>
                <div class="bg-purple-50 p-3 rounded-lg">
                    <i class="fas fa-user-tag text-purple-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-gray-500">
                <span class="text-gray-400 font-medium flex items-center mr-2">
                    <i class="fas fa-minus mr-1"></i> 0%
                </span>
                <span>since last month</span>
            </div>
        </div>

        <div
            class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-1">Pending Approvals</h3>
                    <p class="text-3xl font-bold text-amber-600">{{ $pendingCount }}</p>
                </div>
                <div class="bg-amber-50 p-3 rounded-lg">
                    <i class="fas fa-user-clock text-amber-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm text-gray-500">
                <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">Verify
                    Users &rarr;</a>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Users -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-lg font-semibold text-gray-800">Newest Members</h3>
                <a href="{{ route('admin.users.index') }}"
                    class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach(\App\Models\User::with('roles')->latest()->take(5)->get() as $user)
                                        <tr class="hover:bg-gray-50/80 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div
                                                        class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                                        {{ substr($user->name, 0, 1) }}
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-semibold text-gray-900">{{ $user->name }}</div>
                                                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <span
                                                    class="px-2 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full mr-2
                                                        {{ $user->status === 'approved' ? 'bg-green-100 text-green-800' :
                            ($user->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                    {{ ucfirst($user->status ?? 'Pending') }}
                                                </span>
                                                @foreach($user->roles as $role)
                                                                    <span
                                                                        class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-medium rounded-full 
                                                                                                                                        {{ $role->name === 'admin' ? 'bg-red-50 text-red-700 border border-red-100' :
                                                    ($role->name === 'staff' ? 'bg-blue-50 text-blue-700 border border-blue-100' : 'bg-gray-50 text-gray-700 border border-gray-100') }}">
                                                                        {{ ucfirst($role->name) }}
                                                                    </span>
                                                @endforeach
                                            </td>
                                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-lg font-semibold text-gray-800">Upcoming Events</h3>
                <a href="{{ route('admin.events.index') }}"
                    class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors">View All</a>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach(\App\Models\Event::where('start_time', '>', now())->orderBy('start_time')->take(5)->get() as $event)
                    <div class="p-4 hover:bg-gray-50/80 transition-colors flex items-start gap-4 group cursor-pointer">
                        <div
                            class="bg-blue-50 text-blue-600 rounded-xl p-2 text-center min-w-[60px] border border-blue-100 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                            <span
                                class="block text-xs font-bold uppercase tracking-wider">{{ $event->start_time->format('M') }}</span>
                            <span class="block text-xl font-bold">{{ $event->start_time->format('d') }}</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-gray-900 group-hover:text-blue-600 transition-colors">
                                {{ $event->title }}
                            </h4>
                            <div class="flex items-center text-xs text-gray-500 mt-1">
                                <i class="fas fa-map-marker-alt mr-1.5 text-gray-400"></i>
                                {{ $event->location }}
                            </div>
                            <div class="flex items-center text-xs text-gray-400 mt-1">
                                <i class="far fa-clock mr-1.5"></i>
                                {{ $event->start_time->format('h:i A') }}
                            </div>
                        </div>
                        <div class="self-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Community Activity --}}
    <div class="mt-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-comments text-blue-500 mr-2"></i>Community Activity
                </h3>
                <a href="{{ route('admin.community.index') }}"
                    class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors">Manage All</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-0 border-b border-gray-100">
                <div class="p-4 text-center border-r border-gray-100">
                    @php $postCount = \Illuminate\Support\Facades\Schema::hasTable('community_posts') ? \App\Models\CommunityPost::count() : 0; @endphp
                    <p class="text-2xl font-bold text-gray-800">{{ $postCount }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Total Posts</p>
                </div>
                <div class="p-4 text-center border-r border-gray-100">
                    @php $pollCount = \Illuminate\Support\Facades\Schema::hasTable('community_posts') ? \App\Models\CommunityPost::where('type', 'poll')->count() : 0; @endphp
                    <p class="text-2xl font-bold text-purple-600">{{ $pollCount }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Polls</p>
                </div>
                <div class="p-4 text-center border-r border-gray-100">
                    @php $commentCount = \Illuminate\Support\Facades\Schema::hasTable('community_comments') ? \App\Models\CommunityComment::count() : 0; @endphp
                    <p class="text-2xl font-bold text-blue-600">{{ $commentCount }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Comments</p>
                </div>
                <div class="p-4 text-center">
                    @php $reportedPosts = \Illuminate\Support\Facades\Schema::hasTable('community_posts') ? \App\Models\CommunityPost::where('reports_count', '>', 0)->count() : 0; @endphp
                    <p class="text-2xl font-bold {{ $reportedPosts > 0 ? 'text-red-600' : 'text-green-600' }}">{{ $reportedPosts }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">Reported</p>
                </div>
            </div>
            <div class="divide-y divide-gray-100">
                @php $recentPosts = \Illuminate\Support\Facades\Schema::hasTable('community_posts') ? \App\Models\CommunityPost::with('user')->latest()->take(5)->get() : []; @endphp
                @forelse($recentPosts as $post)
                    <a href="{{ route('admin.community.show', $post) }}"
                        class="p-4 hover:bg-gray-50/80 transition-colors flex items-start gap-3 group">
                        <div class="flex-shrink-0 h-9 w-9 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xs shadow-sm">
                            {{ substr($post->user->name ?? '?', 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                @if($post->is_pinned)
                                    <i class="fas fa-thumbtack text-amber-500 text-xs"></i>
                                @endif
                                <h4 class="text-sm font-semibold text-gray-800 group-hover:text-blue-600 transition-colors truncate">{{ $post->title }}</h4>
                                @if($post->type === 'poll')
                                    <span class="flex-shrink-0 px-1.5 py-0.5 text-[10px] font-semibold rounded bg-purple-50 text-purple-600">POLL</span>
                                @endif
                                @if($post->reports_count > 0)
                                    <span class="flex-shrink-0 px-1.5 py-0.5 text-[10px] font-semibold rounded bg-red-50 text-red-600">
                                        <i class="fas fa-flag mr-0.5"></i>{{ $post->reports_count }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $post->user->name ?? 'Unknown' }} · {{ $post->created_at->diffForHumans() }}
                                · <i class="far fa-heart text-red-300"></i> {{ $post->likes_count }}
                                · <i class="far fa-comment text-blue-300"></i> {{ $post->comments_count }}
                            </p>
                        </div>
                        <div class="self-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                        </div>
                    </a>
                @empty
                    <div class="p-6 text-center text-gray-400 text-sm">No community posts yet</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection