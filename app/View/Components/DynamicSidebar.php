<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class DynamicSidebar extends Component
{
    public $user;
    public $menuItems;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $this->user = Auth::user();
        $this->menuItems = $this->buildMenuItems();
        
        // Garantir que o usuário tenha um role (para usuários existentes sem role)
        if ($this->user && !$this->user->role_id) {
            $this->assignDefaultRole();
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dynamic-sidebar');
    }

    /**
     * Build menu items based on user permissions
     */
    private function buildMenuItems(): array
    {
        if (!$this->user) {
            return [];
        }

        $menuItems = [];

        // Dashboard - sempre visível para usuários autenticados
        if ($this->user->hasPermission('dashboard.view') || $this->user->isSuperAdmin()) {
            $menuItems[] = [
                'name' => 'Dashboard',
                'icon' => 'fas fa-tachometer-alt',
                'route' => 'dashboard',
                'permissions' => ['dashboard.view'],
                'is_active' => request()->routeIs('dashboard'),
                'submenu' => []
            ];
        }

        // Licenciados
        if ($this->user->hasModulePermission('licenciados') || $this->user->isSuperAdmin()) {
            $submenu = [];
            
            if ($this->user->hasPermission('licenciados.view')) {
                $submenu[] = [
                    'name' => 'Listar Licenciados',
                    'route' => 'dashboard.licenciados',
                    'permission' => 'licenciados.view'
                ];
            }
            
            if ($this->user->hasPermission('licenciados.create')) {
                $submenu[] = [
                    'name' => 'Novo Licenciado',
                    'route' => 'dashboard.licenciados',
                    'permission' => 'licenciados.create',
                    'action' => 'create'
                ];
            }
            
            if ($this->user->hasPermission('licenciados.approve')) {
                $submenu[] = [
                    'name' => 'Aprovações',
                    'route' => 'dashboard.licenciados',
                    'permission' => 'licenciados.approve',
                    'action' => 'approvals'
                ];
            }

            $menuItems[] = [
                'name' => 'Licenciados',
                'icon' => 'fas fa-id-card',
                'route' => 'dashboard.licenciados',
                'permissions' => ['licenciados.view', 'licenciados.create', 'licenciados.manage'],
                'is_active' => request()->routeIs('dashboard.licenciados*'),
                'submenu' => $submenu
            ];
        }

        // Contratos
        if ($this->user->hasModulePermission('contratos') || $this->user->isSuperAdmin()) {
            $submenu = [];
            
            if ($this->user->hasPermission('contratos.view')) {
                $submenu[] = [
                    'name' => 'Listar Contratos',
                    'route' => 'contracts.index',
                    'permission' => 'contratos.view'
                ];
            }
            
            if ($this->user->hasPermission('contratos.create')) {
                $submenu[] = [
                    'name' => 'Novo Contrato',
                    'route' => 'contracts.create',
                    'permission' => 'contratos.create'
                ];
                
                $submenu[] = [
                    'name' => 'Gerar Contrato',
                    'route' => 'contracts.generate.index',
                    'permission' => 'contratos.create'
                ];
            }

            // Adicionar Templates de Contrato no submenu de Contratos
            if ($this->user->hasPermission('contratos.manage')) {
                $submenu[] = [
                    'name' => 'Modelos de Contrato',
                    'route' => 'contract-templates.index',
                    'permission' => 'contratos.manage'
                ];
            }

            $menuItems[] = [
                'name' => 'Contratos',
                'icon' => 'fas fa-file-contract',
                'route' => 'contracts.index',
                'permissions' => ['contratos.view', 'contratos.create', 'contratos.manage'],
                'is_active' => request()->routeIs('contracts.*') || request()->routeIs('contract-templates.*'),
                'submenu' => $submenu
            ];
        }

        // Leads
        if ($this->user->hasModulePermission('leads')) {
            $submenu = [];
            
            if ($this->user->hasPermission('leads.view')) {
                $submenu[] = [
                    'name' => 'Listar Leads',
                    'route' => 'dashboard.leads',
                    'permission' => 'leads.view'
                ];
            }
            
            if ($this->user->hasPermission('leads.create')) {
                $submenu[] = [
                    'name' => 'Extrair Leads',
                    'route' => 'dashboard.places.extract',
                    'permission' => 'leads.create',
                    'action' => 'extract'
                ];
            }

            $menuItems[] = [
                'name' => 'Leads',
                'icon' => 'fas fa-user-plus',
                'route' => 'dashboard.leads',
                'permissions' => ['leads.view', 'leads.create', 'leads.manage'],
                'is_active' => request()->routeIs('dashboard.leads*'),
                'submenu' => $submenu
            ];
        }

        // Estabelecimentos (ECs) - Temporariamente desabilitado até implementação
        // if ($this->user->hasModulePermission('estabelecimentos')) {
        //     $submenu = [];
        //     
        //     if ($this->user->hasPermission('estabelecimentos.view')) {
        //         $submenu[] = [
        //             'name' => 'Listar ECs',
        //             'route' => 'dashboard.estabelecimentos',
        //             'permission' => 'estabelecimentos.view'
        //         ];
        //     }
        //     
        //     if ($this->user->hasPermission('estabelecimentos.create')) {
        //         $submenu[] = [
        //             'name' => 'Novo EC',
        //             'route' => 'dashboard.estabelecimentos',
        //             'permission' => 'estabelecimentos.create',
        //             'action' => 'create'
        //         ];
        //     }

        //     $menuItems[] = [
        //         'name' => 'Estabelecimentos',
        //         'icon' => 'fas fa-store',
        //         'route' => 'dashboard.estabelecimentos',
        //         'permissions' => ['estabelecimentos.view', 'estabelecimentos.create', 'estabelecimentos.manage'],
        //         'is_active' => request()->routeIs('dashboard.estabelecimentos*'),
        //         'submenu' => $submenu
        //     ];
        // }

        // Operações
        if ($this->user->hasModulePermission('operacoes')) {
            $submenu = [];
            
            if ($this->user->hasPermission('operacoes.view')) {
                $submenu[] = [
                    'name' => 'Listar Operações',
                    'route' => 'dashboard.operacoes',
                    'permission' => 'operacoes.view'
                ];
            }
            
            if ($this->user->hasPermission('operacoes.create')) {
                $submenu[] = [
                    'name' => 'Nova Operação',
                    'route' => 'dashboard.operacoes',
                    'permission' => 'operacoes.create',
                    'action' => 'create'
                ];
            }

            $menuItems[] = [
                'name' => 'Operações',
                'icon' => 'fas fa-cogs',
                'route' => 'dashboard.operacoes',
                'permissions' => ['operacoes.view', 'operacoes.create', 'operacoes.manage'],
                'is_active' => request()->routeIs('dashboard.operacoes*'),
                'submenu' => $submenu
            ];
        }

        // Planos
        if ($this->user->hasModulePermission('planos')) {
            $submenu = [];
            
            if ($this->user->hasPermission('planos.view')) {
                $submenu[] = [
                    'name' => 'Listar Planos',
                    'route' => 'dashboard.planos',
                    'permission' => 'planos.view'
                ];
                
                // Submenu Simulador de Taxas movido do Marketing
                $submenu[] = [
                    'name' => 'Simulador de Taxas',
                    'route' => 'tax-simulator.index',
                    'permission' => 'planos.view'
                ];
            }
            
            if ($this->user->hasPermission('planos.create')) {
                $submenu[] = [
                    'name' => 'Novo Plano',
                    'route' => 'dashboard.planos',
                    'permission' => 'planos.create',
                    'action' => 'create'
                ];
            }

            $menuItems[] = [
                'name' => 'Planos',
                'icon' => 'fas fa-chart-line',
                'route' => 'dashboard.planos',
                'permissions' => ['planos.view', 'planos.create', 'planos.manage'],
                'is_active' => request()->routeIs('dashboard.planos*') || request()->routeIs('tax-simulator.*'),
                'submenu' => $submenu
            ];
        }

        // Adquirentes
        if ($this->user->hasModulePermission('adquirentes')) {
            $submenu = [];
            
            if ($this->user->hasPermission('adquirentes.view')) {
                $submenu[] = [
                    'name' => 'Listar Adquirentes',
                    'route' => 'dashboard.adquirentes',
                    'permission' => 'adquirentes.view'
                ];
            }
            
            if ($this->user->hasPermission('adquirentes.create')) {
                $submenu[] = [
                    'name' => 'Novo Adquirente',
                    'route' => 'dashboard.adquirentes',
                    'permission' => 'adquirentes.create',
                    'action' => 'create'
                ];
            }

            $menuItems[] = [
                'name' => 'Adquirentes',
                'icon' => 'fas fa-building',
                'route' => 'dashboard.adquirentes',
                'permissions' => ['adquirentes.view', 'adquirentes.create', 'adquirentes.manage'],
                'is_active' => request()->routeIs('dashboard.adquirentes*'),
                'submenu' => $submenu
            ];
        }

        // Agenda
        if ($this->user->hasModulePermission('agenda')) {
            $submenu = [];
            
            if ($this->user->hasPermission('agenda.view')) {
                $submenu[] = [
                    'name' => 'Ver Agenda',
                    'route' => 'dashboard.agenda',
                    'permission' => 'agenda.view'
                ];
            }
            
            if ($this->user->hasPermission('agenda.create')) {
                $submenu[] = [
                    'name' => 'Novo Compromisso',
                    'route' => 'dashboard.agenda',
                    'permission' => 'agenda.create',
                    'action' => 'create'
                ];
            }

            $menuItems[] = [
                'name' => 'Agenda',
                'icon' => 'fas fa-calendar-alt',
                'route' => 'dashboard.agenda',
                'permissions' => ['agenda.view', 'agenda.create', 'agenda.manage'],
                'is_active' => request()->routeIs('dashboard.agenda*'),
                'submenu' => $submenu
            ];
        }

        // Marketing
        if ($this->user->hasModulePermission('marketing')) {
            $submenu = [];
            
            if ($this->user->hasPermission('marketing.view')) {
                $submenu[] = [
                    'name' => 'Dashboard Marketing',
                    'route' => 'dashboard.marketing',
                    'permission' => 'marketing.view'
                ];
                
                $submenu[] = [
                    'name' => 'Campanhas',
                    'route' => 'dashboard.marketing.campanhas',
                    'permission' => 'marketing.view'
                ];
                
                $submenu[] = [
                    'name' => 'Modelos de Email',
                    'route' => 'dashboard.marketing.modelos',
                    'permission' => 'marketing.view'
                ];
                
                // Submenu Lembretes conforme solicitado
                $submenu[] = [
                    'name' => 'Lembretes',
                    'route' => 'reminders.index',
                    'permission' => 'marketing.view'
                ];
            }
            
            if ($this->user->hasPermission('marketing.create')) {
                $submenu[] = [
                    'name' => 'Nova Campanha',
                    'route' => 'dashboard.marketing.campanhas',
                    'permission' => 'marketing.create',
                    'action' => 'create'
                ];
            }

            $menuItems[] = [
                'name' => 'Marketing',
                'icon' => 'fas fa-bullhorn',
                'route' => 'dashboard.marketing',
                'permissions' => ['marketing.view', 'marketing.create', 'marketing.manage'],
                'is_active' => request()->routeIs('dashboard.marketing*') || request()->routeIs('reminders.*'),
                'submenu' => $submenu
            ];
        }

        // Relatórios - Temporariamente desabilitado até implementação
        // if ($this->user->hasModulePermission('relatorios')) {
        //     $submenu = [];
        //     
        //     if ($this->user->hasPermission('relatorios.view')) {
        //         $submenu[] = [
        //             'name' => 'Relatórios Financeiros',
        //             'route' => 'dashboard.relatorios.financeiros',
        //             'permission' => 'relatorios.view'
        //         ];
        //         
        //         $submenu[] = [
        //             'name' => 'Relatórios de Performance',
        //             'route' => 'dashboard.relatorios.performance',
        //             'permission' => 'relatorios.view'
        //         ];
        //         
        //         $submenu[] = [
        //             'name' => 'Relatórios de Comissões',
        //             'route' => 'dashboard.relatorios.comissoes',
        //             'permission' => 'relatorios.view'
        //         ];
        //     }
        //     
        //     if ($this->user->hasPermission('relatorios.export')) {
        //         $submenu[] = [
        //             'name' => 'Exportar Dados',
        //             'route' => 'dashboard.relatorios.export',
        //             'permission' => 'relatorios.export'
        //         ];
        //     }

        //     $menuItems[] = [
        //         'name' => 'Relatórios',
        //         'icon' => 'fas fa-chart-bar',
        //         'route' => 'dashboard.relatorios',
        //         'permissions' => ['relatorios.view', 'relatorios.export'],
        //         'is_active' => request()->routeIs('dashboard.relatorios*'),
        //         'submenu' => $submenu
        //     ];
        // }

        // Usuários & Permissões (apenas para Admin e Super Admin)
        if (($this->user->hasModulePermission('usuarios') || $this->user->isSuperAdmin()) && ($this->user->isAdmin() || $this->user->isSuperAdmin())) {
            $submenu = [];
            
            if ($this->user->hasPermission('usuarios.view')) {
                $submenu[] = [
                    'name' => 'Listar Usuários',
                    'route' => 'dashboard.users',
                    'permission' => 'usuarios.view'
                ];
            }
            
            if ($this->user->hasPermission('usuarios.create')) {
                $submenu[] = [
                    'name' => 'Novo Usuário',
                    'route' => 'dashboard.users',
                    'permission' => 'usuarios.create',
                    'action' => 'create'
                ];
            }
            
            // Submenu Perfil de Usuário (Permissões)
            if ($this->user->hasPermission('permissoes.view') || $this->user->hasPermission('permissoes.manage')) {
                $submenu[] = [
                    'name' => 'Perfil de Usuário',
                    'route' => 'permissions.index',
                    'permission' => 'permissoes.view'
                ];
            }

            $menuItems[] = [
                'name' => 'Usuários',
                'icon' => 'fas fa-users-cog',
                'route' => 'dashboard.users',
                'permissions' => ['usuarios.view', 'usuarios.create', 'usuarios.manage', 'permissoes.view'],
                'is_active' => request()->routeIs('dashboard.users*') || request()->routeIs('permissions.*'),
                'submenu' => $submenu
            ];
        }

        // Configurações do Sistema (apenas para Super Admin)
        if ($this->user->hasModulePermission('configuracoes') && $this->user->isSuperAdmin()) {
            $submenu = [];
            
            if ($this->user->hasPermission('configuracoes.view')) {
                $submenu[] = [
                    'name' => 'Configurações Gerais',
                    'route' => 'dashboard.configuracoes',
                    'permission' => 'configuracoes.view'
                ];
            }
            
            // if ($this->user->hasPermission('configuracoes.manage')) {
            //     $submenu[] = [
            //         'name' => 'Configurações Avançadas',
            //         'route' => 'dashboard.configuracoes.advanced',
            //         'permission' => 'configuracoes.manage'
            //     ];
            // }

            $menuItems[] = [
                'name' => 'Configurações',
                'icon' => 'fas fa-cog',
                'route' => 'dashboard.configuracoes',
                'permissions' => ['configuracoes.view', 'configuracoes.manage'],
                'is_active' => request()->routeIs('dashboard.configuracoes*'),
                'submenu' => $submenu
            ];
        }

        return $menuItems;
    }

    /**
     * Check if user has any of the required permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        if (!$this->user) {
            return false;
        }

        foreach ($permissions as $permission) {
            if ($this->user->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get user role display name
     */
    public function getUserRoleDisplayName(): string
    {
        if (!$this->user || !$this->user->role) {
            return 'Usuário';
        }

        return $this->user->role->display_name;
    }

    /**
     * Build menu items specifically for Super Admin (fallback)
     */
    private function buildSuperAdminMenus(): array
    {
        return [
            [
                'name' => 'Dashboard',
                'icon' => 'fas fa-tachometer-alt',
                'route' => 'dashboard',
                'permissions' => ['dashboard.view'],
                'is_active' => request()->routeIs('dashboard'),
                'submenu' => []
            ],
            [
                'name' => 'Licenciados',
                'icon' => 'fas fa-id-card',
                'route' => 'dashboard.licenciados',
                'permissions' => ['licenciados.view'],
                'is_active' => request()->routeIs('dashboard.licenciados*'),
                'submenu' => [
                    [
                        'name' => 'Listar Licenciados',
                        'route' => 'dashboard.licenciados',
                        'permission' => 'licenciados.view'
                    ],
                    [
                        'name' => 'Novo Licenciado',
                        'route' => 'dashboard.licenciados',
                        'permission' => 'licenciados.create',
                        'action' => 'create'
                    ],
                    [
                        'name' => 'Aprovações',
                        'route' => 'dashboard.licenciados',
                        'permission' => 'licenciados.approve',
                        'action' => 'approvals'
                    ]
                ]
            ],
            [
                'name' => 'Contratos',
                'icon' => 'fas fa-file-contract',
                'route' => 'contracts.index',
                'permissions' => ['contratos.view'],
                'is_active' => request()->routeIs('contracts.*') || request()->routeIs('contract-templates.*'),
                'submenu' => [
                    [
                        'name' => 'Listar Contratos',
                        'route' => 'contracts.index',
                        'permission' => 'contratos.view'
                    ],
                    [
                        'name' => 'Novo Contrato',
                        'route' => 'contracts.create',
                        'permission' => 'contratos.create'
                    ],
                    [
                        'name' => 'Gerar Contrato',
                        'route' => 'contracts.generate.index',
                        'permission' => 'contratos.create'
                    ],
                    [
                        'name' => 'Modelos de Contrato',
                        'route' => 'contract-templates.index',
                        'permission' => 'contratos.manage'
                    ]
                ]
            ],
            [
                'name' => 'Leads',
                'icon' => 'fas fa-user-plus',
                'route' => 'dashboard.leads',
                'permissions' => ['leads.view'],
                'is_active' => request()->routeIs('dashboard.leads*'),
                'submenu' => [
                    [
                        'name' => 'Listar Leads',
                        'route' => 'dashboard.leads',
                        'permission' => 'leads.view'
                    ],
                    [
                        'name' => 'Novo Lead',
                        'route' => 'dashboard.leads',
                        'permission' => 'leads.create',
                        'action' => 'create'
                    ]
                ]
            ],
            [
                'name' => 'Operações',
                'icon' => 'fas fa-cogs',
                'route' => 'dashboard.operacoes',
                'permissions' => ['operacoes.view'],
                'is_active' => request()->routeIs('dashboard.operacoes*'),
                'submenu' => [
                    [
                        'name' => 'Listar Operações',
                        'route' => 'dashboard.operacoes',
                        'permission' => 'operacoes.view'
                    ],
                    [
                        'name' => 'Nova Operação',
                        'route' => 'dashboard.operacoes',
                        'permission' => 'operacoes.create',
                        'action' => 'create'
                    ]
                ]
            ],
            [
                'name' => 'Planos',
                'icon' => 'fas fa-chart-line',
                'route' => 'dashboard.planos',
                'permissions' => ['planos.view'],
                'is_active' => request()->routeIs('dashboard.planos*') || request()->routeIs('tax-simulator.*'),
                'submenu' => [
                    [
                        'name' => 'Listar Planos',
                        'route' => 'dashboard.planos',
                        'permission' => 'planos.view'
                    ],
                    [
                        'name' => 'Simulador de Taxas',
                        'route' => 'tax-simulator.index',
                        'permission' => 'planos.view'
                    ],
                    [
                        'name' => 'Novo Plano',
                        'route' => 'dashboard.planos',
                        'permission' => 'planos.create',
                        'action' => 'create'
                    ]
                ]
            ],
            [
                'name' => 'Adquirentes',
                'icon' => 'fas fa-building',
                'route' => 'dashboard.adquirentes',
                'permissions' => ['adquirentes.view'],
                'is_active' => request()->routeIs('dashboard.adquirentes*'),
                'submenu' => [
                    [
                        'name' => 'Listar Adquirentes',
                        'route' => 'dashboard.adquirentes',
                        'permission' => 'adquirentes.view'
                    ],
                    [
                        'name' => 'Novo Adquirente',
                        'route' => 'dashboard.adquirentes',
                        'permission' => 'adquirentes.create',
                        'action' => 'create'
                    ]
                ]
            ],
            [
                'name' => 'Agenda',
                'icon' => 'fas fa-calendar-alt',
                'route' => 'dashboard.agenda',
                'permissions' => ['agenda.view'],
                'is_active' => request()->routeIs('dashboard.agenda*'),
                'submenu' => [
                    [
                        'name' => 'Ver Agenda',
                        'route' => 'dashboard.agenda',
                        'permission' => 'agenda.view'
                    ],
                    [
                        'name' => 'Novo Compromisso',
                        'route' => 'dashboard.agenda',
                        'permission' => 'agenda.create',
                        'action' => 'create'
                    ]
                ]
            ],
            [
                'name' => 'Marketing',
                'icon' => 'fas fa-bullhorn',
                'route' => 'dashboard.marketing',
                'permissions' => ['marketing.view'],
                'is_active' => request()->routeIs('dashboard.marketing*') || request()->routeIs('reminders.*'),
                'submenu' => [
                    [
                        'name' => 'Dashboard Marketing',
                        'route' => 'dashboard.marketing',
                        'permission' => 'marketing.view'
                    ],
                    [
                        'name' => 'Campanhas',
                        'route' => 'dashboard.marketing.campanhas',
                        'permission' => 'marketing.view'
                    ],
                    [
                        'name' => 'Modelos de Email',
                        'route' => 'dashboard.marketing.modelos',
                        'permission' => 'marketing.view'
                    ],
                    [
                        'name' => 'Lembretes',
                        'route' => 'reminders.index',
                        'permission' => 'marketing.view'
                    ],
                    [
                        'name' => 'Nova Campanha',
                        'route' => 'dashboard.marketing.campanhas',
                        'permission' => 'marketing.create',
                        'action' => 'create'
                    ]
                ]
            ],
            [
                'name' => 'Usuários',
                'icon' => 'fas fa-users-cog',
                'route' => 'dashboard.users',
                'permissions' => ['usuarios.view'],
                'is_active' => request()->routeIs('dashboard.users*') || request()->routeIs('permissions.*'),
                'submenu' => [
                    [
                        'name' => 'Listar Usuários',
                        'route' => 'dashboard.users',
                        'permission' => 'usuarios.view'
                    ],
                    [
                        'name' => 'Novo Usuário',
                        'route' => 'dashboard.users',
                        'permission' => 'usuarios.create',
                        'action' => 'create'
                    ],
                    [
                        'name' => 'Perfil de Usuário',
                        'route' => 'permissions.index',
                        'permission' => 'permissoes.view'
                    ]
                ]
            ],
            [
                'name' => 'Configurações',
                'icon' => 'fas fa-cog',
                'route' => 'dashboard.configuracoes',
                'permissions' => ['configuracoes.view'],
                'is_active' => request()->routeIs('dashboard.configuracoes*'),
                'submenu' => [
                    [
                        'name' => 'Configurações Gerais',
                        'route' => 'dashboard.configuracoes',
                        'permission' => 'configuracoes.view'
                    ]
                ]
            ]
        ];
    }

    /**
     * Assign default role to user (Super Admin for existing users)
     */
    private function assignDefaultRole(): void
    {
        if (!$this->user) {
            return;
        }

        // Buscar o role de Super Admin
        $superAdminRole = \App\Models\Role::where('name', 'super_admin')->first();
        
        if ($superAdminRole) {
            $this->user->update(['role_id' => $superAdminRole->id]);
            $this->user->refresh(); // Recarregar o usuário com o novo role
        }
    }
}
