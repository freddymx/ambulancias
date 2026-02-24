<?php

use App\Enums\ShiftStatus;
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
        Schema::table('ambulance_shifts', function (Blueprint $table) {
            $table->string('status')->default(ShiftStatus::Pending->value)->after('is_reserve');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ambulance_shifts', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
