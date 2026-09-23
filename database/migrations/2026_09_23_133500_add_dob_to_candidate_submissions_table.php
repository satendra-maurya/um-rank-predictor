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
        Schema::table('candidate_submissions', function (Blueprint $table) {
            $table->date('dob')->nullable()->after('candidate_identifier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidate_submissions', function (Blueprint $table) {
            $table->dropColumn('dob');
        });
    }
};
