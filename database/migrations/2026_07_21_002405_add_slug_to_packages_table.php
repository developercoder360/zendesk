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
        // First drop the column if it was partially added
        if (Schema::hasColumn('packages', 'slug')) {
            Schema::table('packages', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }

        Schema::table('packages', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });

        // Backfill
        foreach (\App\Models\Package::all() as $package) {
            $package->slug = \Illuminate\Support\Str::slug($package->name . '-' . $package->billing_interval);
            $package->save();
        }

        Schema::table('packages', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
            $table->unique('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
