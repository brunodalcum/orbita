@php
    $user = Auth::user();
    $branding = $user ? $user->getBrandingWithInheritance() : [];
    
    // Cores padrão se não houver branding personalizado
    $primaryColor = $branding['primary_color'] ?? '#3B82F6';
    $secondaryColor = $branding['secondary_color'] ?? '#6B7280';
    $accentColor = $branding['accent_color'] ?? '#10B981';
    $textColor = $branding['text_color'] ?? '#1F2937';
    $backgroundColor = $branding['background_color'] ?? '#FFFFFF';
    
    // Determinar se as cores são escuras ou claras para contraste
    function hexToRgb($hex) {
        $hex = ltrim($hex, '#');
        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2))
        ];
    }
    
    function getBrightness($hex) {
        $rgb = hexToRgb($hex);
        return ($rgb[0] * 299 + $rgb[1] * 587 + $rgb[2] * 114) / 1000;
    }
    
    $primaryBrightness = getBrightness($primaryColor);
    $isDarkPrimary = $primaryBrightness < 128;
    
    // Cores derivadas para diferentes elementos
    $primaryLight = $isDarkPrimary ? 
        'rgba(' . implode(',', hexToRgb($primaryColor)) . ', 0.1)' : 
        'rgba(' . implode(',', hexToRgb($primaryColor)) . ', 0.1)';
    
    $primaryDark = $isDarkPrimary ? 
        $primaryColor : 
        '#' . sprintf('%02x%02x%02x', 
            max(0, hexdec(substr($primaryColor, 1, 2)) - 30),
            max(0, hexdec(substr($primaryColor, 3, 2)) - 30),
            max(0, hexdec(substr($primaryColor, 5, 2)) - 30)
        );
    
    // Texto contrastante para botões primários
    $primaryTextColor = $isDarkPrimary ? '#FFFFFF' : '#000000';
@endphp

<style>
:root {
    /* Cores principais do branding */
    --primary-color: {{ $primaryColor }};
    --secondary-color: {{ $secondaryColor }};
    --accent-color: {{ $accentColor }};
    --text-color: {{ $textColor }};
    --background-color: {{ $backgroundColor }};
    
    /* Cores derivadas */
    --primary-light: {{ $primaryLight }};
    --primary-dark: {{ $primaryDark }};
    --primary-text: {{ $primaryTextColor }};
    
    /* Gradientes */
    --primary-gradient: linear-gradient(135deg, {{ $primaryColor }} 0%, {{ $secondaryColor }} 100%);
    --accent-gradient: linear-gradient(135deg, {{ $accentColor }} 0%, {{ $primaryColor }} 100%);
}

/* Aplicar branding aos elementos principais */
body {
    background-color: var(--background-color);
    color: var(--text-color);
}

/* Botões primários */
.btn-primary, 
.bg-blue-600, 
.bg-blue-500,
.bg-indigo-600,
.bg-indigo-500 {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
    color: var(--primary-text) !important;
}

.btn-primary:hover,
.bg-blue-600:hover,
.bg-blue-500:hover,
.bg-indigo-600:hover,
.bg-indigo-500:hover {
    background-color: var(--primary-dark) !important;
    border-color: var(--primary-dark) !important;
}

/* Botões secundários */
.btn-secondary,
.bg-gray-600,
.bg-gray-500 {
    background-color: var(--secondary-color) !important;
    border-color: var(--secondary-color) !important;
}

/* Botões de sucesso/accent */
.btn-success,
.bg-green-600,
.bg-green-500,
.bg-emerald-600,
.bg-emerald-500 {
    background-color: var(--accent-color) !important;
    border-color: var(--accent-color) !important;
}

/* Links e textos primários */
.text-blue-600,
.text-blue-500,
.text-indigo-600,
.text-indigo-500 {
    color: var(--primary-color) !important;
}

.text-green-600,
.text-green-500,
.text-emerald-600,
.text-emerald-500 {
    color: var(--accent-color) !important;
}

/* Bordas */
.border-blue-600,
.border-blue-500,
.border-indigo-600,
.border-indigo-500 {
    border-color: var(--primary-color) !important;
}

