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
        Schema::table('tenant_users', function (Blueprint $table) {
            $table->dropColumn(['name', 'email', 'password', 'role']);
            // Add user_id referencing central users table
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('shift')->nullable();
            
            $table->index(['tenant_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenant_users', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->enum('role', ['agent', 'manager', 'owner'])->default('agent');
            $table->dropForeign(['user_id']);
            $table->dropIndex(['tenant_id', 'user_id']);
            $table->dropColumn(['user_id', 'shift']);
        });
    }
};
