<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class LicenciadoSidebar extends Component
{
    public $user;
    public $currentRoute;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->user = Auth::user();
        $this->currentRoute = request()->route() ? request()->route()->getName() : '';
    }

    /**
     * Verificar se a rota está ativa
     */
    public function isActive($routeName)
    {
        return $this->currentRoute === $routeName;
    }

    /**
     * Verificar se o grupo de rotas está ativo
     */
    public function isGroupActive($groupName)
    {
        return str_starts_with($this->currentRoute, $groupName);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.licenciado-sidebar');
    }
}
