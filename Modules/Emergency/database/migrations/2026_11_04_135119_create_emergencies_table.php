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
        Schema::create('emergencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained()->cascadeOnDelete();
            $table->string("status")->default("active")->index();
            $table->decimal("latest_latitude", 10, 7)->nullable();
            $table->decimal("latest_longitude", 10, 7)->nullable();
            $table->string("latest_address")->nullable();
            $table->timestamp("started_at")->useCurrent();
            $table->timestamp("ended_at")->nullable();       
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emergencies');
    }
};
