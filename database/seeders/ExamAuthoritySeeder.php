<?php

namespace Database\Seeders;

use App\Enums\ExamAuthorityLevel;
use App\Models\ExamAuthority;
use App\Models\State;
use Illuminate\Database\Seeder;

class ExamAuthoritySeeder extends Seeder
{
    /**
     * Seed initial central and state exam authorities.
     * Idempotent based on authority `slug`.
     */
    public function run(): void
    {
        $upState = State::where('code', 'UP')->first();

        $authorities = [
            [
                'name' => 'Staff Selection Commission',
                'short_name' => 'SSC',
                'slug' => 'ssc',
                'level' => ExamAuthorityLevel::CENTRAL,
                'state_id' => null,
                'website_url' => 'https://ssc.gov.in',
                'logo' => null,
                'description' => 'Recruitment authority for Group B and C non-technical posts in Central Ministries and Departments.',
                'status' => 'ACTIVE',
            ],
            [
                'name' => 'Railway Recruitment Boards',
                'short_name' => 'RRB',
                'slug' => 'rrb',
                'level' => ExamAuthorityLevel::CENTRAL,
                'state_id' => null,
                'website_url' => 'https://indianrailways.gov.in',
                'logo' => null,
                'description' => 'Recruitment board for technical and non-technical staff in Indian Railways.',
                'status' => 'ACTIVE',
            ],
            [
                'name' => 'Uttar Pradesh Subordinate Services Selection Commission',
                'short_name' => 'UPSSSC',
                'slug' => 'upsssc',
                'level' => ExamAuthorityLevel::STATE,
                'state_id' => $upState?->id,
                'website_url' => 'https://upsssc.gov.in',
                'logo' => null,
                'description' => 'State organization authorized to conduct examinations for appointments to Group C posts in Uttar Pradesh.',
                'status' => 'ACTIVE',
            ],
        ];

        foreach ($authorities as $authData) {
            ExamAuthority::updateOrCreate(
                ['slug' => $authData['slug']],
                $authData
            );
        }
    }
}
