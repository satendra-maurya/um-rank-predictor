<?php

namespace App\Models;

use App\Enums\ConsentStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsentRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'consent_purpose_id',
        'user_id',
        'session_token',
        'consent_status',
        'consented_at',
        'withdrawn_at',
        'consent_version',
        'notice_version',
        'privacy_policy_version',
        'source',
        'ip_hash',
        'user_agent_hash',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'consent_status' => ConsentStatus::class,
            'consented_at' => 'datetime',
            'withdrawn_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    public function consentPurpose(): BelongsTo
    {
        return $this->belongsTo(ConsentPurpose::class, 'consent_purpose_id');
    }

    public function purpose(): BelongsTo
    {
        return $this->consentPurpose();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
