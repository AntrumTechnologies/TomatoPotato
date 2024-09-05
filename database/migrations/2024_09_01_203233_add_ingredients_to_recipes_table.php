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
        Schema::table('recipes', function (Blueprint $table) {
            $table->string('ingredients')->nullable();
            $table->string('quantities')->nullable();
            $table->string('units')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('recipes', 'ingredients')) {
            Schema::table('recipes', function (Blueprint $table) {
                $table->dropColumn('ingredients');
            });
        }

        if (Schema::hasColumn('recipes', 'quantities')) {
            Schema::table('recipes', function (Blueprint $table) {
                $table->dropColumn('quantities');
            });
        }

        if (Schema::hasColumn('recipes', 'units')) {
            Schema::table('recipes', function (Blueprint $table) {
                $table->dropColumn('units');
            });
        }
    }
};
