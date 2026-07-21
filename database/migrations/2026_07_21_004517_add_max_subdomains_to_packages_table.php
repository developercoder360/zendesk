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
        Schema::table('packages', function (Blueprint $table) {
            $table->integer('max_subdomains')->nullable()->after('ai_mode_allowed');
        });

        foreach (\App\Models\Package::all() as $package) {
            if ($package->name === 'Support Team') {
                $package->max_subdomains = 1;
            } elseif ($package->name === 'Professional') {
                $package->max_subdomains = 3;
            } elseif ($package->name === 'Enterprise') {
                $package->max_subdomains = null;
            }
            $package->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('max_subdomains');
        });
    }
};
