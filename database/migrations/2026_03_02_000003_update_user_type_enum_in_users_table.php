<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQLite, we can't easily modify ENUM constraints if they exist,
        // but Laravel handles basic column changes if doctrine/dbal is installed.
        // If not, we can just treat it as a string for now.
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_type')->default('exporter')->change();
        });

        // Update existing guests to delegates
        DB::table('users')->where('user_type', 'guest')->update(['user_type' => 'delegate']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN user_type ENUM('guest', 'exporter') DEFAULT 'exporter'");
    }
};
