<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_handoff_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('tenant_id')->index();
            $table->unsignedBigInteger('chat_id')->index();
            $table->enum('reason', ['agent_offline', 'agent_busy', 'escalated_by_ai']);
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->foreign('chat_id')->references('id')->on('chats')->cascadeOnDelete();
        });
        DB::statement('ALTER TABLE ai_handoff_logs ENABLE ROW LEVEL SECURITY');
        DB::statement('CREATE POLICY tenant_isolation_policy ON ai_handoff_logs USING (tenant_id = NULLIF(current_setting(\'app.current_tenant_id\', true), \'\')::uuid)');
        DB::statement('CREATE POLICY tenant_isolation_bypass ON ai_handoff_logs USING (current_setting(\'app.bypass_rls\', true) = \'on\')');
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_handoff_logs');
    }
};
