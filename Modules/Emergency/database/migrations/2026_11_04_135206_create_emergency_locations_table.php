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
        Schema::create('emergency_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId("emergency_id")->constrained()->cascadeOnDelete();
            $table->decimal("latitude", 10, 7);
            $table->decimal("longitude", 10, 7);
            $table->string("address")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emergency_locations');
    }
};
