@extends('layouts.admin')

@section('title', 'Post Details')

@section('content')
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('admin.community.index') }}" class="hover:text-blue-600 transition-colors">
            <i class="fas fa-comments mr-1"></i> Community
        </a>
        <i class="fas fa-chevron-right text-xs text-gray-300"></i>
        <span class="text-gray-700 font-medium truncate max-w-xs">{{ Str::limit($post->title, 40) }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Post Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Post Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                            {{ substr($post->user->name ?? '?', 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $post->user->name ?? 'Unknown User' }}</p>
                            <p class="text-xs text-gray-400">{{ $post->created_at->format('d M Y, h:i A') }} · {{ $post->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($post->is_pinned)
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-amber-50 text-amber-700 border border-amber-100">
                                <i class="fas fa-thumbtack mr-1"></i> Pinned
                            </span>
                        @endif
                        @if($post->type === 'poll')
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-purple-50 text-purple-700 border border-purple-100">
                                <i class="fas fa-poll mr-1"></i> Poll
                            </span>
                        @else
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                                <i class="fas fa-comment-alt mr-1"></i> Discussion
                            </span>
                        @endif
                    </div>
                </div>

                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-3">{{ $post->title }}</h2>
                    <div class="text-gray-600 text-sm leading-relaxed whitespace-pre-wrap">{{ $post->content }}</div>

                    {{-- Poll Options (if poll) --}}
                    @if($post->type === 'poll' && $post->pollOptions->isNotEmpty())
                        @php
                            $totalVotes = $post->pollOptions->sum(fn($opt) => $opt->votes ? $opt->votes->count() : 0);
                        @endphp
                        <div class="mt-6 space-y-3">
                            <h4 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                <i class="fas fa-chart-bar text-purple-500"></i>
                                Poll Results
                                <span class="text-gray-400 font-normal">({{ $totalVotes }} total vote{{ $totalVotes !== 1 ? 's' : '' }})</span>
                            </h4>
                            @foreach($post->pollOptions as $option)
                                @php
                                    $voteCount = $option->votes ? $option->votes->count() : 0;
                                    $pct = $totalVotes > 0 ? round(($voteCount / $totalVotes) * 100) : 0;
                                @endphp
                                <div class="relative bg-gray-50 rounded-lg overflow-hidden border border-gray-100">
                                    <div class="absolute inset-y-0 left-0 bg-purple-100 transition-all duration-500" style="width: {{ $pct }}%"></div>
                                    <div class="relative flex items-center justify-between px-4 py-3">
                                        <span class="text-sm font-medium text-gray-700">{{ $option->option_text }}</span>
                                        <span class="text-sm font-bold text-purple-700">{{ $voteCount }} ({{ $pct }}%)</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Post Actions --}}
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30 flex items-center justify-between">
                    <div class="flex items-center gap-5 text-sm text-gray-500">
                        <span class="flex items-center gap-1.5" title="Likes">
                            <i class="far fa-heart text-red-400"></i>
                            {{ $post->likes_count ?? 0 }} like{{ ($post->likes_count ?? 0) !== 1 ? 's' : '' }}
                        </span>
                        <span class="flex items-center gap-1.5" title="Comments">
                            <i class="far fa-comment text-blue-400"></i>
                            {{ $post->comments_count ?? 0 }} comment{{ ($post->comments_count ?? 0) !== 1 ? 's' : '' }}
                        </span>
                        @if($post->reports_count > 0)
                            <span class="flex items-center gap-1.5 text-red-500 font-medium">
                                <i class="fas fa-flag"></i>
                                {{ $post->reports_count }} report{{ $post->reports_count > 1 ? 's' : '' }}
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <form action="{{ route('admin.community.pin', $post) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit"
                                class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all {{ $post->is_pinned ? 'bg-amber-100 text-amber-700 hover:bg-amber-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                                <i class="fas fa-thumbtack mr-1"></i>
                                {{ $post->is_pinned ? 'Unpin' : 'Pin' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.community.destroy', $post) }}" method="POST" class="inline"
                            onsubmit="return confirm('Permanently delete this post and all its comments?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-3 py-1.5 text-xs font-medium bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-all">
                                <i class="fas fa-trash-alt mr-1"></i> Delete Post
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Comments Section --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-comments text-blue-500 mr-2"></i>
                        Comments ({{ $comments->count() }})
                    </h3>
                </div>

                @if($comments->isEmpty())
                    <div class="p-8 text-center">
                        <div class="bg-gray-50 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="far fa-comment text-gray-300 text-lg"></i>
                        </div>
                        <p class="text-gray-500 text-sm">No comments on this post yet</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach($comments as $comment)
                            {{-- Top-level comment --}}
                            <div class="p-5 hover:bg-gray-50/50 transition-colors" id="comment-{{ $comment->id }}">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gradient-to-br from-green-400 to-teal-500 flex items-center justify-center text-white font-bold text-xs">
                                        {{ substr($comment->user->name ?? '?', 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <span class="text-sm font-semibold text-gray-800">{{ $comment->user->name ?? 'Unknown' }}</span>
                                                <span class="text-xs text-gray-400 ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            <form action="{{ route('admin.community.comments.destroy', $comment) }}" method="POST" class="inline"
                                                onsubmit="return confirm('Delete this comment and all its replies?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all opacity-0 group-hover:opacity-100"
                                                    title="Delete Comment"
                                                    style="opacity: 0.5;"
                                                    onmouseover="this.style.opacity='1'"
                                                    onmouseout="this.style.opacity='0.5'">
                                                    <i class="fas fa-trash-alt text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1 leading-relaxed">{{ $comment->content }}</p>
                                        @if($comment->reports_count > 0)
                                            <span class="inline-flex items-center gap-1 mt-1.5 text-xs text-red-500 font-medium">
                                                <i class="fas fa-flag"></i> {{ $comment->reports_count }} report{{ $comment->reports_count > 1 ? 's' : '' }}
                                            </span>
                                        @endif

                                        {{-- Replies --}}
                                        @if($comment->replies->isNotEmpty())
                                            <div class="mt-3 ml-4 pl-4 border-l-2 border-gray-100 space-y-3">
                                                @foreach($comment->replies as $reply)
                                                    <div class="flex items-start gap-2.5" id="comment-{{ $reply->id }}">
                                                        <div class="flex-shrink-0 h-6 w-6 rounded-full bg-gradient-to-br from-orange-400 to-pink-500 flex items-center justify-center text-white font-bold text-[10px]">
                                                            {{ substr($reply->user->name ?? '?', 0, 1) }}
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <div class="flex items-center justify-between">
                                                                <div>
                                                                    <span class="text-xs font-semibold text-gray-700">{{ $reply->user->name ?? 'Unknown' }}</span>
                                                                    <span class="text-[10px] text-gray-400 ml-1.5">{{ $reply->created_at->diffForHumans() }}</span>
                                                                </div>
                                                                <form action="{{ route('admin.community.comments.destroy', $reply) }}" method="POST" class="inline"
                                                                    onsubmit="return confirm('Delete this reply?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="p-1 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded transition-all"
                                                                        title="Delete Reply"
                                                                        style="opacity: 0.3;"
                                                                        onmouseover="this.style.opacity='1'"
                                                                        onmouseout="this.style.opacity='0.3'">
                                                                        <i class="fas fa-times text-[10px]"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                            <p class="text-xs text-gray-600 mt-0.5">{{ $reply->content }}</p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Sidebar —  Post Metadata --}}
        <div class="space-y-6">
            {{-- Post Info Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50">
                    <h4 class="text-sm font-semibold text-gray-700">Post Information</h4>
                </div>
                <div class="p-5 space-y-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Post ID</span>
                        <span class="font-mono text-gray-700 bg-gray-50 px-2 py-0.5 rounded">#{{ $post->id }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Type</span>
                        <span class="font-medium text-gray-700 capitalize">{{ $post->type }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Pinned</span>
                        @if($post->is_pinned)
                            <span class="text-amber-600 font-medium"><i class="fas fa-check mr-1"></i> Yes</span>
                        @else
                            <span class="text-gray-400">No</span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Reports</span>
                        @if($post->reports_count > 0)
                            <span class="text-red-600 font-medium">{{ $post->reports_count }}</span>
                        @else
                            <span class="text-green-600">0</span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Created</span>
                        <span class="text-gray-700">{{ $post->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Last Updated</span>
                        <span class="text-gray-700">{{ $post->updated_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Author Info Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50">
                    <h4 class="text-sm font-semibold text-gray-700">Author</h4>
                </div>
                <div class="p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold shadow-sm">
                            {{ substr($post->user->name ?? '?', 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $post->user->name ?? 'Unknown' }}</p>
                            <p class="text-xs text-gray-400">{{ $post->user->email ?? '' }}</p>
                        </div>
                    </div>
                    @if($post->user)
                        <a href="{{ route('admin.users.show', $post->user) }}"
                            class="block w-full text-center px-4 py-2 text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-all">
                            <i class="fas fa-external-link-alt mr-1"></i> View User Profile
                        </a>
                    @endif
                </div>
            </div>

            {{-- Engagement Summary Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50">
                    <h4 class="text-sm font-semibold text-gray-700">Engagement</h4>
                </div>
                <div class="p-5 grid grid-cols-2 gap-4">
                    <div class="text-center p-3 bg-red-50/50 rounded-lg">
                        <p class="text-2xl font-bold text-red-500">{{ $post->likes_count ?? 0 }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Likes</p>
                    </div>
                    <div class="text-center p-3 bg-blue-50/50 rounded-lg">
                        <p class="text-2xl font-bold text-blue-500">{{ $post->comments_count ?? 0 }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">Comments</p>
                    </div>
                    @if($post->type === 'poll')
                        <div class="col-span-2 text-center p-3 bg-purple-50/50 rounded-lg">
                            <p class="text-2xl font-bold text-purple-500">{{ $post->pollOptions->sum(fn($o) => $o->votes ? $o->votes->count() : 0) }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">Poll Votes</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/50">
                    <h4 class="text-sm font-semibold text-gray-700">Quick Actions</h4>
                </div>
                <div class="p-4 space-y-2">
                    <form action="{{ route('admin.community.pin', $post) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium rounded-lg transition-all
                                {{ $post->is_pinned ? 'text-amber-700 bg-amber-50 hover:bg-amber-100' : 'text-gray-600 bg-gray-50 hover:bg-gray-100' }}">
                            <i class="fas fa-thumbtack w-4 text-center"></i>
                            {{ $post->is_pinned ? 'Unpin Post' : 'Pin Post' }}
                        </button>
                    </form>
                    <form action="{{ route('admin.community.destroy', $post) }}" method="POST"
                        onsubmit="return confirm('Permanently delete this post and all its comments?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-all">
                            <i class="fas fa-trash-alt w-4 text-center"></i>
                            Delete Post
                        </button>
                    </form>
                    <a href="{{ route('admin.community.index') }}"
                        class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium text-gray-600 bg-gray-50 hover:bg-gray-100 rounded-lg transition-all">
                        <i class="fas fa-arrow-left w-4 text-center"></i>
                        Back to Posts
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
