<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('business_name')->nullable()->after('company');
            $table->boolean('registered_with_cac')->default(false)->after('business_name');
            $table->boolean('exported_before')->default(false)->after('registered_with_cac');
            $table->boolean('registered_with_nepc')->default(false)->after('exported_before');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['business_name', 'registered_with_cac', 'exported_before', 'registered_with_nepc']);
        });
    }
};
