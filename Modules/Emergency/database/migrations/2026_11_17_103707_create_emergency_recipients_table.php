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
        Schema::create('emergency_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId("emergency_id")->constrained()->cascadeOnDelete();
            $table->foreignId("contact_user_id")->constrained("users")->onDelete("cascade");
            $table->string("status")->default("notified");
            $table->timestamps();
         }); 
        }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emergency_recipients');
    }
};
