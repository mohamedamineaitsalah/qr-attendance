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
        Schema::table('persons', function (Blueprint $table) {
            $table->index('status');
            $table->index('qr_token');
        });

        Schema::table('attendance', function (Blueprint $table) {
            $table->index('action');
            $table->index('date');
            $table->index('person_id');
        });

        Schema::table('rejected_scans', function (Blueprint $table) {
            $table->index('qr_token');
        });
    }

    public function down(): void
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['qr_token']);
        });

        Schema::table('attendance', function (Blueprint $table) {
            $table->dropIndex(['action']);
            $table->dropIndex(['date']);
            $table->dropIndex(['person_id']);
        });

        Schema::table('rejected_scans', function (Blueprint $table) {
            $table->dropIndex(['qr_token']);
        });
    }
};
