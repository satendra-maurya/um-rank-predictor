<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Seed initial target states.
     * Idempotent based on state `code`.
     */
    public function run(): void
    {
        $states = [
            [
                'name' => 'Uttar Pradesh',
                'short_name' => 'UP',
                'code' => 'UP',
                'status' => 'ACTIVE',
            ],
            [
                'name' => 'Bihar',
                'short_name' => 'BR',
                'code' => 'BR',
                'status' => 'ACTIVE',
            ],
            [
                'name' => 'Madhya Pradesh',
                'short_name' => 'MP',
                'code' => 'MP',
                'status' => 'ACTIVE',
            ],
            [
                'name' => 'Rajasthan',
                'short_name' => 'RJ',
                'code' => 'RJ',
                'status' => 'ACTIVE',
            ],
            [
                'name' => 'Delhi',
                'short_name' => 'DL',
                'code' => 'DL',
                'status' => 'ACTIVE',
            ],
        ];

        foreach ($states as $stateData) {
            State::updateOrCreate(
                ['code' => $stateData['code']],
                $stateData
            );
        }
    }
}
