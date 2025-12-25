<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Keys Storage Configuration
    |--------------------------------------------------------------------------
    |
    | Determines where API keys should be stored securely.
    | 
    | Options:
    |   - true: Store in database (recommended for shared VPS)
    |   - false: Store in encrypted files
    |
    */
    'use_db_for_keys' => env('USE_DB_FOR_KEYS', true),
];