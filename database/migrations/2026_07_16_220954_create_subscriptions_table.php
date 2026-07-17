<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->uuid('tenant_id')->index();
            $table->unsignedBigInteger('package_id')->index();
            $table->string('gateway_subscription_id')->nullable();
            $table->enum('status', ['active', 'cancelled', 'past_due'])->default('active');
            $table->timestamp('starts_at');
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
            
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
            $table->foreign('package_id')->references('id')->on('packages')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};