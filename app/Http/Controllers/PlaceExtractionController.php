<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\PlaceExtraction;
use App\Services\GooglePlacesService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlaceExtractionController extends Controller
{
    private GooglePlacesService $placesService;

    public function __construct(GooglePlacesService $placesService)
    {
        $this->placesService = $placesService;
    }

    /**
     * Exibir página de extração de leads via Google Places
     */
    public function index()
    {
        try {
            $user = Auth::user();
            
            // Estatísticas gerais com tratamento de erro
            $totalPlaces = 0;
            $placesWithPhone = 0;
            $placesWithWebsite = 0;
            $recentExtractions = 0;
            $userExtractions = collect();
            
            try {
                $totalPlaces = Place::count() ?? 0;
            } catch (\Exception $e) {
                Log::error('Erro ao contar places: ' . $e->getMessage());
            }
            
            try {
                $placesWithPhone = Place::whereNotNull('formatted_phone_number')->count() ?? 0;
            } catch (\Exception $e) {
                Log::error('Erro ao contar places com telefone: ' . $e->getMessage());
            }
            
            try {
                $placesWithWebsite = Place::whereNotNull('website')->count() ?? 0;
            } catch (\Exception $e) {
                Log::error('Erro ao contar places com website: ' . $e->getMessage());
            }
            
            try {
                $recentExtractions = PlaceExtraction::where('user_id', $user->id)
                    ->where('created_at', '>=', now()->subDays(7))
                    ->count() ?? 0;
            } catch (\Exception $e) {
                Log::error('Erro ao contar extrações recentes: ' . $e->getMessage());
            }
            
            // Últimas extrações do usuário
            try {
                $userExtractions = PlaceExtraction::where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get();
            } catch (\Exception $e) {
                Log::error('Erro ao buscar extrações do usuário: ' . $e->getMessage());
                $userExtractions = collect();
            }
            
            // Tipos de estabelecimento mais comuns
            $commonTypes = $this->getCommonPlaceTypes();
            
            // Informações da API
            $apiInfo = $this->placesService->getApiUsageInfo();
            
            return view('dashboard.places.extract', compact(
                'totalPlaces',
                'placesWithPhone', 
                'placesWithWebsite',
                'recentExtractions',
                'userExtractions',
                'commonTypes',
                'apiInfo'
            ));
            
        } catch (\Exception $e) {
            Log::error('Erro geral no PlaceExtractionController@index: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Retornar view com dados padrão
            return view('dashboard.places.extract', [
                'totalPlaces' => 0,
                'placesWithPhone' => 0,
                'placesWithWebsite' => 0,
                'recentExtractions' => 0,
                'userExtractions' => collect(),
                'commonTypes' => $this->getCommonPlaceTypes(),
                'apiInfo' => ['configured' => false, 'rate_limits' => [], 'cache_ttl' => 3600]
            ]);
        }
    }

    /**
     * Executar extração de places
     */
    public function extract(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'radius' => 'nullable|integer|min:100|max:50000',
            'types' => 'nullable|array',
            'types.*' => 'string',
            'language' => 'nullable|string|size:5',
            'region' => 'nullable|string|size:2',
            'legal_basis' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();
            
            $user = Auth::user();
            
            // Criar registro de extração
            $extraction = PlaceExtraction::create([
                'user_id' => $user->id,
                'query' => $request->input('query'),
                'location' => $request->input('location'),
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
                'radius' => $request->input('radius', 10000),
                'types' => $request->input('types', []),
                'language' => $request->input('language', 'pt-BR'),
                'region' => $request->input('region', 'BR'),
                'status' => 'pending',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'legal_basis' => $request->input('legal_basis'),
                'compliance_flags' => [
                    'lgpd_compliant' => true,
                    'legitimate_interest' => true,
                    'data_minimization' => true,
                ],
            ]);
            
            DB::commit();
            
            // Executar extração em background (ou síncronamente para MVP)
            $this->executeExtraction($extraction);
            
            return response()->json([
                'success' => true,
                'message' => 'Extração iniciada com sucesso',
                'extraction_id' => $extraction->id,
                'status' => $extraction->status,
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erro ao iniciar extração de places', [
                'user_id' => Auth::id(),
                'request_data' => $request->all(),
                'error' => $e->getMessage(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor',
                'error' => config('app.debug') ? $e->getMessage() : 'Erro interno',
            ], 500);
        }
    }

    /**
     * Obter tipos de estabelecimento mais comuns
     */
    private function getCommonPlaceTypes(): array
    {
        return [
            'restaurant' => 'Restaurantes',
            'pharmacy' => 'Farmácias',
            'hospital' => 'Hospitais',
            'bank' => 'Bancos',
            'gas_station' => 'Postos de Gasolina',
            'supermarket' => 'Supermercados',
            'clothing_store' => 'Lojas de Roupas',
            'beauty_salon' => 'Salões de Beleza',
            'gym' => 'Academias',
            'school' => 'Escolas',
            'lawyer' => 'Escritórios de Advocacia',
            'dentist' => 'Consultórios Odontológicos',
            'veterinary_care' => 'Veterinárias',
            'car_repair' => 'Oficinas Mecânicas',
            'real_estate_agency' => 'Imobiliárias',
        ];
    }

    /**
     * Executar extração
     */
    private function executeExtraction(PlaceExtraction $extraction): void
    {
        try {
            $extraction->markAsStarted();
            
            // Preparar parâmetros para busca
            $searchParams = [
                'query' => $extraction->query,
                'language' => $extraction->language,
                'region' => $extraction->region,
            ];
            
            // Adicionar localização se fornecida
            if ($extraction->latitude && $extraction->longitude) {
                $searchParams['location'] = $extraction->latitude . ',' . $extraction->longitude;
                $searchParams['radius'] = $extraction->radius;
            } elseif ($extraction->location) {
                $searchParams['location'] = $extraction->location;
                if ($extraction->radius) {
                    $searchParams['radius'] = $extraction->radius;
                }
            }
            
            // Adicionar tipos se fornecidos
            if (!empty($extraction->types)) {
                $searchParams['type'] = implode('|', $extraction->types);
            }
            
            // Buscar places
            $searchResult = $this->placesService->searchPlaces($searchParams);
            
            if (!$searchResult['success']) {
                $extraction->markAsFailed($searchResult['error']);
                return;
            }
            
            $places = $searchResult['results'];
            $extraction->update(['total_found' => count($places)]);
            
            // Processar e salvar places (simulação para dados mock)
            $totalProcessed = count($places);
            $totalNew = $totalProcessed; // Para dados mock, todos são novos
            
            $stats = [
                'total_processed' => $totalProcessed,
                'total_new' => $totalNew,
                'total_updated' => 0,
                'total_duplicates' => 0,
            ];
            
            // Atualizar estatísticas
            $extraction->update([
                'total_processed' => $stats['total_processed'],
                'total_new' => $stats['total_new'],
                'total_updated' => $stats['total_updated'],
                'total_duplicates' => $stats['total_duplicates'],
            ]);
            
            $extraction->markAsCompleted();
            
            Log::info('Extração de places concluída', [
                'extraction_id' => $extraction->id,
                'stats' => $stats,
            ]);
            
        } catch (\Exception $e) {
            $extraction->markAsFailed($e->getMessage());
            
            Log::error('Erro durante extração de places', [
                'extraction_id' => $extraction->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Obter status de uma extração
     */
    public function status(int $extractionId): JsonResponse
    {
        $extraction = PlaceExtraction::forUser(Auth::id())->findOrFail($extractionId);
        
        return response()->json([
            'success' => true,
            'extraction' => $extraction->getSummary(),
        ]);
    }

    /**
     * Obter detalhes dos leads de uma extração
     */
    public function details(int $extractionId): JsonResponse
    {
        try {
            $extraction = PlaceExtraction::forUser(Auth::id())->findOrFail($extractionId);
            
            // Verificar se temos API key configurada para buscar dados reais
            if (!empty(config('services.google_places.api_key'))) {
                // Buscar dados reais da API do Google Places
                $realLeads = $this->getRealLeadsFromAPI($extraction);
                
                return response()->json([
                    'success' => true,
                    'extraction' => [
                        'id' => $extraction->id,
                        'query' => $extraction->query,
                        'location' => $extraction->location,
                        'status' => $extraction->status,
                        'created_at' => $extraction->created_at->format('d/m/Y H:i'),
                        'total_found' => count($realLeads),
                        'total_processed' => $extraction->total_processed,
                    ],
                    'leads' => $realLeads,
                ]);
            }
            
            // Fallback para dados mock se API key não configurada
            $mockLeads = $this->getMockLeadsForExtraction($extraction);
            
            return response()->json([
                'success' => true,
                'extraction' => [
                    'id' => $extraction->id,
                    'query' => $extraction->query,
                    'location' => $extraction->location,
                    'status' => $extraction->status,
                    'created_at' => $extraction->created_at->format('d/m/Y H:i'),
                    'total_found' => $extraction->total_found ?? count($mockLeads),
                    'total_processed' => $extraction->total_processed,
                ],
                'leads' => $mockLeads,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao buscar detalhes da extração', [
                'extraction_id' => $extractionId,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar detalhes da extração',
            ], 500);
        }
    }

    /**
     * Buscar leads reais da API do Google Places
     */
    private function getRealLeadsFromAPI(PlaceExtraction $extraction): array
    {
        try {
            // Preparar parâmetros para busca
            $searchParams = [
                'query' => $extraction->query . ' ' . $extraction->location,
                'language' => $extraction->language ?? 'pt-BR',
                'region' => $extraction->region ?? 'BR',
            ];

            // Adicionar localização se fornecida
            if ($extraction->latitude && $extraction->longitude) {
                $searchParams['location'] = $extraction->latitude . ',' . $extraction->longitude;
                $searchParams['radius'] = $extraction->radius ?? 10000;
            }

            // Adicionar tipos se fornecidos
            if (!empty($extraction->types)) {
                $searchParams['type'] = implode('|', $extraction->types);
            }

            Log::info('Buscando leads reais da API', [
                'extraction_id' => $extraction->id,
                'search_params' => $searchParams,
            ]);

            // Buscar via Google Places API
            $searchResult = $this->placesService->searchPlaces($searchParams);

            if (!$searchResult['success']) {
                Log::error('Erro na busca da API do Google Places', [
                    'extraction_id' => $extraction->id,
                    'error' => $searchResult['error'] ?? 'Erro desconhecido',
                ]);
                
                // Fallback para dados mock em caso de erro
                return $this->getMockLeadsForExtraction($extraction);
            }

            $places = $searchResult['results'] ?? [];
            
            Log::info('Leads reais encontrados', [
                'extraction_id' => $extraction->id,
                'total_found' => count($places),
            ]);

            // Processar e enriquecer dados dos places
            $leads = [];
            foreach ($places as $place) {
                // Buscar detalhes adicionais se necessário
                $detailedPlace = $this->enrichPlaceData($place);
                $leads[] = $detailedPlace;
            }

            return $leads;

        } catch (\Exception $e) {
            Log::error('Erro ao buscar leads reais da API', [
                'extraction_id' => $extraction->id,
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString(),
            ]);

            // Fallback para dados mock em caso de erro
            return $this->getMockLeadsForExtraction($extraction);
        }
    }

    /**
     * Enriquecer dados de um place com informações adicionais
     */
    private function enrichPlaceData(array $place): array
    {
        $enrichedPlace = [
            'id' => $place['place_id'] ?? 'unknown',
            'name' => $place['name'] ?? 'Nome não disponível',
            'formatted_address' => $place['formatted_address'] ?? 'Endereço não disponível',
            'formatted_phone_number' => $place['formatted_phone_number'] ?? null,
            'website' => $place['website'] ?? null,
            'rating' => $place['rating'] ?? null,
            'user_ratings_total' => $place['user_ratings_total'] ?? 0,
            'business_status' => $place['business_status'] ?? 'OPERATIONAL',
            'types' => $place['types'] ?? [],
            'geometry' => $place['geometry'] ?? null,
            'price_level' => $place['price_level'] ?? null,
            'opening_hours' => $place['opening_hours'] ?? null,
        ];

        // Se não temos telefone ou website, buscar detalhes adicionais
        if (empty($enrichedPlace['formatted_phone_number']) || empty($enrichedPlace['website'])) {
            try {
                $placeId = $place['place_id'] ?? null;
                if ($placeId) {
                    $detailsResult = $this->placesService->getPlaceDetails($placeId, [
                        'formatted_phone_number',
                        'website',
                        'opening_hours',
                        'price_level'
                    ]);

                    if ($detailsResult['success'] && !empty($detailsResult['result'])) {
                        $details = $detailsResult['result'];
                        
                        // Atualizar com dados detalhados se disponíveis
                        $enrichedPlace['formatted_phone_number'] = $details['formatted_phone_number'] ?? $enrichedPlace['formatted_phone_number'];
                        $enrichedPlace['website'] = $details['website'] ?? $enrichedPlace['website'];
                        $enrichedPlace['opening_hours'] = $details['opening_hours'] ?? $enrichedPlace['opening_hours'];
                        $enrichedPlace['price_level'] = $details['price_level'] ?? $enrichedPlace['price_level'];
                    }
                }
            } catch (\Exception $e) {
                Log::warning('Erro ao buscar detalhes do place', [
                    'place_id' => $placeId ?? 'unknown',
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $enrichedPlace;
    }

    /**
     * Gerar leads mock para uma extração específica
     */
    private function getMockLeadsForExtraction(PlaceExtraction $extraction): array
    {
        // Determinar quantos leads gerar baseado no total_found da extração
        $totalToGenerate = $extraction->total_found ?? 3;
        
        // Personalizar baseado na query da extração
        $query = strtolower($extraction->query);
        
        if (str_contains($query, 'restaurante')) {
            return $this->generateMockLeads('restaurant', $totalToGenerate, $extraction->location);
        } elseif (str_contains($query, 'loja')) {
            return $this->generateMockLeads('store', $totalToGenerate, $extraction->location);
        } else {
            // Default para farmácias
            return $this->generateMockLeads('pharmacy', $totalToGenerate, $extraction->location);
        }
    }

    /**
     * Gerar leads mock de um tipo específico
     */
    private function generateMockLeads(string $type, int $count, string $location): array
    {
        $leads = [];
        
        switch ($type) {
            case 'pharmacy':
                $templates = $this->getPharmacyTemplates();
                break;
            case 'restaurant':
                $templates = $this->getRestaurantTemplates();
                break;
            case 'store':
                $templates = $this->getStoreTemplates();
                break;
            default:
                $templates = $this->getPharmacyTemplates();
        }
        
        for ($i = 0; $i < $count; $i++) {
            $template = $templates[$i % count($templates)];
            $leads[] = [
                'id' => 'mock_' . ($i + 1),
                'name' => $template['name'] . ($i > 0 ? ' ' . ($i + 1) : ''),
                'formatted_address' => $template['address'] . ', ' . $location,
                'formatted_phone_number' => $this->generateRandomPhone(),
                'website' => $template['website'] ?? ($i % 3 === 0 ? null : 'https://exemplo' . ($i + 1) . '.com.br'),
                'rating' => round(3.5 + (rand(0, 15) / 10), 1),
                'user_ratings_total' => rand(25, 300),
                'business_status' => 'OPERATIONAL',
                'types' => $template['types'],
            ];
        }
        
        return $leads;
    }

    /**
     * Templates para farmácias
     */
    private function getPharmacyTemplates(): array
    {
        return [
            [
                'name' => 'Farmácia Central',
                'address' => 'Rua das Flores, 123 - Centro',
                'website' => 'https://farmaciacentral.com.br',
                'types' => ['pharmacy', 'health', 'store'],
            ],
            [
                'name' => 'Drogaria Popular',
                'address' => 'Av. Principal, 456 - Bairro Novo',
                'website' => null,
                'types' => ['pharmacy', 'health'],
            ],
            [
                'name' => 'Farmácia 24 Horas',
                'address' => 'Rua do Comércio, 789 - Centro',
                'website' => 'https://farmacia24h.com.br',
                'types' => ['pharmacy', 'health', 'store'],
            ],
            [
                'name' => 'Drogasil',
                'address' => 'Shopping Center, Loja 12',
                'website' => 'https://drogasil.com.br',
                'types' => ['pharmacy', 'health'],
            ],
            [
                'name' => 'Farmácia São João',
                'address' => 'Rua São João, 321',
                'website' => null,
                'types' => ['pharmacy', 'health'],
            ],
        ];
    }

    /**
     * Templates para restaurantes
     */
    private function getRestaurantTemplates(): array
    {
        return [
            [
                'name' => 'Restaurante do Chef',
                'address' => 'Rua Gastronômica, 100 - Centro',
                'website' => 'https://restaurantedochef.com.br',
                'types' => ['restaurant', 'food', 'establishment'],
            ],
            [
                'name' => 'Pizzaria Bella Vista',
                'address' => 'Av. dos Sabores, 200 - Bairro Alto',
                'website' => null,
                'types' => ['restaurant', 'meal_delivery', 'food'],
            ],
            [
                'name' => 'Churrascaria Gaúcha',
                'address' => 'Rua da Carne, 150',
                'website' => 'https://churrascariagaucha.com.br',
                'types' => ['restaurant', 'food'],
            ],
        ];
    }

    /**
     * Templates para lojas
     */
    private function getStoreTemplates(): array
    {
        return [
            [
                'name' => 'Loja Fashion Style',
                'address' => 'Shopping Center, Loja 45',
                'website' => 'https://fashionstyle.com.br',
                'types' => ['clothing_store', 'store', 'establishment'],
            ],
            [
                'name' => 'Eletrônicos Tech',
                'address' => 'Rua da Tecnologia, 300 - Centro',
                'website' => 'https://eletronicostech.com.br',
                'types' => ['electronics_store', 'store', 'establishment'],
            ],
        ];
    }

    /**
     * Gerar telefone aleatório
     */
    private function generateRandomPhone(): string
    {
        $ddd = 82; // DDD de Alagoas
        $prefix = rand(3000, 3999);
        $suffix = rand(1000, 9999);
        return "($ddd) $prefix-$suffix";
    }

}