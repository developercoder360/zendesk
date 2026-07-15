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
        Schema::table('plans', function (Blueprint $table) {
            $table->string('description')->nullable()->after('name');
            $table->integer('price_yearly')->nullable()->after('price');
            $table->boolean('is_popular')->default(false)->after('features');
            $table->boolean('is_active')->default(true)->after('is_popular');
            $table->string('cta_text')->nullable()->after('is_active');
            $table->string('cta_link')->nullable()->after('cta_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'price_yearly',
                'is_popular',
                'is_active',
                'cta_text',
                'cta_link',
            ]);
        });
    }
};
