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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->uuid('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            
            $table->string('subject');
            $table->text('description');
            $table->string('priority')->default('normal'); // low, normal, high, urgent
            
            $table->foreignId('status_id')->nullable()->constrained('ticket_statuses')->nullOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // The customer
            $table->foreignId('agent_id')->nullable()->constrained('users')->nullOnDelete(); // The assigned agent
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
