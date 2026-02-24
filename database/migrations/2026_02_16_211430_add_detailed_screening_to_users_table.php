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
            $table->string('business_address')->nullable()->after('business_name');
            $table->string('year_established')->nullable()->after('business_address');
            $table->string('business_structure')->nullable()->after('year_established');
            $table->string('cac_certificate_path')->nullable()->after('cac_number');
            $table->string('nepc_status')->nullable()->after('registered_with_nepc');
            $table->string('nepc_certificate_path')->nullable()->after('nepc_status');
            $table->string('recent_export_activity')->nullable()->after('nepc_certificate_path');

            // Evidence of Export Readiness
            $table->boolean('commercial_scale')->default(false)->after('recent_export_activity');
            $table->boolean('packaged_for_retail')->default(false)->after('commercial_scale');
            $table->boolean('regulatory_registration')->default(false)->after('packaged_for_retail');
            $table->boolean('engaged_logistics')->default(false)->after('regulatory_registration');
            $table->boolean('received_inquiries')->default(false)->after('engaged_logistics');

            // Production & Operational Integrity
            $table->string('production_location')->nullable()->after('received_inquiries');
            $table->boolean('production_compliant')->default(false)->after('production_location');
            $table->string('production_capacity')->nullable()->after('production_compliant');

            // Market Presence & Commercial Behaviour
            $table->text('active_channels')->nullable()->after('production_capacity'); // JSON/Cast
            $table->string('sales_model')->nullable()->after('active_channels');

            // Export Objective
            $table->string('export_objective')->nullable()->after('sales_model');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'business_address',
                'year_established',
                'business_structure',
                'cac_certificate_path',
                'nepc_status',
                'nepc_certificate_path',
                'recent_export_activity',
                'commercial_scale',
                'packaged_for_retail',
                'regulatory_registration',
                'engaged_logistics',
                'received_inquiries',
                'production_location',
                'production_compliant',
                'production_capacity',
                'active_channels',
                'sales_model',
                'export_objective'
            ]);
        });
    }
};
