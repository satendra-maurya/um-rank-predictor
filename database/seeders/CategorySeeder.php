<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Seed initial candidate reservation categories.
     * Idempotent based on category `code`.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'General / Unreserved',
                'code' => 'UR',
                'description' => 'Unreserved / General Category Candidates',
                'sort_order' => 1,
                'status' => 'ACTIVE',
            ],
            [
                'name' => 'Other Backward Class',
                'code' => 'OBC',
                'description' => 'Other Backward Classes (Non-Creamy Layer)',
                'sort_order' => 2,
                'status' => 'ACTIVE',
            ],
            [
                'name' => 'Economically Weaker Section',
                'code' => 'EWS',
                'description' => 'Economically Weaker Sections',
                'sort_order' => 3,
                'status' => 'ACTIVE',
            ],
            [
                'name' => 'Scheduled Caste',
                'code' => 'SC',
                'description' => 'Scheduled Castes',
                'sort_order' => 4,
                'status' => 'ACTIVE',
            ],
            [
                'name' => 'Scheduled Tribe',
                'code' => 'ST',
                'description' => 'Scheduled Tribes',
                'sort_order' => 5,
                'status' => 'ACTIVE',
            ],
        ];

        foreach ($categories as $catData) {
            Category::updateOrCreate(
                ['code' => $catData['code']],
                $catData
            );
        }
    }
}
