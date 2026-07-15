<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommunityPost;
use App\Models\CommunityComment;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    /**
     * List all community posts with stats.
     */
    public function index(Request $request)
    {
        $query = CommunityPost::with(['user', 'pollOptions'])
            ->withCount(['likes', 'comments'])
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('type') && in_array($request->type, ['text', 'poll'])) {
            $query->where('type', $request->type);
        }

        $posts = $query->paginate(15)->withQueryString();

        $totalPosts = CommunityPost::count();
        $totalComments = CommunityComment::count();
        
        // Active users (users who have posted or commented)
        $posters = CommunityPost::select('user_id')->distinct()->pluck('user_id');
        $commenters = CommunityComment::select('user_id')->distinct()->pluck('user_id');
        $activeUsersCount = $posters->merge($commenters)->unique()->count();

        // Engagement rate (average interactions per post)
        // Engagements = Likes + Comments + Poll Votes
        $totalLikes = \App\Models\CommunityLike::count();
        $totalVotes = \App\Models\CommunityPollVote::count();
        $totalEngagements = $totalLikes + $totalComments + $totalVotes;
        
        $engagementRate = $totalPosts > 0 ? round($totalEngagements / $totalPosts, 1) : 0;

        $stats = [
            'total_posts'    => $totalPosts,
            'total_comments' => $totalComments,
            'active_users'   => $activeUsersCount,
            'engagement_rate'=> $engagementRate,
        ];

        return view('admin.community.index', compact('posts', 'stats'));
    }

    /**
     * Show a single post with its comments and stats.
     */
    public function show(CommunityPost $post)
    {
        $post->load(['user', 'pollOptions.votes', 'likes']);

        $comments = CommunityComment::with(['user', 'replies.user'])
            ->where('community_post_id', $post->id)
            ->whereNull('parent_id')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.community.show', compact('post', 'comments'));
    }

    /**
     * Toggle pin status of a post.
     */
    public function pin(CommunityPost $post)
    {
        $post->is_pinned = !$post->is_pinned;
        $post->save();

        $status = $post->is_pinned ? 'pinned' : 'unpinned';
        return back()->with('success', "Post has been {$status} successfully.");
    }

    /**
     * Delete a post.
     */
    public function destroy(CommunityPost $post)
    {
        $post->delete();
        return redirect()->route('admin.community.index')
            ->with('success', 'Post deleted successfully.');
    }

    /**
     * Delete a comment.
     */
    public function destroyComment(CommunityComment $comment)
    {
        $postId = $comment->community_post_id;
        $comment->delete();
        return redirect()->route('admin.community.show', $postId)
            ->with('success', 'Comment deleted successfully.');
    }
}
