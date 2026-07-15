<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CommunityPost;
use App\Models\CommunityComment;
use App\Models\CommunityLike;
use App\Models\CommunityPollOption;
use App\Models\CommunityPollVote;
use App\Models\UserNotification;
use App\Http\Resources\CommunityPostResource;
use App\Http\Resources\CommunityCommentResource;
use Illuminate\Support\Facades\DB;

class CommunityController extends Controller
{
    /**
     * Fetch community feed.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = CommunityPost::with(['user', 'pollOptions'])
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $posts = $query->paginate(10);

        return CommunityPostResource::collection($posts);
    }
    
    /**
     * Show a single post.
     */
    public function show(Request $request, $id)
    {
        $post = CommunityPost::with(['user', 'pollOptions'])->findOrFail($id);
        return response()->json([
            'data' => new CommunityPostResource($post)
        ]);
    }

    /**
     * Create a new post (text or poll).
     */
    public function store(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:text,poll',
            'poll_options' => 'required_if:type,poll|array|min:2',
            'poll_options.*' => 'required_if:type,poll|string|max:100',
        ]);

        return DB::transaction(function() use ($validated, $user) {
            $post = CommunityPost::create([
                'user_id' => $user->id,
                'title' => $validated['title'],
                'content' => $validated['content'],
                'type' => $validated['type'],
                'is_pinned' => false,
            ]);

            if ($validated['type'] === 'poll') {
                foreach ($validated['poll_options'] as $optionText) {
                    CommunityPollOption::create([
                        'community_post_id' => $post->id,
                        'option_text' => $optionText,
                    ]);
                }
            }

            return response()->json([
                'message' => 'Post created successfully',
                'data' => new CommunityPostResource($post->load('pollOptions'))
            ], 201);
        });
    }

    /**
     * Delete a post.
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $post = CommunityPost::findOrFail($id);

        // Check permission (author or admin)
        if ($post->user_id !== $user->id && !$user->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }

    /**
     * Like/Unlike a post.
     */
    public function like(Request $request, $id)
    {
        $user = $request->user();
        $post = CommunityPost::findOrFail($id);

        $existingLike = CommunityLike::where('user_id', $user->id)
            ->where('community_post_id', $post->id)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $liked = false;
        } else {
            CommunityLike::create([
                'user_id' => $user->id,
                'community_post_id' => $post->id,
            ]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $post->likes()->count()
        ]);
    }

    /**
     * Vote in a poll post.
     */
    public function vote(Request $request, $id)
    {
        $user = $request->user();
        $post = CommunityPost::findOrFail($id);

        if ($post->type !== 'poll') {
            return response()->json(['message' => 'Post is not a poll'], 400);
        }

        $validated = $request->validate([
            'community_poll_option_id' => 'required|exists:community_poll_options,id',
        ]);

        $option = CommunityPollOption::findOrFail($validated['community_poll_option_id']);
        if ($option->community_post_id !== $post->id) {
            return response()->json(['message' => 'Invalid poll option'], 400);
        }

        $existingVote = CommunityPollVote::where('user_id', $user->id)
            ->where('community_post_id', $post->id)
            ->first();

        if ($existingVote) {
            return response()->json(['message' => 'User has already voted in this poll'], 400);
        }

        CommunityPollVote::create([
            'community_post_id' => $post->id,
            'community_poll_option_id' => $option->id,
            'user_id' => $user->id,
        ]);

        // Trigger Notification to poll author if they are not the voter
        if ($post->user_id !== $user->id) {
            UserNotification::create([
                'user_id' => $post->user_id,
                'title' => 'Poll Activity',
                'message' => "Someone voted on your poll: '{$post->title}'",
                'type' => 'poll_vote',
                'reference_id' => $post->id,
            ]);
        }

        return response()->json([
            'message' => 'Vote cast successfully',
            'data' => new CommunityPostResource($post->load('pollOptions'))
        ]);
    }

    /**
     * Pin/Unpin a post.
     */
    public function pin(Request $request, $id)
    {
        $user = $request->user();
        if (!$user->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post = CommunityPost::findOrFail($id);
        $post->is_pinned = !$post->is_pinned;
        $post->save();

        return response()->json([
            'message' => $post->is_pinned ? 'Post pinned successfully' : 'Post unpinned successfully',
            'data' => new CommunityPostResource($post)
        ]);
    }

    /**
     * Fetch post comments.
     */
    public function comments(Request $request, $id)
    {
        $post = CommunityPost::findOrFail($id);
        
        // Fetch top-level comments and load their replies recursively
        $comments = CommunityComment::with(['user', 'replies.user'])
            ->where('community_post_id', $post->id)
            ->whereNull('parent_id')
            ->orderBy('created_at', 'asc')
            ->get();

        return CommunityCommentResource::collection($comments);
    }

    /**
     * Add a comment/reply.
     */
    public function storeComment(Request $request, $id)
    {
        $user = $request->user();
        $post = CommunityPost::findOrFail($id);

        $validated = $request->validate([
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:community_comments,id',
        ]);

        $comment = CommunityComment::create([
            'community_post_id' => $post->id,
            'user_id' => $user->id,
            'parent_id' => $validated['parent_id'],
            'content' => $validated['content'],
        ]);

        // Send notifications
        if ($validated['parent_id']) {
            // Reply to comment
            $parentComment = CommunityComment::findOrFail($validated['parent_id']);
            if ($parentComment->user_id !== $user->id) {
                UserNotification::create([
                    'user_id' => $parentComment->user_id,
                    'title' => 'New Reply',
                    'message' => "{$user->name} replied to your comment: '" . substr($parentComment->content, 0, 30) . "...'",
                    'type' => 'reply',
                    'reference_id' => $post->id,
                ]);
            }
        } else {
            // Comment on post
            if ($post->user_id !== $user->id) {
                UserNotification::create([
                    'user_id' => $post->user_id,
                    'title' => 'New Comment',
                    'message' => "{$user->name} commented on your post: '{$post->title}'",
                    'type' => 'comment',
                    'reference_id' => $post->id,
                ]);
            }
        }

        return response()->json([
            'message' => 'Comment added successfully',
            'data' => new CommunityCommentResource($comment->load('user'))
        ], 201);
    }

    /**
     * Delete comment.
     */
    public function destroyComment(Request $request, $id)
    {
        $user = $request->user();
        $comment = CommunityComment::findOrFail($id);

        // Allow comment author, post author, or admin to delete comment
        if ($comment->user_id !== $user->id && $comment->post->user_id !== $user->id && !$user->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    }

    /**
     * Report post or comment.
     */
    public function report(Request $request, $type, $id)
    {
        if ($type === 'post') {
            $item = CommunityPost::findOrFail($id);
        } elseif ($type === 'comment') {
            $item = CommunityComment::findOrFail($id);
        } else {
            return response()->json(['message' => 'Invalid report target'], 400);
        }

        $item->reports_count += 1;
        $item->save();

        return response()->json(['message' => 'Content reported successfully for review']);
    }
}
