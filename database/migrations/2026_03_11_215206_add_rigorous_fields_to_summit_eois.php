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
        Schema::table('summit_eois', function (Blueprint $table) {
            // Rigorous fields migrated from Registration
            $table->string('business_address')->nullable()->after('business_name');
            $table->string('year_established')->nullable()->after('business_address');
            $table->string('business_structure')->nullable()->after('year_established');
            $table->string('cac_number')->nullable()->after('business_structure');

            // Certificate paths
            $table->string('cac_certificate')->nullable();
            $table->string('nepc_certificate')->nullable();
            $table->string('haccp_certificate')->nullable();
            $table->string('fda_certificate')->nullable();
            $table->string('halal_certificate')->nullable();
            $table->string('son_certificate')->nullable();

            // Production & Strategy
            $table->string('production_location')->nullable();
            $table->boolean('production_compliant')->default(false);
            $table->string('production_capacity')->nullable();
            $table->text('active_channels')->nullable();
            $table->string('sales_model')->nullable();
            $table->string('export_objective')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('summit_eois', function (Blueprint $table) {
            $table->dropColumn([
                'business_address',
                'year_established',
                'business_structure',
                'cac_number',
                'cac_certificate',
                'nepc_certificate',
                'haccp_certificate',
                'fda_certificate',
                'halal_certificate',
                'son_certificate',
                'production_location',
                'production_compliant',
                'production_capacity',
                'active_channels',
                'sales_model',
                'export_objective',
            ]);
        });
    }
};
