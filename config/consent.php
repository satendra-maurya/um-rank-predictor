<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Consent & Privacy Policy Versions
    |--------------------------------------------------------------------------
    */
    'defaults' => [
        'consent_version' => '1.0',
        'notice_version' => '1.0',
        'privacy_policy_version' => '1.0',
    ],

    /*
    |--------------------------------------------------------------------------
    | Pre-configured Consent Purposes
    |--------------------------------------------------------------------------
    */
    'purposes' => [
        'rank_prediction' => [
            'key' => 'rank_prediction',
            'name' => 'Rank Prediction Service',
            'description' => 'Processing candidate data for providing the rank prediction service.',
            'version' => '1.0',
            'status' => 1, // ActiveStatus::ACTIVE
        ],
    ],

];
