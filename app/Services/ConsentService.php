<?php

namespace App\Services;

use App\Enums\ActiveStatus;
use App\Enums\ConsentStatus;
use App\Models\ConsentPurpose;
use App\Models\ConsentRecord;

class ConsentService
{
    /**
     * Resolve or auto-create consent purpose by key.
     */
    public function resolvePurpose(string $purposeKey): ConsentPurpose
    {
        $config = config("consent.purposes.{$purposeKey}", []);

        return ConsentPurpose::firstOrCreate(
            ['key' => $purposeKey],
            [
                'name' => $config['name'] ?? ucwords(str_replace('_', ' ', $purposeKey)),
                'description' => $config['description'] ?? null,
                'version' => $config['version'] ?? config('consent.defaults.consent_version', '1.0'),
                'status' => ActiveStatus::ACTIVE,
            ]
        );
    }

    /**
     * Record a granted consent event for a purpose.
     *
     * @param  array{
     *     user_id?: ?int,
     *     session_token?: ?string,
     *     consented_at?: ?\DateTimeInterface,
     *     consent_version?: ?string,
     *     notice_version?: ?string,
     *     privacy_policy_version?: ?string,
     *     source?: ?string,
     *     ip_address?: ?string,
     *     user_agent?: ?string,
     *     ip_hash?: ?string,
     *     user_agent_hash?: ?string,
     *     metadata?: ?array
     * }  $options
     */
    public function give(string $purposeKey, array $options = []): ConsentRecord
    {
        $purpose = $this->resolvePurpose($purposeKey);

        $ip = $options['ip_address'] ?? request()?->ip();
        $ua = $options['user_agent'] ?? request()?->userAgent();

        $ipHash = ! empty($options['ip_hash']) ? $options['ip_hash'] : ($ip ? hash('sha256', $ip) : null);
        $uaHash = ! empty($options['user_agent_hash']) ? $options['user_agent_hash'] : ($ua ? hash('sha256', $ua) : null);

        $defaults = config('consent.defaults', []);

        return ConsentRecord::create([
            'consent_purpose_id' => $purpose->id,
            'user_id' => $options['user_id'] ?? auth()->id(),
            'session_token' => $options['session_token'] ?? session()?->getId(),
            'consent_status' => ConsentStatus::GRANTED,
            'consented_at' => $options['consented_at'] ?? now(),
            'withdrawn_at' => null,
            'consent_version' => (string) ($options['consent_version'] ?? $defaults['consent_version'] ?? $purpose->version ?? '1.0'),
            'notice_version' => (string) ($options['notice_version'] ?? $defaults['notice_version'] ?? '1.0'),
            'privacy_policy_version' => (string) ($options['privacy_policy_version'] ?? $defaults['privacy_policy_version'] ?? '1.0'),
            'source' => $options['source'] ?? 'rank_predictor_form',
            'ip_hash' => $ipHash,
            'user_agent_hash' => $uaHash,
            'metadata' => $options['metadata'] ?? null,
        ]);
    }

    public function giveConsent(string $purposeKey, array $options = []): ConsentRecord
    {
        return $this->give($purposeKey, $options);
    }

    /**
     * Check if active granted consent exists for a purpose and user/session.
     */
    public function hasConsent(string $purposeKey, int|array|null $userIdOrOptions = null, ?string $sessionToken = null): bool
    {
        $consent = $this->getConsent($purposeKey, $userIdOrOptions, $sessionToken);

        return $consent && $consent->consent_status === ConsentStatus::GRANTED;
    }

    /**
     * Get the latest consent record for a purpose and user/session.
     */
    public function getConsent(string $purposeKey, int|array|null $userIdOrOptions = null, ?string $sessionToken = null): ?ConsentRecord
    {
        if (is_array($userIdOrOptions)) {
            $userId = $userIdOrOptions['user_id'] ?? auth()->id();
            $sessionToken = $userIdOrOptions['session_token'] ?? $sessionToken ?? session()?->getId();
        } else {
            $userId = $userIdOrOptions ?? auth()->id();
            $sessionToken = $sessionToken ?? session()?->getId();
        }

        $purpose = ConsentPurpose::where('key', $purposeKey)->first();
        if (! $purpose) {
            return null;
        }

        return ConsentRecord::where('consent_purpose_id', $purpose->id)
            ->where(function ($query) use ($userId, $sessionToken) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } elseif ($sessionToken) {
                    $query->where('session_token', $sessionToken);
                } else {
                    $query->whereRaw('1 = 0');
                }
            })
            ->latest('id')
            ->first();
    }

    /**
     * Record a consent withdrawal event without overwriting historical audit data.
     */
    public function withdraw(string $purposeKey, int|array|null $userIdOrOptions = null, ?string $sessionToken = null, array $options = []): ?ConsentRecord
    {
        if (is_array($userIdOrOptions)) {
            $options = array_merge($userIdOrOptions, $options);
            $userId = $options['user_id'] ?? auth()->id();
            $sessionToken = $options['session_token'] ?? session()?->getId();
        } else {
            $userId = $userIdOrOptions ?? auth()->id();
            $sessionToken = $sessionToken ?? session()?->getId();
        }

        $latest = $this->getConsent($purposeKey, $userId, $sessionToken);
        if (! $latest || $latest->consent_status === ConsentStatus::WITHDRAWN) {
            return $latest;
        }

        $ip = $options['ip_address'] ?? request()?->ip();
        $ua = $options['user_agent'] ?? request()?->userAgent();

        $ipHash = ! empty($options['ip_hash']) ? $options['ip_hash'] : ($ip ? hash('sha256', $ip) : null);
        $uaHash = ! empty($options['user_agent_hash']) ? $options['user_agent_hash'] : ($ua ? hash('sha256', $ua) : null);

        return ConsentRecord::create([
            'consent_purpose_id' => $latest->consent_purpose_id,
            'user_id' => $userId,
            'session_token' => $sessionToken,
            'consent_status' => ConsentStatus::WITHDRAWN,
            'consented_at' => $latest->consented_at,
            'withdrawn_at' => $options['withdrawn_at'] ?? now(),
            'consent_version' => $latest->consent_version,
            'notice_version' => $latest->notice_version,
            'privacy_policy_version' => $latest->privacy_policy_version,
            'source' => $options['source'] ?? 'user_withdrawal',
            'ip_hash' => $ipHash,
            'user_agent_hash' => $uaHash,
            'metadata' => $options['metadata'] ?? null,
        ]);
    }

    public function withdrawConsent(string $purposeKey, int|array|null $userIdOrOptions = null, ?string $sessionToken = null, array $options = []): ?ConsentRecord
    {
        return $this->withdraw($purposeKey, $userIdOrOptions, $sessionToken, $options);
    }
}
