@extends('layouts.admin')

@section('title', 'Community Management')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Community Management</h2>
            <p class="text-sm text-gray-500 mt-1">Monitor and moderate community posts, polls & discussions</p>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-1">Total Posts</h3>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total_posts'] }}</p>
                </div>
                <div class="bg-blue-50 p-3 rounded-lg">
                    <i class="fas fa-newspaper text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-1">Total Comments</h3>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total_comments'] }}</p>
                </div>
                <div class="bg-indigo-50 p-3 rounded-lg">
                    <i class="fas fa-comments text-indigo-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-1">Active Users</h3>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['active_users'] }}</p>
                </div>
                <div class="bg-green-50 p-3 rounded-lg">
                    <i class="fas fa-users text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-1">Engagement Rate</h3>
                    <p class="text-3xl font-bold text-purple-600">{{ $stats['engagement_rate'] }}<span class="text-lg font-medium text-purple-400">/post</span></p>
                </div>
                <div class="bg-purple-50 p-3 rounded-lg">
                    <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6">
        <form method="GET" action="{{ route('admin.community.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search posts by title, content, or author…"
                    class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" />
            </div>
            <select name="type"
                class="px-4 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white min-w-[140px]">
                <option value="">All Types</option>
                <option value="text" {{ request('type') == 'text' ? 'selected' : '' }}>Discussions</option>
                <option value="poll" {{ request('type') == 'poll' ? 'selected' : '' }}>Polls</option>
            </select>
            <button type="submit"
                class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors shadow-sm">
                <i class="fas fa-filter mr-1.5"></i> Filter
            </button>
            @if(request('search') || request('type'))
                <a href="{{ route('admin.community.index') }}"
                    class="px-5 py-2.5 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    Clear
                </a>
            @endif
        </form>
    </div>

    {{-- Posts Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-lg font-semibold text-gray-800">All Posts</h3>
        </div>

        @if($posts->isEmpty())
            <div class="p-12 text-center">
                <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-comments text-gray-300 text-2xl"></i>
                </div>
                <p class="text-gray-500 font-medium">No community posts found</p>
                <p class="text-gray-400 text-sm mt-1">Posts will appear here when users create them in the app</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/80">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Author & Post</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Engagement</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($posts as $post)
                            <tr class="hover:bg-gray-50/80 transition-colors group">
                                {{-- Author & Post --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                            {{ substr($post->user->name ?? '?', 0, 1) }}
                                        </div>
                                        <div class="min-w-0">
                                            <a href="{{ route('admin.community.show', $post) }}"
                                                class="text-sm font-semibold text-gray-900 hover:text-blue-600 transition-colors block truncate max-w-[280px]">
                                                @if($post->is_pinned)
                                                    <i class="fas fa-thumbtack text-amber-500 mr-1 text-xs"></i>
                                                @endif
                                                {{ $post->title }}
                                            </a>
                                            <p class="text-xs text-gray-500 mt-0.5">by {{ $post->user->name ?? 'Unknown' }}</p>
                                            <p class="text-xs text-gray-400 mt-0.5 truncate max-w-[280px]">{{ Str::limit($post->content, 60) }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Type --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($post->type === 'poll')
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-purple-50 text-purple-700 border border-purple-100">
                                            <i class="fas fa-poll mr-1"></i> Poll
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                                            <i class="fas fa-comment-alt mr-1"></i> Discussion
                                        </span>
                                    @endif
                                </td>

                                {{-- Engagement --}}
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-4 text-sm">
                                        <span class="flex items-center gap-1 text-gray-500" title="Likes">
                                            <i class="far fa-heart text-red-400"></i>
                                            {{ $post->likes_count ?? 0 }}
                                        </span>
                                        <span class="flex items-center gap-1 text-gray-500" title="Comments">
                                            <i class="far fa-comment text-blue-400"></i>
                                            {{ $post->comments_count ?? 0 }}
                                        </span>
                                        @if($post->type === 'poll')
                                            <span class="flex items-center gap-1 text-gray-500" title="Votes">
                                                <i class="fas fa-chart-bar text-purple-400"></i>
                                                {{ $post->pollOptions->sum(fn($o) => $o->votes()->count()) }}
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($post->reports_count > 0)
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-50 text-red-700 border border-red-100">
                                            <i class="fas fa-flag mr-1"></i> {{ $post->reports_count }} report{{ $post->reports_count > 1 ? 's' : '' }}
                                        </span>
                                    @elseif($post->is_pinned)
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-50 text-amber-700 border border-amber-100">
                                            <i class="fas fa-thumbtack mr-1"></i> Pinned
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-50 text-green-700 border border-green-100">
                                            Active
                                        </span>
                                    @endif
                                </td>

                                {{-- Created --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $post->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }}</div>
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('admin.community.show', $post) }}"
                                            class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                            title="View Details">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        <form action="{{ route('admin.community.pin', $post) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="p-2 text-gray-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all"
                                                title="{{ $post->is_pinned ? 'Unpin' : 'Pin' }}">
                                                <i class="fas fa-thumbtack text-sm {{ $post->is_pinned ? 'text-amber-500' : '' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.community.destroy', $post) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Delete this post and all its comments? This action cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                                title="Delete Post">
                                                <i class="fas fa-trash-alt text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($posts->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30">
                    {{ $posts->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection
