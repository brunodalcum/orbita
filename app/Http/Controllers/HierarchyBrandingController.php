<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\NodeBranding;
use App\Models\OrbitaOperacao;
use App\Models\WhiteLabel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class HierarchyBrandingController extends Controller
{
    /**
     * Interface principal de configuração de branding
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Verificar se é Super Admin e se foi selecionado um nó específico
        $selectedNodeId = $request->get('node_id');
        $targetUser = $user;
        
        if ($user->isSuperAdminNode() && $selectedNodeId) {
            $targetUser = User::find($selectedNodeId);
            if (!$targetUser) {
                return redirect()->route('hierarchy.branding')->with('error', 'Nó não encontrado');
            }
        }
        
        // Obter branding atual do usuário/nó selecionado (para edição, sem forçar Órbita)
        $currentBranding = $targetUser->getBrandingForEditing();
        
        // Obter branding do pai para mostrar herança
        $parentBranding = $targetUser->getParentBranding();
        
        // Obter todas as configurações disponíveis
        $availableFonts = $this->getAvailableFonts();
        $colorPresets = $this->getColorPresets();
        
        // Para Super Admin, obter lista de nós disponíveis (incluindo ele mesmo)
        $availableNodes = [];
        if ($user->isSuperAdminNode()) {
            // Incluir o próprio Super Admin no seletor
            $availableNodes = User::where(function($query) use ($user) {
                $query->whereIn('node_type', ['operacao', 'white_label', 'licenciado_l1', 'licenciado_l2', 'licenciado_l3'])
                      ->where('is_active', true);
            })
            ->orWhere('id', $user->id) // Incluir o próprio Super Admin
            ->orderByRaw("CASE WHEN id = {$user->id} THEN 0 ELSE 1 END") // Super Admin primeiro
            ->orderBy('node_type')
            ->orderBy('name')
            ->get();
        }
        
        return view('hierarchy.branding.index', compact(
            'user',
            'targetUser',
            'currentBranding',
            'parentBranding',
            'availableFonts',
            'colorPresets',
            'availableNodes',
            'selectedNodeId'
        ));
    }

    /**
     * Salvar configurações de branding
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Verificar se é Super Admin e se foi selecionado um nó específico
        $selectedNodeId = $request->get('node_id');
        $targetUser = $user;
        
        if ($user->isSuperAdminNode() && $selectedNodeId) {
            $targetUser = User::find($selectedNodeId);
            if (!$targetUser) {
                return response()->json(['error' => 'Nó não encontrado'], 404);
            }
        }
        
        $validator = Validator::make($request->all(), [
            'node_id' => 'nullable|integer|exists:users,id',
            'primary_color' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'secondary_color' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'accent_color' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'text_color' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'background_color' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'font_family' => 'nullable|string|max:100',
            'custom_css' => 'nullable|string|max:10000',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo_small' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'favicon' => 'nullable|image|mimes:png,ico|max:512',
            'inherit_from_parent' => 'nullable|in:true,false,1,0'
        ]);

        if ($validator->fails()) {
            \Log::error('Validation failed for branding save', [
                'errors' => $validator->errors(),
                'request_data' => $request->all(),
                'target_user_id' => $targetUser->id,
                'target_user_node_type' => $targetUser->node_type
            ]);
            
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Obter ou criar registro de branding
            $branding = NodeBranding::firstOrCreate([
                'node_type' => $targetUser->node_type,
                'node_id' => $targetUser->id
            ]);

                // Processar uploads de imagens
                if ($request->hasFile('logo')) {
                    $logoPath = $this->processImageUpload($request->file('logo'), 'logo', 300, 100);
                    $branding->logo_url = $logoPath;
                }

                if ($request->hasFile('logo_small')) {
                    $logoSmallPath = $this->processImageUpload($request->file('logo_small'), 'logo_small', 150, 50);
                    $branding->logo_small_url = $logoSmallPath;
                }

                if ($request->hasFile('favicon')) {
                    $faviconPath = $this->processImageUpload($request->file('favicon'), 'favicon', 32, 32);
                    $branding->favicon_url = $faviconPath;
                }

            // Atualizar cores (sempre salvar se enviado, mesmo que seja valor padrão)
            if ($request->has('primary_color')) {
                $branding->primary_color = $request->primary_color;
            }
            if ($request->has('secondary_color')) {
                $branding->secondary_color = $request->secondary_color;
            }
            if ($request->has('accent_color')) {
                $branding->accent_color = $request->accent_color;
            }
            if ($request->has('text_color')) {
                $branding->text_color = $request->text_color;
            }
            if ($request->has('background_color')) {
                $branding->background_color = $request->background_color;
            }

            // Atualizar fonte
            if ($request->has('font_family')) {
                $branding->font_family = $request->font_family;
            }

            // Atualizar CSS customizado
            if ($request->has('custom_css')) {
                $branding->custom_css = $request->custom_css;
            }

            // Configurar herança
            $branding->inherit_from_parent = $request->boolean('inherit_from_parent');

            // Salvar configurações de tema
            $themeSettings = [];
            if ($request->filled('theme_mode')) {
                $themeSettings['mode'] = $request->theme_mode;
            }
            if ($request->filled('border_radius')) {
                $themeSettings['border_radius'] = $request->border_radius;
            }
            if ($request->filled('shadow_intensity')) {
                $themeSettings['shadow_intensity'] = $request->shadow_intensity;
            }
            
            $branding->theme_settings = $themeSettings;
            $branding->save();

            return response()->json([
                'success' => true,
                'message' => 'Branding atualizado com sucesso!',
                'branding' => $branding
            ]);

        } catch (\Exception $e) {
            \Log::error('Exception during branding save', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'target_user_id' => $targetUser->id,
                'target_user_node_type' => $targetUser->node_type,
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar branding: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Preview do branding em tempo real
     */
    public function preview(Request $request)
    {
        $user = Auth::user();
        
        // Simular branding com as configurações enviadas
        $previewBranding = [
            'primary_color' => $request->get('primary_color', '#3B82F6'),
            'secondary_color' => $request->get('secondary_color', '#6B7280'),
            'accent_color' => $request->get('accent_color', '#10B981'),
            'text_color' => $request->get('text_color', '#1F2937'),
            'background_color' => $request->get('background_color', '#FFFFFF'),
            'font_family' => $request->get('font_family', 'Inter'),
            'custom_css' => $request->get('custom_css', ''),
        ];

        return view('hierarchy.branding.preview', compact('previewBranding'));
    }

    /**
     * Resetar branding para herdar do pai
     */
    public function reset(Request $request)
    {
        $user = Auth::user();
        
        // Verificar se é Super Admin e se foi selecionado um nó específico
        $selectedNodeId = $request->get('node_id');
        $targetUser = $user;
        
        if ($user->isSuperAdminNode() && $selectedNodeId) {
            $targetUser = User::find($selectedNodeId);
            if (!$targetUser) {
                return response()->json(['error' => 'Nó não encontrado'], 404);
            }
        }
        
        try {
            $branding = NodeBranding::where([
                'node_type' => $targetUser->node_type,
                'node_id' => $targetUser->id
            ])->first();

            if ($branding) {
                // Remover arquivos de imagem se existirem
                if ($branding->logo_url) {
                    Storage::disk('public')->delete($branding->logo_url);
                }
                if ($branding->logo_small_url) {
                    Storage::disk('public')->delete($branding->logo_small_url);
                }
                if ($branding->favicon_url) {
                    Storage::disk('public')->delete($branding->favicon_url);
                }

                $branding->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Branding resetado com sucesso! Agora herdando do pai.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao resetar branding: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar branding como CSS
     */
    public function exportCss(Request $request)
    {
        $user = Auth::user();
        $branding = $user->getBrandingWithInheritance();
        
        $css = $this->generateCssFromBranding($branding);
        
        return response($css)
            ->header('Content-Type', 'text/css')
            ->header('Content-Disposition', 'attachment; filename="branding-' . Str::slug($user->name) . '.css"');
    }

    /**
     * Processar upload de imagem
     */
    private function processImageUpload($file, $type, $maxWidth, $maxHeight)
    {
        $filename = $type . '_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = 'branding/' . $filename;
        
        try {
            // Criar manager de imagem
            $manager = new ImageManager(new Driver());
            
            // Redimensionar imagem mantendo proporção
            $image = $manager->read($file);
            
            // Obter dimensões originais
            $originalWidth = $image->width();
            $originalHeight = $image->height();
            
            // Calcular proporção para manter aspect ratio
            $widthRatio = $maxWidth / $originalWidth;
            $heightRatio = $maxHeight / $originalHeight;
            $ratio = min($widthRatio, $heightRatio);
            
            // Calcular novas dimensões
            $newWidth = (int)($originalWidth * $ratio);
            $newHeight = (int)($originalHeight * $ratio);
            
            // Redimensionar mantendo proporção
            $image->resize($newWidth, $newHeight);
            
            // Salvar no storage
            Storage::disk('public')->put($path, $image->encode());
            
            return $path;
        } catch (\Exception $e) {
            // Se falhar o processamento, salvar imagem original
            \Log::warning('Falha no processamento de imagem, salvando original: ' . $e->getMessage());
            
            $path = $file->store('branding', 'public');
            return $path;
        }
    }

    /**
     * Obter fontes disponíveis
     */
    private function getAvailableFonts()
    {
        return [
            'Inter' => 'Inter',
            'Roboto' => 'Roboto',
            'Open Sans' => 'Open Sans',
            'Lato' => 'Lato',
            'Montserrat' => 'Montserrat',
            'Source Sans Pro' => 'Source Sans Pro',
            'Raleway' => 'Raleway',
            'Nunito' => 'Nunito',
            'Poppins' => 'Poppins',
            'Playfair Display' => 'Playfair Display'
        ];
    }

    /**
     * Obter presets de cores
     */
    private function getColorPresets()
    {
        return [
            'default' => [
                'name' => 'Órbita Default',
                'primary_color' => '#3B82F6',
                'secondary_color' => '#6B7280',
                'accent_color' => '#10B981',
                'text_color' => '#1F2937',
                'background_color' => '#FFFFFF'
            ],
            'dark' => [
                'name' => 'Dark Mode',
                'primary_color' => '#6366F1',
                'secondary_color' => '#9CA3AF',
                'accent_color' => '#34D399',
                'text_color' => '#F9FAFB',
                'background_color' => '#111827'
            ],
            'corporate' => [
                'name' => 'Corporativo',
                'primary_color' => '#1E40AF',
                'secondary_color' => '#64748B',
                'accent_color' => '#0EA5E9',
                'text_color' => '#0F172A',
                'background_color' => '#F8FAFC'
            ],
            'nature' => [
                'name' => 'Natureza',
                'primary_color' => '#059669',
                'secondary_color' => '#6B7280',
                'accent_color' => '#84CC16',
                'text_color' => '#064E3B',
                'background_color' => '#F0FDF4'
            ],
            'sunset' => [
                'name' => 'Pôr do Sol',
                'primary_color' => '#EA580C',
                'secondary_color' => '#78716C',
                'accent_color' => '#F59E0B',
                'text_color' => '#1C1917',
                'background_color' => '#FFF7ED'
            ]
        ];
    }

    /**
     * Gerar CSS a partir do branding
     */
    private function generateCssFromBranding($branding)
    {
        $css = ":root {\n";
        
        if (isset($branding['primary_color'])) {
            $css .= "  --primary-color: {$branding['primary_color']};\n";
        }
        if (isset($branding['secondary_color'])) {
            $css .= "  --secondary-color: {$branding['secondary_color']};\n";
        }
        if (isset($branding['accent_color'])) {
            $css .= "  --accent-color: {$branding['accent_color']};\n";
        }
        if (isset($branding['text_color'])) {
            $css .= "  --text-color: {$branding['text_color']};\n";
        }
        if (isset($branding['background_color'])) {
            $css .= "  --background-color: {$branding['background_color']};\n";
        }
        if (isset($branding['font_family'])) {
            $css .= "  --font-family: '{$branding['font_family']}', sans-serif;\n";
        }
        
        $css .= "}\n\n";
        
        $css .= "body {\n";
        $css .= "  font-family: var(--font-family);\n";
        $css .= "  color: var(--text-color);\n";
        $css .= "  background-color: var(--background-color);\n";
        $css .= "}\n\n";
        
        if (isset($branding['custom_css'])) {
            $css .= $branding['custom_css'];
        }
        
        return $css;
    }
}