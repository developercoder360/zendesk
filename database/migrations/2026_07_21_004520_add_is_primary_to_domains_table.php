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
        Schema::table('domains', function (Blueprint $table) {
            $table->boolean('is_primary')->default(false)->after('domain');
        });

        foreach (\App\Models\Tenant::all() as $tenant) {
            $firstDomain = $tenant->domains()->orderBy('created_at')->first();
            if ($firstDomain) {
                $firstDomain->update(['is_primary' => true]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('domains', function (Blueprint $table) {
            $table->dropColumn('is_primary');
        });
    }
};
