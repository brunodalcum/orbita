<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogoutListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Logout $event): void
    {
        // Garantir que o usuário seja redirecionado para a página de login
        if (request()->expectsJson()) {
            return;
        }

        // Se não for uma requisição AJAX, redirecionar para login
        if (!request()->ajax()) {
            redirect()->route('login')->send();
        }
    }
}
