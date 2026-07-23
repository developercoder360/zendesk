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
        Schema::table('chats', function (Blueprint $table) {
            if (!Schema::hasColumn('chats', 'needs_human_escalation')) {
                $table->boolean('needs_human_escalation')->default(false)->after('status');
            }
            if (!Schema::hasColumn('chats', 'is_typing')) {
                $table->boolean('is_typing')->default(false)->after('needs_human_escalation');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chats', function (Blueprint $table) {
            if (Schema::hasColumn('chats', 'needs_human_escalation')) {
                $table->dropColumn('needs_human_escalation');
            }
            if (Schema::hasColumn('chats', 'is_typing')) {
                $table->dropColumn('is_typing');
            }
        });
    }
};
