<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('emergency_contacts', function (Blueprint $table) {
            $table->string('identifier_type')->nullable()->after('contact_user_id');
        });

        DB::statement("
            UPDATE emergency_contacts ec
            SET identifier_type = CASE
                WHEN u.email = (
                    SELECT email
                    FROM users
                    WHERE id = ec.contact_user_id
                )
                THEN 'email'
                ELSE 'username'
            END
            FROM users u
            WHERE u.id = ec.contact_user_id
        ");

        Schema::table('emergency_contacts', function (Blueprint $table) {
            $table->string('identifier_type')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('emergency_contacts', function (Blueprint $table) {
            $table->dropColumn('identifier_type');
        });
    }
};