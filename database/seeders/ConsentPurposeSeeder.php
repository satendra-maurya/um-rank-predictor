<?php

namespace Database\Seeders;

use App\Enums\ActiveStatus;
use App\Models\ConsentPurpose;
use Illuminate\Database\Seeder;

class ConsentPurposeSeeder extends Seeder
{
    /**
     * Seed initial consent purposes.
     */
    public function run(): void
    {
        $purposes = config('consent.purposes', []);

        foreach ($purposes as $key => $data) {
            ConsentPurpose::firstOrCreate(
                ['key' => $key],
                [
                    'name' => $data['name'] ?? ucwords(str_replace('_', ' ', $key)),
                    'description' => $data['description'] ?? null,
                    'version' => $data['version'] ?? '1.0',
                    'status' => ActiveStatus::ACTIVE,
                ]
            );
        }
    }
}
