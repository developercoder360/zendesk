<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration {
    public function up(): void {
        Schema::create('widget_settings', function (Blueprint $table) {
            $table->id();
            $table->uuid('tenant_id')->unique();
            $table->string('primary_color')->default('#000000');
            $table->string('welcome_text')->nullable();
            // Note: embed_key is a public identifier generated via Str::random(40). 
            // It is not a secret credential and does not need to be hashed.
            $table->string('embed_key')->unique();
            $table->jsonb('allowed_domains')->nullable();
            $table->jsonb('business_hours')->nullable();
            $table->text('offline_message')->nullable();
            $table->timestamps();
            
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
        });
        DB::statement('ALTER TABLE widget_settings ENABLE ROW LEVEL SECURITY');
        DB::statement('CREATE POLICY tenant_isolation_policy ON widget_settings USING (tenant_id = NULLIF(current_setting(\'app.current_tenant_id\', true), \'\')::uuid)');
        DB::statement('CREATE POLICY tenant_isolation_bypass ON widget_settings USING (current_setting(\'app.bypass_rls\', true) = \'on\')');
    }
    public function down(): void {
        Schema::dropIfExists('widget_settings');
    }
};
