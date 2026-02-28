<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('summit_eois', function (Blueprint $table) {
            $table->id();
            $table->foreignId('summit_id')->constrained()->onDelete('cascade');

            // Section A: Contact & Business Identity
            $table->string('full_name');
            $table->string('phone');
            $table->string('email');
            $table->string('business_name');
            $table->string('state');
            $table->enum('preferred_location', ['port_harcourt', 'lagos', 'kano']);
            $table->enum('how_heard', ['nepc', 'bank', 'industry_association', 'word_of_mouth', 'other']);

            // Section B: Business Profile & Export Status
            $table->enum('sector', [
                'agro_processing',
                'solid_minerals',
                'manufacturing',
                'services',
                'multiple',
                'other'
            ]);
            $table->string('primary_products');
            $table->enum('cac_registration', ['yes', 'no', 'in_progress']);
            $table->enum('nepc_registration', ['yes', 'no', 'in_progress']);
            $table->enum('export_status', [
                'currently_exporting',
                'exported_before',
                'export_ready',
                'exploring'
            ]);
            $table->enum('recent_export_value', [
                'above_50m',
                '10m_to_50m',
                'below_10m',
                'no_export_yet'
            ]);

            // Section C: Additional Information
            $table->boolean('commercial_scale')->default(false);
            $table->boolean('regulatory_registration')->default(false);
            $table->string('regulatory_body')->nullable();
            // JSON array of selected certifications
            $table->json('certifications')->nullable();
            // JSON array: up to 2 seminar goals
            $table->json('seminar_goals')->nullable();

            // System / Admin fields
            $table->enum('status', ['pending', 'selected', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('selected_at')->nullable();
            // One-time token generated when admin selects; used to unlock registration
            $table->string('registration_token')->unique()->nullable();
            // Linked once they complete full registration
            $table->foreignId('registered_user_id')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();

            // One EOI per email per summit
            $table->unique(['email', 'summit_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('summit_eois');
    }
};
