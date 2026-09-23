<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database in correct foreign key dependency order.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // 1. States (Root hierarchy)
            $this->call(StateSeeder::class);

            // 2. Exam Authorities (Depends on States for state-level authorities)
            $this->call(ExamAuthoritySeeder::class);

            // 3. Reservation Categories (Independent master)
            $this->call(CategorySeeder::class);

            // 4. Exams (Depends on Exam Authorities)
            $this->call(ExamSeeder::class);

            // 5. Exam Cycles (Depends on Exams)
            $this->call(ExamCycleSeeder::class);

            // 6. Exam Stages (Depends on Exam Cycles)
            $this->call(ExamStageSeeder::class);

            // 7. Shifts (Depends on Exam Stages)
            $this->call(ShiftSeeder::class);

            // 8. Consent Purposes
            $this->call(ConsentPurposeSeeder::class);

            // 9. Admin User
            $this->call(AdminUserSeeder::class);
        });
    }
}
