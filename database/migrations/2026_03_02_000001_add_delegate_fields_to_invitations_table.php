<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            // Drop the old FK constraints first so we can restructure
            $table->dropForeign(['user_id']);
            $table->dropForeign(['event_id']);

            // Make user_id & event_id nullable (invite comes BEFORE user account)
            $table->foreignId('user_id')->nullable()->change();
            $table->foreignId('event_id')->nullable()->change();

            // Token-based invite fields
            $table->string('token', 100)->nullable()->unique()->after('invited_by');
            $table->string('invite_type')->default('delegate')->after('token'); // delegate | speaker

            // Delegate confirmation details
            $table->string('full_name')->nullable()->after('invite_type');
            $table->string('phone')->nullable()->after('full_name');
            $table->string('email')->nullable()->after('phone');
            $table->string('organization')->nullable()->after('email');
            $table->string('role_title')->nullable()->after('organization');
            $table->string('state')->nullable()->after('role_title');
            $table->string('preferred_location')->nullable()->after('state');
            $table->text('areas_of_interest')->nullable()->after('preferred_location');
            $table->boolean('physical_attendance')->nullable()->after('areas_of_interest');
            $table->string('how_received_invitation')->nullable()->after('physical_attendance');
            $table->timestamp('confirmed_at')->nullable()->after('how_received_invitation');
            $table->unsignedBigInteger('summit_id')->nullable()->after('event_id');

            // Re-add FK constraints as nullable
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->dropColumn([
                'token',
                'invite_type',
                'full_name',
                'phone',
                'email',
                'organization',
                'role_title',
                'state',
                'preferred_location',
                'areas_of_interest',
                'physical_attendance',
                'how_received_invitation',
                'confirmed_at',
                'summit_id',
            ]);
        });
    }
};
