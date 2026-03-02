<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('speaker_invitations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('summit_id')->nullable();
            $table->string('token', 100)->unique();
            $table->enum('status', ['pending', 'confirmed'])->default('pending');

            // Contact & Identity
            $table->string('full_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('organization')->nullable();
            $table->string('role_title')->nullable();

            // Location
            $table->string('state')->nullable();
            $table->string('preferred_location')->nullable(); // port_harcourt | lagos | kano

            // Speaking details
            $table->enum('session_type', ['keynote', 'presentation', 'panel', 'chat'])->nullable();
            $table->string('speaking_topic')->nullable();
            $table->text('bio')->nullable();
            $table->string('profile_photo_path')->nullable();

            // Confirmation
            $table->boolean('physical_attendance')->default(true);
            $table->timestamp('confirmed_at')->nullable();

            // Linked account after registration
            $table->unsignedBigInteger('registered_user_id')->nullable();
            $table->foreign('registered_user_id')->references('id')->on('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('speaker_invitations');
    }
};
