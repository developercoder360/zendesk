<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_settings', function (Blueprint $table) {
            $table->id();
            $table->uuid('tenant_id')->unique();
            $table->boolean('ai_mode_enabled')->default(false);
            $table->boolean('fallback_to_human')->default(true);
            $table->text('welcome_message')->nullable();
            $table->string('model_version')->default('gpt-4o');
            $table->timestamps();
            
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
        });
        DB::statement('ALTER TABLE ai_settings ENABLE ROW LEVEL SECURITY');
        DB::statement('CREATE POLICY tenant_isolation_policy ON ai_settings USING (tenant_id = NULLIF(current_setting(\'app.current_tenant_id\', true), \'\')::uuid)');
        DB::statement('CREATE POLICY tenant_isolation_bypass ON ai_settings USING (current_setting(\'app.bypass_rls\', true) = \'on\')');
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_settings');
    }
};
