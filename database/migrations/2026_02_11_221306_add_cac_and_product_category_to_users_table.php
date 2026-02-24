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
            $table->string('cac_number')->nullable()->after('business_name');
            $table->enum('product_category', ['Agricultural Products', 'Manufactured Goods', 'Solid Minerals', 'Services', 'Other'])->nullable()->after('cac_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cac_number', 'product_category']);
        });
    }
};
