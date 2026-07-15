<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Claimed Certificates
        Schema::create('claimed_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('module_id');
            $table->string('module_title');
            $table->timestamp('claimed_at');
            $table->timestamps();

            $table->unique(['user_id', 'module_id']);
        });

        // 2. Community Posts
        Schema::create('community_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->enum('type', ['text', 'poll'])->default('text');
            $table->boolean('is_pinned')->default(false);
            $table->integer('reports_count')->default(0);
            $table->timestamps();
        });

        // 3. Community Poll Options (if type = 'poll')
        Schema::create('community_poll_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_post_id')->constrained()->onDelete('cascade');
            $table->string('option_text');
            $table->timestamps();
        });

        // 4. Community Poll Votes
        Schema::create('community_poll_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_post_id')->constrained()->onDelete('cascade');
            $table->foreignId('community_poll_option_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['community_post_id', 'user_id']);
        });

        // 5. Community Comments (with parent_id support for threads/replies)
        Schema::create('community_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_post_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('community_comments')->onDelete('cascade');
            $table->text('content');
            $table->integer('reports_count')->default(0);
            $table->timestamps();
        });

        // 6. Community Likes (on posts)
        Schema::create('community_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('community_post_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'community_post_id']);
        });

        // 7. User Notifications (alerting about comment replies, comments on posts, or poll votes)
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('message');
            $table->string('type'); // 'comment', 'reply', 'poll_vote'
            $table->unsignedBigInteger('reference_id')->nullable(); // post_id
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
        Schema::dropIfExists('community_likes');
        Schema::dropIfExists('community_comments');
        Schema::dropIfExists('community_poll_votes');
        Schema::dropIfExists('community_poll_options');
        Schema::dropIfExists('community_posts');
        Schema::dropIfExists('claimed_certificates');
    }
};
