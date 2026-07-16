<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE EXTENSION IF NOT EXISTS vector;');
        
        Schema::create('knowledge_base_embeddings', function (Blueprint $table) {
            $table->id();
            $table->uuid('tenant_id')->index();
            $table->unsignedBigInteger('document_id')->index();
            $table->text('chunk_text');
            $table->string('embedding_model_version');
            $table->timestamps();
            
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->foreign('document_id')->references('id')->on('knowledge_base_documents')->cascadeOnDelete();
        });
        
        // Add vector column and HNSW index
        DB::statement('ALTER TABLE knowledge_base_embeddings ADD COLUMN embedding vector(1536)');
        DB::statement('CREATE INDEX kb_embeddings_hnsw_idx ON knowledge_base_embeddings USING hnsw (embedding vector_cosine_ops)');
        
        DB::statement('ALTER TABLE knowledge_base_embeddings ENABLE ROW LEVEL SECURITY');
        DB::statement('CREATE POLICY tenant_isolation_policy ON knowledge_base_embeddings USING (tenant_id = NULLIF(current_setting(\'app.current_tenant_id\', true), \'\')::uuid)');
        DB::statement('CREATE POLICY tenant_isolation_bypass ON knowledge_base_embeddings USING (current_setting(\'app.bypass_rls\', true) = \'on\')');
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge_base_embeddings');
    }
};
