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
        Schema::table('summits', function (Blueprint $table) {
            $table->boolean('is_eoi_open')->default(false)->after('is_active');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->string('category')->default('general')->after('cover_image_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('summits', function (Blueprint $table) {
            $table->dropColumn('is_eoi_open');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
