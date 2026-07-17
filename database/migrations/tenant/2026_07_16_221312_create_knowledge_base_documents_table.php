<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('knowledge_base_documents', function (Blueprint $table) {
            $table->id();
            $table->uuid('tenant_id')->index();
            $table->string('title');
            $table->enum('source_type', ['upload', 'url', 'manual']);
            $table->string('source_reference')->nullable();
            $table->enum('status', ['processing', 'ready', 'failed'])->default('processing');
            $table->timestamps();
            
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
        });
        DB::statement('ALTER TABLE knowledge_base_documents ENABLE ROW LEVEL SECURITY');
        DB::statement('CREATE POLICY tenant_isolation_policy ON knowledge_base_documents USING (tenant_id = NULLIF(current_setting(\'app.current_tenant_id\', true), \'\')::uuid)');
        DB::statement('CREATE POLICY tenant_isolation_bypass ON knowledge_base_documents USING (current_setting(\'app.bypass_rls\', true) = \'on\')');
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge_base_documents');
    }
};
