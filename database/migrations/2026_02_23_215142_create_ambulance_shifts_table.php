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
        Schema::create('ambulance_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->boolean('is_reserve')->default(false);
            $table->timestamps();

            // Ensure a user can only have one record per date (either shift or reserve)
            // Wait, "The personnel will be able to put themselves as a reserve if a day has already been selected by another personnel"
            // If they are reserve, it's still a record for that day.
            // "The days cannot be overlapping for the personnel" -> Unique(user_id, date)
            $table->unique(['user_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ambulance_shifts');
    }
};
