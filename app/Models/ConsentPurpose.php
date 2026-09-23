<?php

namespace App\Models;

use App\Enums\ActiveStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConsentPurpose extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'description',
        'version',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => ActiveStatus::class,
        ];
    }

    public function consentRecords(): HasMany
    {
        return $this->hasMany(ConsentRecord::class);
    }
}
