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
        Schema::table('cpbs', function (Blueprint $table) {
            $table->string('cpb_number', 100)->nullable()->after('batch_number');
            $table->string('cpb_revision', 10)->nullable()->after('cpb_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cpbs', function (Blueprint $table) {
            $table->dropColumn(['cpb_number', 'cpb_revision']);
        });
    }
};
