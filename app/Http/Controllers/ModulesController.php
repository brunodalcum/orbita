<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ModulesController extends Controller
{
    /**
     * Interface principal de gerenciamento de módulos
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $availableModules = $this->getAvailableModules();
        
        // Obter módulos atuais do usuário
        $currentModules = $user->modules ?? [];
        
        // Obter módulos herdados
        $inheritedModules = $this->getInheritedModules($user);
        
        // Combinar módulos com status
        $modulesWithStatus = [];
        foreach ($availableModules as $key => $module) {
            $modulesWithStatus[$key] = array_merge($module, [
                'key' => $key,
                'enabled' => $user->hasModuleAccess($key),
                'locally_enabled' => isset($currentModules[$key]) && ($currentModules[$key]['enabled'] ?? false),
                'inherited' => !isset($currentModules[$key]) && isset($inheritedModules[$key]),
                'inherited_from' => $inheritedModules[$key]['source'] ?? null,
                'settings' => $currentModules[$key]['settings'] ?? [],
                'can_configure' => $this->canConfigureModule($user, $key),
                'dependencies_met' => $this->checkDependencies($key, $availableModules, $user)
            ]);
        }
        
        return view('hierarchy.modules.index', compact(
            'user',
            'modulesWithStatus',
            'availableModules'
        ));
    }

    /**
     * Atualizar status de um módulo
     */
    public function updateModule(Request $request, $moduleKey)
    {
        $user = Auth::user();
        $availableModules = $this->getAvailableModules();
        
        if (!isset($availableModules[$moduleKey])) {
            return response()->json(['success' => false, 'message' => 'Módulo não encontrado'], 404);
        }
        
        if (!$this->canConfigureModule($user, $moduleKey)) {
            return response()->json(['success' => false, 'message' => 'Sem permissão'], 403);
        }
        
        try {
            $currentModules = $user->modules ?? [];
            $enabled = $request->boolean('enabled');
            
            $currentModules[$moduleKey] = [
                'enabled' => $enabled,
                'settings' => $request->get('settings', []),
                'updated_at' => now()->toISOString()
            ];
            
            $user->modules = $currentModules;
            $user->save();
            
            return response()->json(['success' => true, 'message' => 'Módulo atualizado']);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Lista de módulos disponíveis
     */
    private function getAvailableModules()
    {
        return [
            'pix' => [
                'name' => 'PIX',
                'description' => 'Sistema de pagamentos instantâneos PIX',
                'icon' => 'credit-card',
                'category' => 'Pagamentos',
                'dependencies' => []
            ],
            'bolepix' => [
                'name' => 'BolePix',
                'description' => 'Geração de boletos com opção PIX',
                'icon' => 'document-text',
                'category' => 'Pagamentos',
                'dependencies' => ['pix']
            ],
            'gateway' => [
                'name' => 'Gateway',
                'description' => 'Gateway de pagamentos completo',
                'icon' => 'globe-alt',
                'category' => 'Pagamentos',
                'dependencies' => []
            ],
            'gerenpix' => [
                'name' => 'GerenPix',
                'description' => 'Gerenciamento avançado de PIX',
                'icon' => 'chart-bar',
                'category' => 'Gestão',
                'dependencies' => ['pix']
            ],
            'contraPDV' => [
                'name' => 'ContraPDV',
                'description' => 'Sistema de ponto de venda',
                'icon' => 'desktop-computer',
                'category' => 'Vendas',
                'dependencies' => ['gateway']
            ],
            'agenda' => [
                'name' => 'Agenda',
                'description' => 'Sistema de agendamentos e reuniões',
                'icon' => 'calendar',
                'category' => 'Produtividade',
                'dependencies' => []
            ],
            'leads' => [
                'name' => 'Leads',
                'description' => 'Gerenciamento de leads e prospects',
                'icon' => 'user-group',
                'category' => 'CRM',
                'dependencies' => []
            ],
            'contracts' => [
                'name' => 'Contratos',
                'description' => 'Gestão de contratos e documentos',
                'icon' => 'document-duplicate',
                'category' => 'Documentos',
                'dependencies' => []
            ]
        ];
    }

    /**
     * Obter módulos herdados
     */
    private function getInheritedModules(User $user)
    {
        $inherited = [];
        
        if ($user->whiteLabel) {
            $wlModules = $user->whiteLabel->modules ?? [];
            foreach ($wlModules as $key => $config) {
                if ($config['enabled'] ?? false) {
                    $inherited[$key] = ['source' => 'white_label', 'config' => $config];
                }
            }
        }
        
        if ($user->orbitaOperacao) {
            $opModules = $user->orbitaOperacao->modules ?? [];
            foreach ($opModules as $key => $config) {
                if (($config['enabled'] ?? false) && !isset($inherited[$key])) {
                    $inherited[$key] = ['source' => 'operacao', 'config' => $config];
                }
            }
        }
        
        return $inherited;
    }

    /**
     * Verificar se pode configurar módulo
     */
    private function canConfigureModule(User $user, $moduleKey)
    {
        return $user->isSuperAdminNode() || $user->isOperacaoNode() || $user->isWhiteLabelNode();
    }

    /**
     * Verificar dependências
     */
    private function checkDependencies($moduleKey, $availableModules, User $user)
    {
        $module = $availableModules[$moduleKey];
        $dependencies = $module['dependencies'] ?? [];
        
        foreach ($dependencies as $dependency) {
            if (!$user->hasModuleAccess($dependency)) {
                return false;
            }
        }
        
        return true;
    }
}