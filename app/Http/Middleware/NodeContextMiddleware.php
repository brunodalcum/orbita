<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\OrbitaOperacao;
use App\Models\WhiteLabel;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class NodeContextMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Determinar o contexto atual baseado no domínio/subdomínio ou usuário logado
        $context = $this->determineNodeContext($request);
        
        // Compartilhar contexto com todas as views
        View::share('nodeContext', $context);
        
        // Adicionar contexto ao request para uso nos controllers
        $request->merge(['nodeContext' => $context]);
        
        // Adicionar headers de contexto para debugging
        $response = $next($request);
        
        if ($context) {
            $response->headers->set('X-Node-Type', $context['type']);
            $response->headers->set('X-Node-ID', $context['id']);
            $response->headers->set('X-Node-Name', $context['name']);
        }
        
        return $response;
    }

    /**
     * Determinar o contexto do nó atual
     */
    private function determineNodeContext(Request $request): ?array
    {
        $host = $request->getHost();
        
        // 1. Tentar determinar por domínio/subdomínio
        $context = $this->getContextByDomain($host);
        
        if ($context) {
            return $context;
        }
        
        // 2. Se não encontrou por domínio, usar contexto do usuário logado
        if (Auth::check()) {
            return $this->getContextByUser(Auth::user());
        }
        
        // 3. Contexto padrão (plataforma)
        return $this->getDefaultContext();
    }

    /**
     * Obter contexto por domínio/subdomínio
     */
    private function getContextByDomain(string $host): ?array
    {
        // Verificar White Labels
        $whiteLabel = WhiteLabel::where('domain', $host)
                                ->orWhere('subdomain', $host)
                                ->first();
        
        if ($whiteLabel) {
            return [
                'type' => 'white_label',
                'id' => $whiteLabel->id,
                'name' => $whiteLabel->display_name,
                'entity' => $whiteLabel,
                'branding' => $whiteLabel->getBrandingWithInheritance(),
                'modules' => $this->getAvailableModules($whiteLabel),
                'operacao' => $whiteLabel->operacao,
                'hierarchy_path' => "operacao:{$whiteLabel->operacao_id}/white_label:{$whiteLabel->id}"
            ];
        }
        
        // Verificar Operações
        $operacao = OrbitaOperacao::where('domain', $host)
                                  ->orWhere('subdomain', $host)
                                  ->first();
        
        if ($operacao) {
            return [
                'type' => 'operacao',
                'id' => $operacao->id,
                'name' => $operacao->display_name,
                'entity' => $operacao,
                'branding' => $operacao->getBrandingWithInheritance(),
                'modules' => $this->getAvailableModules($operacao),
                'operacao' => $operacao,
                'hierarchy_path' => "operacao:{$operacao->id}"
            ];
        }
        
        return null;
    }

    /**
     * Obter contexto por usuário logado
     */
    private function getContextByUser(User $user): array
    {
        // Se usuário tem White Label
        if ($user->white_label_id && $user->whiteLabel) {
            return [
                'type' => 'white_label',
                'id' => $user->whiteLabel->id,
                'name' => $user->whiteLabel->display_name,
                'entity' => $user->whiteLabel,
                'branding' => $user->whiteLabel->getBrandingWithInheritance(),
                'modules' => $this->getAvailableModules($user->whiteLabel),
                'operacao' => $user->whiteLabel->operacao,
                'current_user' => $user,
                'hierarchy_path' => $user->hierarchy_path ?? "user:{$user->id}"
            ];
        }
        
        // Se usuário tem Operação
        if ($user->operacao_id && $user->orbitaOperacao) {
            return [
                'type' => 'operacao',
                'id' => $user->orbitaOperacao->id,
                'name' => $user->orbitaOperacao->display_name,
                'entity' => $user->orbitaOperacao,
                'branding' => $user->orbitaOperacao->getBrandingWithInheritance(),
                'modules' => $this->getAvailableModules($user->orbitaOperacao),
                'operacao' => $user->orbitaOperacao,
                'current_user' => $user,
                'hierarchy_path' => $user->hierarchy_path ?? "user:{$user->id}"
            ];
        }
        
        // Super Admin ou usuário sem contexto específico
        return [
            'type' => 'platform',
            'id' => null,
            'name' => 'Órbita Platform',
            'entity' => null,
            'branding' => $this->getDefaultBranding(),
            'modules' => $this->getAllModules(),
            'operacao' => null,
            'current_user' => $user,
            'hierarchy_path' => "user:{$user->id}"
        ];
    }

    /**
     * Obter contexto padrão da plataforma
     */
    private function getDefaultContext(): array
    {
        return [
            'type' => 'platform',
            'id' => null,
            'name' => 'Órbita Platform',
            'entity' => null,
            'branding' => $this->getDefaultBranding(),
            'modules' => $this->getAllModules(),
            'operacao' => null,
            'current_user' => null,
            'hierarchy_path' => 'platform'
        ];
    }

    /**
     * Obter módulos disponíveis para uma entidade
     */
    private function getAvailableModules($entity): array
    {
        if (method_exists($entity, 'modules') && $entity->modules) {
            return collect($entity->modules)
                ->filter(function ($module) {
                    return isset($module['enabled']) && $module['enabled'] === true;
                })
                ->keys()
                ->toArray();
        }
        
        return $this->getAllModules();
    }

    /**
     * Obter todos os módulos disponíveis
     */
    private function getAllModules(): array
    {
        return [
            'pix',
            'bolepix', 
            'gateway',
            'gerenpix',
            'contraPDV',
            'agenda',
            'leads',
            'contracts'
        ];
    }

    /**
     * Obter branding padrão da plataforma
     */
    private function getDefaultBranding(): array
    {
        return [
            'logo_url' => '/images/orbita-logo.png',
            'primary_color' => '#3B82F6',
            'secondary_color' => '#1E40AF',
            'accent_color' => '#F59E0B',
            'text_color' => '#1F2937',
            'background_color' => '#FFFFFF',
            'font_family' => 'Inter, sans-serif'
        ];
    }
}