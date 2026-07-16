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
        Schema::table('tickets', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable()->after('tenant_id')->constrained('customers')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });

        Schema::table('ticket_replies', function (Blueprint $table) {
            $table->foreignId('customer_id')->nullable()->after('user_id')->constrained('customers')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');
            // Reverting user_id to not nullable might fail if there's null data, but we'll try
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });

        Schema::table('ticket_replies', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }
};
