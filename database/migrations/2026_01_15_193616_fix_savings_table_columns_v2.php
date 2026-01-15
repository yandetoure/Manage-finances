<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('savings', function (Blueprint $table) {
            if (Schema::hasColumn('savings', 'goal_name')) {
                $table->renameColumn('goal_name', 'target_name');
            }
            if (!Schema::hasColumn('savings', 'deadline')) {
                $table->date('deadline')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('savings', function (Blueprint $table) {
            if (Schema::hasColumn('savings', 'target_name')) {
                $table->renameColumn('target_name', 'goal_name');
            }
            if (Schema::hasColumn('savings', 'deadline')) {
                $table->dropColumn('deadline');
            }
        });
    }
};
