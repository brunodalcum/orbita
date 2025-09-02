<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class GoogleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // TEMPORARIAMENTE COMENTADO - Google API Client removido durante instalação do Dompdf
        // Para reativar, reinstale: composer require google/apiclient google/auth google/apiclient-services
        
        /*
        // Configurar SSL para cURL (Windows/XAMPP)
        require_once base_path('config/curl-ssl-fix.php');
        
        // Carregar classes necessárias do Google (ordem correta)
        require_once base_path('vendor/google/apiclient/src/Exception.php');
        require_once base_path('vendor/google/apiclient/src/Model.php');
        require_once base_path('vendor/google/apiclient/src/Extension/Collection.php');
        require_once base_path('vendor/google/apiclient/src/Service.php');
        require_once base_path('vendor/google/apiclient/src/Service/Resource.php');
        require_once base_path('vendor/google/apiclient/src/Service/Exception.php');
        require_once base_path('vendor/google/apiclient/src/Utils/UriTemplate.php');
        require_once base_path('vendor/google/apiclient/src/Http/REST.php');
        require_once base_path('vendor/google/apiclient/src/Http/Batch.php');
        require_once base_path('vendor/google/apiclient/src/Task/Runner.php');
        require_once base_path('vendor/google/apiclient/src/Task/Retryable.php');
        require_once base_path('vendor/google/apiclient/src/Task/Exception.php');
        require_once base_path('vendor/google/apiclient/src/Client.php');
        
        // Carregar classes do Google Auth
        require_once base_path('vendor/google/auth/src/GetUniverseDomainInterface.php');
        require_once base_path('vendor/google/auth/src/ApplicationDefaultCredentials.php');
        
        // Carregar classes de cache do Google Auth
        require_once base_path('vendor/google/auth/src/Cache/MemoryCacheItemPool.php');
        
        // Carregar classes do Google HttpHandler
        require_once base_path('vendor/google/auth/src/Logging/LoggingTrait.php');
        require_once base_path('vendor/google/auth/src/HttpHandler/HttpHandlerFactory.php');
        require_once base_path('vendor/google/auth/src/HttpHandler/Guzzle6HttpHandler.php');
        require_once base_path('vendor/google/auth/src/HttpHandler/Guzzle7HttpHandler.php');
        
        // Carregar classes do Google Auth Middleware
        require_once base_path('vendor/google/auth/src/Middleware/SimpleMiddleware.php');
        
        // Carregar classes do Google AuthHandler
        require_once base_path('vendor/google/apiclient/src/AuthHandler/AuthHandlerFactory.php');
        require_once base_path('vendor/google/apiclient/src/AuthHandler/Guzzle6AuthHandler.php');
        require_once base_path('vendor/google/apiclient/src/AuthHandler/Guzzle7AuthHandler.php');
        
        // Carregar classes do Google Calendar
        require_once base_path('vendor/google/apiclient-services/src/Calendar.php');
        require_once base_path('vendor/google/apiclient/src/Calendar/Event.php');
        require_once base_path('vendor/google/apiclient/src/Calendar/EventDateTime.php');
        require_once base_path('vendor/google/apiclient/src/Calendar/EventAttendee.php');
        
        // Carregar classes de conferência do Google Calendar
        require_once base_path('vendor/google/apiclient-services/src/Calendar/ConferenceData.php');
        require_once base_path('vendor/google/apiclient/src/Calendar/ConferenceSolution.php');
        require_once base_path('vendor/google/apiclient/src/Calendar/ConferenceSolutionKey.php');
        require_once base_path('vendor/google/apiclient-services/src/Calendar/CreateConferenceRequest.php');
        
        // Carregar classes Resource do Google Calendar
        require_once base_path('vendor/google/apiclient-services/src/Calendar/Resource/Acl.php');
        require_once base_path('vendor/google/apiclient/src/Calendar/Resource/CalendarList.php');
        require_once base_path('vendor/google/apiclient/src/Calendar/Resource/Calendars.php');
        require_once base_path('vendor/google/apiclient/src/Calendar/Resource/Channels.php');
        require_once base_path('vendor/google/apiclient/src/Calendar/Resource/Colors.php');
        require_once base_path('vendor/google/apiclient/src/Calendar/Resource/Events.php');
        require_once base_path('vendor/google/apiclient/src/Calendar/Resource/Freebusy.php');
        require_once base_path('vendor/google/apiclient/src/Calendar/Resource/Settings.php');
        */
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
