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
            $table->string('haccp_certificate_path')->nullable()->after('nepc_certificate_path');
            $table->string('fda_certificate_path')->nullable()->after('haccp_certificate_path');
            $table->string('halal_certificate_path')->nullable()->after('fda_certificate_path');
            $table->string('son_certificate_path')->nullable()->after('halal_certificate_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
