<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class ViteService
{
    /**
     * Renderizar assets do Vite com fallback para CDN
     */
    public function renderAssets($assets): string
    {
        $assets = eval("return $assets;");
        
        if (!is_array($assets)) {
            $assets = [$assets];
        }
        
        $html = '';
        
        // Verificar se o manifest existe
        $manifestPath = public_path('build/manifest.json');
        
        if (File::exists($manifestPath)) {
            // Usar Vite normal se manifest existe
            try {
                $html .= app(\Illuminate\Foundation\Vite::class)($assets)->toHtml();
            } catch (\Exception $e) {
                // Fallback se houver erro com Vite
                $html .= $this->renderFallbackAssets($assets);
            }
        } else {
            // Usar fallback se manifest não existe
            $html .= $this->renderFallbackAssets($assets);
        }
        
        return $html;
    }
    
    /**
     * Renderizar assets de fallback usando CDN
     */
    private function renderFallbackAssets(array $assets): string
    {
        $html = '';
        
        foreach ($assets as $asset) {
            if (str_contains($asset, '.css')) {
                // Para CSS, usar Tailwind CDN
                $html .= '<script src="https://cdn.tailwindcss.com"></script>' . PHP_EOL;
                $html .= '<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">' . PHP_EOL;
                $html .= '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">' . PHP_EOL;
                
                // Adicionar CSS customizado inline
                $html .= '<style>
                    body { font-family: "Inter", sans-serif; }
                    .card { 
                        background: white; 
                        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); 
                        transition: all 0.3s ease; 
                    }
                    .card:hover { 
                        transform: translateY(-2px); 
                        box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); 
                    }
                    .btn-primary {
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        color: white;
                        padding: 0.5rem 1rem;
                        border-radius: 0.5rem;
                        border: none;
                        cursor: pointer;
                        transition: all 0.3s ease;
                    }
                    .btn-primary:hover {
                        transform: translateY(-1px);
                        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
                    }
                    .sidebar-active {
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        color: white;
                    }
                    .progress-bar { 
                        background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%); 
                        transition: width 0.5s ease-in-out;
                    }
                    .animate-pulse {
                        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
                    }
                    @keyframes pulse {
                        0%, 100% { opacity: 1; }
                        50% { opacity: .5; }
                    }
                    .fade-in {
                        animation: fadeIn 0.5s ease-in-out;
                    }
                    @keyframes fadeIn {
                        from { opacity: 0; transform: translateY(10px); }
                        to { opacity: 1; transform: translateY(0); }
                    }
                    .modal-backdrop {
                        background: rgba(0, 0, 0, 0.5);
                        backdrop-filter: blur(4px);
                    }
                    .toast {
                        position: fixed;
                        top: 1rem;
                        right: 1rem;
                        z-index: 9999;
                        padding: 1rem 1.5rem;
                        border-radius: 0.5rem;
                        color: white;
                        font-weight: 500;
                        box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1);
                        animation: slideIn 0.3s ease-out;
                    }
                    .toast-success { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
                    .toast-error { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
                    .toast-warning { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
                    .toast-info { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); }
                    @keyframes slideIn {
                        from { transform: translateX(100%); opacity: 0; }
                        to { transform: translateX(0); opacity: 1; }
                    }
                </style>' . PHP_EOL;
            }
            
            if (str_contains($asset, '.js')) {
                // Para JS, usar Alpine.js e outros CDNs necessários
                $html .= '<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>' . PHP_EOL;
                $html .= '<script src="https://unpkg.com/axios/dist/axios.min.js"></script>' . PHP_EOL;
                
                // Adicionar JavaScript customizado inline
                $html .= '<script>
                    // Funções utilitárias
                    window.showToast = function(message, type = "success") {
                        const toast = document.createElement("div");
                        toast.className = `toast toast-${type}`;
                        toast.textContent = message;
                        document.body.appendChild(toast);
                        
                        setTimeout(() => {
                            toast.style.animation = "slideOut 0.3s ease-in forwards";
                            setTimeout(() => toast.remove(), 300);
                        }, 3000);
                    };
                    
                    // Configurar Axios com CSRF token
                    if (typeof axios !== "undefined") {
                        axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
                        const token = document.querySelector("meta[name=csrf-token]");
                        if (token) {
                            axios.defaults.headers.common["X-CSRF-TOKEN"] = token.content;
                        }
                    }
                    
                    // Adicionar estilos para animação slideOut
                    const style = document.createElement("style");
                    style.textContent = `
                        @keyframes slideOut {
                            to { transform: translateX(100%); opacity: 0; }
                        }
                    `;
                    document.head.appendChild(style);
                </script>' . PHP_EOL;
            }
        }
        
        return $html;
    }
}