.border-green-600,
.border-green-500,
.border-emerald-600,
.border-emerald-500 {
    border-color: var(--accent-color) !important;
}

/* Backgrounds com transparência */
.bg-blue-50,
.bg-indigo-50 {
    background-color: var(--primary-light) !important;
}

/* Cards e containers */
.card-header,
.bg-primary {
    background: var(--primary-gradient) !important;
    color: var(--primary-text) !important;
}

/* Navegação e menus */
.nav-link.active,
.navbar-nav .nav-link:hover {
    color: var(--primary-color) !important;
}

/* Formulários */
.form-control:focus,
.form-select:focus {
    border-color: var(--primary-color) !important;
    box-shadow: 0 0 0 0.2rem {{ $primaryLight }} !important;
}

/* Badges */
.badge-primary,
.bg-blue-100 {
    background-color: var(--primary-light) !important;
    color: var(--primary-color) !important;
}

/* Alertas */
.alert-primary {
    background-color: var(--primary-light) !important;
    border-color: var(--primary-color) !important;
    color: var(--primary-dark) !important;
}

/* Tabelas */
.table-primary {
    background-color: var(--primary-light) !important;
}

.table thead th {
    border-bottom-color: var(--primary-color) !important;
}

/* Paginação */
.page-link {
    color: var(--primary-color) !important;
}

.page-item.active .page-link {
    background-color: var(--primary-color) !important;
    border-color: var(--primary-color) !important;
}

/* Modais */
.modal-header {
    background: var(--primary-gradient) !important;
    color: var(--primary-text) !important;
}

/* Progressos */
.progress-bar {
    background-color: var(--primary-color) !important;
}

/* Dropdowns */
.dropdown-item:hover,
.dropdown-item:focus {
    background-color: var(--primary-light) !important;
    color: var(--primary-color) !important;
}

/* Customizações específicas para elementos do dashboard */
.dashboard-card {
    border-left: 4px solid var(--primary-color);
}

.dashboard-stat {
    background: var(--primary-gradient);
    color: var(--primary-text);
}

.sidebar-gradient {
    background: var(--primary-gradient);
}

/* Animações e transições */
.btn, .nav-link, .dropdown-item, .form-control {
    transition: all 0.3s ease;
}

/* Responsividade para logos */
.logo-container img {
    max-width: 100%;
    height: auto;
    object-fit: contain;
}

/* Logo no header principal */
.main-logo {
    max-height: 60px;
    width: auto;
    object-fit: contain;
}

/* Logo na sidebar */
.sidebar-logo {
    max-height: 45px;
    width: auto;
    max-width: 180px;
    object-fit: contain;
}

/* Logo pequena */
.small-logo {
    max-height: 30px;
    width: auto;
    max-width: 120px;
    object-fit: contain;
}

/* Logo no branding */
.branding-logo {
    max-height: 80px;
    width: auto;
    object-fit: contain;
}

/* Logo no modal/detalhes */
.modal-logo {
    max-height: 50px;
    width: auto;
    object-fit: contain;
}

/* Favicon dinâmico já é tratado no layout principal */

/* Customizações para diferentes tipos de nó */
@if($user && $user->isSuperAdminNode())
    .super-admin-badge {
        background: linear-gradient(135deg, #6B46C1 0%, #9333EA 100%);
        color: white;
    }
@elseif($user && $user->node_type === 'operacao')
    .operacao-badge {
        background: linear-gradient(135deg, #DC2626 0%, #EF4444 100%);
        color: white;
    }
@elseif($user && $user->node_type === 'white_label')
    .white-label-badge {
        background: linear-gradient(135deg, #059669 0%, #10B981 100%);
        color: white;
    }
@endif

/* Efeitos hover melhorados */
.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.card:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

/* Scrollbar personalizada */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: var(--primary-color);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: var(--primary-dark);
}
</style>

@if(!empty($branding['custom_css']))
<style>
/* CSS personalizado do usuário */
{!! $branding['custom_css'] !!}
</style>
@endif
