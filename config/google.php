<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google API Configuration
    |--------------------------------------------------------------------------
    |
    | Configurações para integração com Google Calendar e Meet
    |
    */

    'client_id' => env('GOOGLE_CLIENT_ID', ''),
    'client_secret' => env('GOOGLE_CLIENT_SECRET', ''),
    'redirect_uri' => env('GOOGLE_REDIRECT_URI', 'http://127.0.0.1:8000/google/callback'),
    'scopes' => [
        'https://www.googleapis.com/auth/calendar',
        'https://www.googleapis.com/auth/calendar.events',
    ],

    /*
    |--------------------------------------------------------------------------
    | Google Calendar Settings
    |--------------------------------------------------------------------------
    */
    
    'calendar' => [
        'primary_calendar_id' => env('GOOGLE_CALENDAR_ID', 'primary'),
        'timezone' => env('GOOGLE_CALENDAR_TIMEZONE', 'America/Sao_Paulo'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Google Meet Settings
    |--------------------------------------------------------------------------
    */
    
    'meet' => [
        'auto_generate' => env('GOOGLE_MEET_AUTO_GENERATE', true),
        'default_duration' => env('GOOGLE_MEET_DEFAULT_DURATION', 60), // minutos
    ],

    /*
    |--------------------------------------------------------------------------
    | Service Account (Opcional - para acesso sem autenticação do usuário)
    |--------------------------------------------------------------------------
    */
    
    'service_account' => [
        'enabled' => env('GOOGLE_SERVICE_ACCOUNT_ENABLED', false),
        'credentials_path' => env('GOOGLE_SERVICE_ACCOUNT_PATH', base_path('storage/app/google/service-account.json')),
        'subject_email' => env('GOOGLE_SERVICE_ACCOUNT_SUBJECT'), // Email do usuário para impersonar
    ],
];
