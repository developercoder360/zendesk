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
        Schema::table('visitors', function (Blueprint $table) {
            $table->unsignedBigInteger('served_by_agent_id')->nullable()->after('session_id');
            $table->string('current_page_title')->nullable()->after('current_page_url');
            $table->integer('open_tabs_count')->default(1)->after('current_page_title');
            $table->string('referrer')->nullable()->after('open_tabs_count');
            $table->integer('visits_count')->default(1)->after('referrer');
            $table->string('country_code', 2)->nullable()->after('visits_count');
            $table->string('device_type')->nullable()->after('country_code');
            $table->string('browser')->nullable()->after('device_type');
            $table->string('status')->default('online')->after('browser');

            $table->foreign('served_by_agent_id')->references('id')->on('tenant_users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropForeign(['served_by_agent_id']);
            $table->dropColumn([
                'served_by_agent_id',
                'current_page_title',
                'open_tabs_count',
                'referrer',
                'visits_count',
                'country_code',
                'device_type',
                'browser',
                'status',
            ]);
        });
    }
};
