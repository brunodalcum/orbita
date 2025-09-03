<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google Calendar API Configuration
    |--------------------------------------------------------------------------
    |
    | Configurações para integração com Google Calendar API
    |
    */

    'api_key' => env('GOOGLE_CALENDAR_API_KEY'),
    'client_id' => env('GOOGLE_CALENDAR_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CALENDAR_CLIENT_SECRET'),
    'redirect_uri' => env('GOOGLE_CALENDAR_REDIRECT_URI', 'http://127.0.0.1:8000/auth/google/callback'),
    'scopes' => env('GOOGLE_CALENDAR_SCOPES', 'https://www.googleapis.com/auth/calendar'),

    /*
    |--------------------------------------------------------------------------
    | Google Meet Configuration
    |--------------------------------------------------------------------------
    |
    | Configurações para Google Meet
    |
    */

    'meet_enabled' => env('GOOGLE_MEET_ENABLED', true),
    'default_timezone' => env('GOOGLE_MEET_DEFAULT_TIMEZONE', 'America/Sao_Paulo'),

    /*
    |--------------------------------------------------------------------------
    | Calendar Settings
    |--------------------------------------------------------------------------
    |
    | Configurações do calendário
    |
    */

    'calendar_id' => env('GOOGLE_CALENDAR_ID', 'primary'),
    'send_updates' => env('GOOGLE_CALENDAR_SEND_UPDATES', 'all'),

    /*
    |--------------------------------------------------------------------------
    | Service Account Configuration
    |--------------------------------------------------------------------------
    |
    | Configurações para Service Account (recomendado para produção)
    |
    */

    'service_account_enabled' => env('GOOGLE_SERVICE_ACCOUNT_ENABLED', false),
    'service_account_file' => env('GOOGLE_SERVICE_ACCOUNT_FILE', storage_path('app/google-credentials.json')),
];

