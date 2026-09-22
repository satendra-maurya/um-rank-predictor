<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $binaryStatusTables = [
            'states',
            'exam_authorities',
            'exams',
            'exam_stages',
            'notices',
            'important_links',
            'categories',
            'shifts',
            'prediction_models',
        ];

        $isSqlite = DB::getDriverName() === 'sqlite';

        // 1. Convert binary status columns (ACTIVE -> 1, INACTIVE -> 0)
        foreach ($binaryStatusTables as $table) {
            DB::table($table)->where('status', 'ACTIVE')->update(['status' => '1']);
            DB::table($table)->where('status', 'INACTIVE')->update(['status' => '0']);
            DB::table($table)->whereNotIn('status', ['0', '1'])->update(['status' => '1']);

            if (! $isSqlite) {
                DB::statement("ALTER TABLE `{$table}` MODIFY COLUMN `status` TINYINT UNSIGNED NOT NULL DEFAULT 1");
            }
        }

        // 2. Convert exam_cycles status column
        DB::table('exam_cycles')->where('status', 'DRAFT')->update(['status' => '0']);
        DB::table('exam_cycles')->whereIn('status', ['ACTIVE', 'ONGOING', 'UPCOMING'])->update(['status' => '1']);
        DB::table('exam_cycles')->where('status', 'COMPLETED')->update(['status' => '2']);
        DB::table('exam_cycles')->whereIn('status', ['CANCELLED', 'ARCHIVED'])->update(['status' => '3']);
        DB::table('exam_cycles')->whereNotIn('status', ['0', '1', '2', '3'])->update(['status' => '0']);

        if (! $isSqlite) {
            DB::statement('ALTER TABLE `exam_cycles` MODIFY COLUMN `status` TINYINT UNSIGNED NOT NULL DEFAULT 0');
        }

        // 3. Convert candidate_submissions trust_status column
        DB::table('candidate_submissions')->where('trust_status', 'TRUSTED')->update(['trust_status' => '1']);
        DB::table('candidate_submissions')->whereIn('trust_status', ['SUSPICIOUS', 'FLAGGED'])->update(['trust_status' => '2']);
        DB::table('candidate_submissions')->whereIn('trust_status', ['REJECTED', 'BLOCKED'])->update(['trust_status' => '3']);
        DB::table('candidate_submissions')->where('trust_status', 'PENDING_AUDIT')->update(['trust_status' => '4']);
        DB::table('candidate_submissions')->whereNotIn('trust_status', ['1', '2', '3', '4'])->update(['trust_status' => '4']);

        if (! $isSqlite) {
            DB::statement('ALTER TABLE `candidate_submissions` MODIFY COLUMN `trust_status` TINYINT UNSIGNED NOT NULL DEFAULT 4');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $binaryStatusTables = [
            'states',
            'exam_authorities',
            'exams',
            'exam_stages',
            'notices',
            'important_links',
            'categories',
            'shifts',
            'prediction_models',
        ];

        $isSqlite = DB::getDriverName() === 'sqlite';

        foreach ($binaryStatusTables as $table) {
            if (! $isSqlite) {
                DB::statement("ALTER TABLE `{$table}` MODIFY COLUMN `status` VARCHAR(20) NOT NULL DEFAULT 'ACTIVE'");
            }

            DB::table($table)->where('status', '1')->update(['status' => 'ACTIVE']);
            DB::table($table)->where('status', '0')->update(['status' => 'INACTIVE']);
        }

        if (! $isSqlite) {
            DB::statement("ALTER TABLE `exam_cycles` MODIFY COLUMN `status` VARCHAR(20) NOT NULL DEFAULT 'DRAFT'");
        }
        DB::table('exam_cycles')->where('status', '0')->update(['status' => 'DRAFT']);
        DB::table('exam_cycles')->where('status', '1')->update(['status' => 'ACTIVE']);
        DB::table('exam_cycles')->where('status', '2')->update(['status' => 'COMPLETED']);
        DB::table('exam_cycles')->where('status', '3')->update(['status' => 'CANCELLED']);

        if (! $isSqlite) {
            DB::statement("ALTER TABLE `candidate_submissions` MODIFY COLUMN `trust_status` VARCHAR(20) NOT NULL DEFAULT 'PENDING_AUDIT'");
        }
        DB::table('candidate_submissions')->where('trust_status', '1')->update(['trust_status' => 'TRUSTED']);
        DB::table('candidate_submissions')->where('trust_status', '2')->update(['trust_status' => 'SUSPICIOUS']);
        DB::table('candidate_submissions')->where('trust_status', '3')->update(['trust_status' => 'REJECTED']);
        DB::table('candidate_submissions')->where('trust_status', '4')->update(['trust_status' => 'PENDING_AUDIT']);
    }
};
