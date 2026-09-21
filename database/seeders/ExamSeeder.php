<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\ExamAuthority;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    /**
     * Seed initial target exams for SSC, Railway, and State boards.
     * Idempotent based on exam `slug`.
     */
    public function run(): void
    {
        $ssc = ExamAuthority::where('slug', 'ssc')->first();
        $rrb = ExamAuthority::where('slug', 'rrb')->first();
        $upsssc = ExamAuthority::where('slug', 'upsssc')->first();

        $exams = [
            // SSC Exams
            [
                'authority_slug' => 'ssc',
                'name' => 'SSC Combined Graduate Level Examination',
                'short_name' => 'SSC CGL',
                'slug' => 'ssc-cgl',
                'description' => 'Premier exam conducted for Group B & C posts across central government departments.',
                'exam_type' => 'GRADUATION_LEVEL',
                'status' => 'ACTIVE',
            ],
            [
                'authority_slug' => 'ssc',
                'name' => 'SSC Multi Tasking Staff Examination',
                'short_name' => 'SSC MTS',
                'slug' => 'ssc-mts',
                'description' => 'Recruitment exam for general Central Service Group C Non-Gazetted posts.',
                'exam_type' => 'MATRIC',
                'status' => 'ACTIVE',
            ],
            [
                'authority_slug' => 'ssc',
                'name' => 'SSC Combined Higher Secondary Level Examination',
                'short_name' => 'SSC CHSL',
                'slug' => 'ssc-chsl',
                'description' => 'Recruitment for Lower Division Clerk, Junior Secretariat Assistant, and Data Entry Operator positions.',
                'exam_type' => 'INTERMEDIATE',
                'status' => 'ACTIVE',
            ],
            [
                'authority_slug' => 'ssc',
                'name' => 'SSC Central Police Organization Examination',
                'short_name' => 'SSC CPO',
                'slug' => 'ssc-cpo',
                'description' => 'Recruitment exam for Sub-Inspector in Delhi Police and Central Armed Police Forces.',
                'exam_type' => 'GRADUATION_LEVEL',
                'status' => 'ACTIVE',
            ],

            // Railway Exams
            [
                'authority_slug' => 'rrb',
                'name' => 'RRB Non-Technical Popular Categories',
                'short_name' => 'RRB NTPC',
                'slug' => 'rrb-ntpc',
                'description' => 'Recruitment for Station Master, Goods Guard, Commercial Apprentice, and Clerk posts in Indian Railways.',
                'exam_type' => 'GRADUATION_AND_INTERMEDIATE',
                'status' => 'ACTIVE',
            ],
            [
                'authority_slug' => 'rrb',
                'name' => 'RRB Group D Recruitment',
                'short_name' => 'RRB Group D',
                'slug' => 'rrb-group-d',
                'description' => 'Recruitment for Track Maintainer Grade IV, Helper/Assistant positions in Indian Railways.',
                'exam_type' => 'MATRIC',
                'status' => 'ACTIVE',
            ],

            // State Exams (UPSSSC)
            [
                'authority_slug' => 'upsssc',
                'name' => 'UPSSSC Preliminary Eligibility Test',
                'short_name' => 'UPSSSC PET',
                'slug' => 'upsssc-pet',
                'description' => 'Mandatory preliminary screening eligibility test for Group C posts in Uttar Pradesh.',
                'exam_type' => 'ELIGIBILITY',
                'status' => 'ACTIVE',
            ],
        ];

        foreach ($exams as $examData) {
            $authority = ExamAuthority::where('slug', $examData['authority_slug'])->first();

            if (! $authority) {
                continue;
            }

            Exam::updateOrCreate(
                ['slug' => $examData['slug']],
                [
                    'exam_authority_id' => $authority->id,
                    'name' => $examData['name'],
                    'short_name' => $examData['short_name'],
                    'slug' => $examData['slug'],
                    'description' => $examData['description'],
                    'exam_type' => $examData['exam_type'],
                    'status' => $examData['status'],
                ]
            );
        }
    }
}
