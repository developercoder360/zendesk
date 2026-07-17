<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration {
    public function up(): void {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->uuid('tenant_id');
            $table->string('session_id');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('current_page_url')->nullable();
            $table->timestamp('first_seen_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->unique(['tenant_id', 'session_id']);
        });
        DB::statement('ALTER TABLE visitors ENABLE ROW LEVEL SECURITY');
        DB::statement('CREATE POLICY tenant_isolation_policy ON visitors USING (tenant_id = NULLIF(current_setting(\'app.current_tenant_id\', true), \'\')::uuid)');
        DB::statement('CREATE POLICY tenant_isolation_bypass ON visitors USING (current_setting(\'app.bypass_rls\', true) = \'on\')');
    }
    public function down(): void {
        Schema::dropIfExists('visitors');
    }
};
