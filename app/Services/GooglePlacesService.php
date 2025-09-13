<?php

namespace App\Services;

use App\Models\Place;
use App\Models\PlaceExtraction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GooglePlacesService
{
    private ?string $apiKey;
    private string $baseUrl;
    private array $rateLimits;
    private int $cacheTtl;

    public function __construct()
    {
        $config = config('services.google_places');
        $this->apiKey = $config['api_key'] ?? null;
        $this->baseUrl = $config['base_url'];
        $this->rateLimits = $config['rate_limit'];
        $this->cacheTtl = $config['cache_ttl'];
        
        // Log se a API key não estiver configurada
        if (empty($this->apiKey)) {
            Log::warning('Google Places API key não configurada. Funcionalidade limitada.');
        }
    }

    /**
     * Getter para API key (para testes)
     */
    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    /**
     * Buscar places por texto e localização
     */
    public function searchPlaces(array $params): array
    {
        // Se a API key não estiver configurada, retornar dados de exemplo
        if (empty($this->apiKey)) {
            return $this->getMockSearchResults($params);
        }
        
        $cacheKey = 'places_search_' . md5(serialize($params));
        
        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($params) {
            $url = $this->baseUrl . '/textsearch/json';
            
            $queryParams = [
                'query' => $params['query'],
                'key' => $this->apiKey,
                'language' => $params['language'] ?? 'pt-BR',
                'region' => $params['region'] ?? 'BR',
            ];
            
            // Adicionar localização se fornecida
            if (isset($params['location'])) {
                $queryParams['location'] = $params['location'];
            }
            
            // Adicionar raio se fornecido
            if (isset($params['radius'])) {
                $queryParams['radius'] = $params['radius'];
            }
            
            // Adicionar tipos se fornecidos
            if (isset($params['type'])) {
                $queryParams['type'] = $params['type'];
            }
            
            try {
                $response = Http::timeout(30)->get($url, $queryParams);
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    if ($data['status'] === 'OK') {
                        return [
                            'success' => true,
                            'results' => $data['results'] ?? [],
                            'next_page_token' => $data['next_page_token'] ?? null,
                            'status' => $data['status'],
                        ];
                    } else {
                        Log::warning('Google Places API returned non-OK status', [
                            'status' => $data['status'],
                            'error_message' => $data['error_message'] ?? null,
                            'params' => $params,
                        ]);
                        
                        return [
                            'success' => false,
                            'error' => $data['error_message'] ?? 'API returned status: ' . $data['status'],
                            'status' => $data['status'],
                        ];
                    }
                } else {
                    Log::error('Google Places API request failed', [
                        'status_code' => $response->status(),
                        'response' => $response->body(),
                        'params' => $params,
                    ]);
                    
                    return [
                        'success' => false,
                        'error' => 'HTTP ' . $response->status() . ': ' . $response->body(),
                    ];
                }
            } catch (\Exception $e) {
                Log::error('Exception during Google Places API request', [
                    'message' => $e->getMessage(),
                    'params' => $params,
                ]);
                
                return [
                    'success' => false,
                    'error' => 'Exception: ' . $e->getMessage(),
                ];
            }
        });
    }

    /**
     * Obter detalhes de um place específico
     */
    public function getPlaceDetails(string $placeId, array $fields = []): array
    {
        $cacheKey = 'place_details_' . $placeId . '_' . md5(serialize($fields));
        
        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($placeId, $fields) {
            $url = $this->baseUrl . '/details/json';
            
            $defaultFields = [
                'place_id',
                'name',
                'formatted_address',
                'vicinity',
                'geometry',
                'formatted_phone_number',
                'international_phone_number',
                'website',
                'types',
                'rating',
                'user_ratings_total',
                'price_level',
                'opening_hours',
                'photos',
                'editorial_summary',
                'business_status',
                'plus_code',
            ];
            
            $requestFields = empty($fields) ? $defaultFields : $fields;
            
            $queryParams = [
                'place_id' => $placeId,
                'fields' => implode(',', $requestFields),
                'key' => $this->apiKey,
                'language' => 'pt-BR',
                'region' => 'BR',
            ];
            
            try {
                $response = Http::timeout(30)->get($url, $queryParams);
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    if ($data['status'] === 'OK') {
                        return [
                            'success' => true,
                            'result' => $data['result'] ?? [],
                            'status' => $data['status'],
                        ];
                    } else {
                        Log::warning('Google Places Details API returned non-OK status', [
                            'status' => $data['status'],
                            'error_message' => $data['error_message'] ?? null,
                            'place_id' => $placeId,
                        ]);
                        
                        return [
                            'success' => false,
                            'error' => $data['error_message'] ?? 'API returned status: ' . $data['status'],
                            'status' => $data['status'],
                        ];
                    }
                } else {
                    Log::error('Google Places Details API request failed', [
                        'status_code' => $response->status(),
                        'response' => $response->body(),
                        'place_id' => $placeId,
                    ]);
                    
                    return [
                        'success' => false,
                        'error' => 'HTTP ' . $response->status() . ': ' . $response->body(),
                    ];
                }
            } catch (\Exception $e) {
                Log::error('Exception during Google Places Details API request', [
                    'message' => $e->getMessage(),
                    'place_id' => $placeId,
                ]);
                
                return [
                    'success' => false,
                    'error' => 'Exception: ' . $e->getMessage(),
                ];
            }
        });
    }

    /**
     * Processar e salvar places encontrados
     */
    public function processAndSavePlaces(array $places, PlaceExtraction $extraction): array
    {
        $stats = [
            'total_processed' => 0,
            'total_new' => 0,
            'total_updated' => 0,
            'total_duplicates' => 0,
        ];

        foreach ($places as $placeData) {
            try {
                $stats['total_processed']++;
                
                // Verificar se já existe
                $existingPlace = Place::where('place_id', $placeData['place_id'])->first();
                
                if ($existingPlace) {
                    // Atualizar dados existentes
                    $this->updateExistingPlace($existingPlace, $placeData, $extraction);
                    $stats['total_updated']++;
                } else {
                    // Verificar duplicatas por outros critérios
                    $duplicate = $this->findDuplicatePlace($placeData);
                    
                    if ($duplicate) {
                        $stats['total_duplicates']++;
                        continue;
                    }
                    
                    // Criar novo place
                    $this->createNewPlace($placeData, $extraction);
                    $stats['total_new']++;
                }
                
                // Incrementar contador de requisições API
                $extraction->incrementApiRequests();
                
                // Rate limiting - pausa entre requisições
                usleep(50000); // 50ms = 20 requests/second (dentro do limite)
                
            } catch (\Exception $e) {
                Log::error('Error processing place', [
                    'place_id' => $placeData['place_id'] ?? 'unknown',
                    'error' => $e->getMessage(),
                    'extraction_id' => $extraction->id,
                ]);
            }
        }

        return $stats;
    }

    /**
     * Criar novo place
     */
    private function createNewPlace(array $placeData, PlaceExtraction $extraction): Place
    {
        // Obter detalhes adicionais se necessário
        $details = $this->getPlaceDetails($placeData['place_id']);
        
        if ($details['success']) {
            $placeData = array_merge($placeData, $details['result']);
        }
        
        $place = Place::create([
            'place_id' => $placeData['place_id'],
            'name' => $placeData['name'] ?? null,
            'formatted_address' => $placeData['formatted_address'] ?? null,
            'vicinity' => $placeData['vicinity'] ?? null,
            'latitude' => $placeData['geometry']['location']['lat'] ?? null,
            'longitude' => $placeData['geometry']['location']['lng'] ?? null,
            'plus_code' => $placeData['plus_code']['global_code'] ?? null,
            'formatted_phone_number' => $placeData['formatted_phone_number'] ?? null,
            'international_phone_number' => $placeData['international_phone_number'] ?? null,
            'website' => $placeData['website'] ?? null,
            'types' => $placeData['types'] ?? [],
            'rating' => $placeData['rating'] ?? null,
            'user_ratings_total' => $placeData['user_ratings_total'] ?? null,
            'price_level' => $placeData['price_level'] ?? null,
            'opening_hours' => $placeData['opening_hours'] ?? null,
            'open_now' => $placeData['opening_hours']['open_now'] ?? null,
            'photos' => $this->processPhotos($placeData['photos'] ?? []),
            'editorial_summary' => $placeData['editorial_summary']['overview'] ?? null,
            'business_status' => $placeData['business_status'] ?? null,
            'source' => 'google_places',
            'collected_at' => now(),
            'search_query' => $extraction->query,
            'search_location' => $extraction->location,
            'search_radius' => $extraction->radius,
            'phone_hash' => $this->hashPhone($placeData['formatted_phone_number'] ?? null),
            'website_domain' => $this->extractDomain($placeData['website'] ?? null),
            'address_hash' => $this->hashAddress($placeData['formatted_address'] ?? null),
            'has_phone' => !empty($placeData['formatted_phone_number']),
            'has_website' => !empty($placeData['website']),
            'is_active' => true,
        ]);
        
        // Calcular e salvar score de qualidade
        $place->updateQualityScore();
        
        return $place;
    }

    /**
     * Atualizar place existente
     */
    private function updateExistingPlace(Place $place, array $placeData, PlaceExtraction $extraction): void
    {
        // Obter detalhes atualizados
        $details = $this->getPlaceDetails($placeData['place_id']);
        
        if ($details['success']) {
            $placeData = array_merge($placeData, $details['result']);
        }
        
        $place->update([
            'name' => $placeData['name'] ?? $place->name,
            'formatted_address' => $placeData['formatted_address'] ?? $place->formatted_address,
            'vicinity' => $placeData['vicinity'] ?? $place->vicinity,
            'latitude' => $placeData['geometry']['location']['lat'] ?? $place->latitude,
            'longitude' => $placeData['geometry']['location']['lng'] ?? $place->longitude,
            'formatted_phone_number' => $placeData['formatted_phone_number'] ?? $place->formatted_phone_number,
            'international_phone_number' => $placeData['international_phone_number'] ?? $place->international_phone_number,
            'website' => $placeData['website'] ?? $place->website,
            'types' => $placeData['types'] ?? $place->types,
            'rating' => $placeData['rating'] ?? $place->rating,
            'user_ratings_total' => $placeData['user_ratings_total'] ?? $place->user_ratings_total,
            'price_level' => $placeData['price_level'] ?? $place->price_level,
            'opening_hours' => $placeData['opening_hours'] ?? $place->opening_hours,
            'open_now' => $placeData['opening_hours']['open_now'] ?? $place->open_now,
            'photos' => $this->processPhotos($placeData['photos'] ?? []),
            'editorial_summary' => $placeData['editorial_summary']['overview'] ?? $place->editorial_summary,
            'business_status' => $placeData['business_status'] ?? $place->business_status,
            'collected_at' => now(),
            'has_phone' => !empty($placeData['formatted_phone_number']),
            'has_website' => !empty($placeData['website']),
        ]);
        
        // Recalcular score de qualidade
        $place->updateQualityScore();
    }

    /**
     * Encontrar possível duplicata
     */
    private function findDuplicatePlace(array $placeData): ?Place
    {
        $phoneHash = $this->hashPhone($placeData['formatted_phone_number'] ?? null);
        $websiteDomain = $this->extractDomain($placeData['website'] ?? null);
        $addressHash = $this->hashAddress($placeData['formatted_address'] ?? null);
        
        $query = Place::query();
        
        if ($phoneHash) {
            $query->orWhere('phone_hash', $phoneHash);
        }
        
        if ($websiteDomain) {
            $query->orWhere('website_domain', $websiteDomain);
        }
        
        if ($addressHash) {
            $query->orWhere('address_hash', $addressHash);
        }
        
        return $query->first();
    }

    /**
     * Processar fotos
     */
    private function processPhotos(array $photos): array
    {
        $processedPhotos = [];
        
        foreach (array_slice($photos, 0, 5) as $photo) { // Máximo 5 fotos
            if (isset($photo['photo_reference'])) {
                $processedPhotos[] = [
                    'photo_reference' => $photo['photo_reference'],
                    'width' => $photo['width'] ?? null,
                    'height' => $photo['height'] ?? null,
                    'url' => $this->getPhotoUrl($photo['photo_reference']),
                ];
            }
        }
        
        return $processedPhotos;
    }

    /**
     * Obter URL da foto
     */
    private function getPhotoUrl(string $photoReference, int $maxWidth = 400): string
    {
        return "https://maps.googleapis.com/maps/api/place/photo?maxwidth={$maxWidth}&photo_reference={$photoReference}&key={$this->apiKey}";
    }

    /**
     * Hash do telefone para deduplicação
     */
    private function hashPhone(?string $phone): ?string
    {
        if (!$phone) return null;
        
        // Remover caracteres não numéricos
        $cleanPhone = preg_replace('/\D/', '', $phone);
        
        // Se começar com código do país, remover
        if (strlen($cleanPhone) > 10 && substr($cleanPhone, 0, 2) === '55') {
            $cleanPhone = substr($cleanPhone, 2);
        }
        
        return hash('sha256', $cleanPhone);
    }

    /**
     * Extrair domínio do website
     */
    private function extractDomain(?string $website): ?string
    {
        if (!$website) return null;
        
        $parsed = parse_url($website);
        return $parsed['host'] ?? null;
    }

    /**
     * Hash do endereço para deduplicação
     */
    private function hashAddress(?string $address): ?string
    {
        if (!$address) return null;
        
        // Normalizar endereço (remover acentos, converter para minúsculas, etc.)
        $normalized = Str::ascii(Str::lower($address));
        $normalized = preg_replace('/[^a-z0-9\s]/', '', $normalized);
        $normalized = preg_replace('/\s+/', ' ', trim($normalized));
        
        return hash('sha256', $normalized);
    }

    /**
     * Verificar se API key está configurada
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Obter informações de uso da API
     */
    public function getApiUsageInfo(): array
    {
        // Aqui você poderia implementar lógica para verificar cotas da API
        // Por enquanto, retorna informações básicas
        return [
            'configured' => $this->isConfigured(),
            'rate_limits' => $this->rateLimits,
            'cache_ttl' => $this->cacheTtl,
        ];
    }

    /**
     * Retornar dados de exemplo quando a API key não estiver configurada
     */
    private function getMockSearchResults(array $params): array
    {
        Log::info('Retornando dados de exemplo - Google Places API key não configurada', $params);
        
        return [
            'success' => true,
            'results' => [
                [
                    'place_id' => 'mock_place_1',
                    'name' => 'Estabelecimento Exemplo 1',
                    'formatted_address' => 'Rua Exemplo, 123 - Centro, São Paulo - SP, Brasil',
                    'geometry' => [
                        'location' => [
                            'lat' => -23.5505199,
                            'lng' => -46.6333094
                        ]
                    ],
                    'rating' => 4.5,
                    'user_ratings_total' => 150,
                    'types' => ['establishment', 'point_of_interest'],
                    'business_status' => 'OPERATIONAL',
                    'formatted_phone_number' => '(11) 1234-5678',
                    'website' => 'https://exemplo1.com.br',
                    'opening_hours' => [
                        'open_now' => true,
                        'weekday_text' => [
                            'Segunda-feira: 08:00–18:00',
                            'Terça-feira: 08:00–18:00',
                            'Quarta-feira: 08:00–18:00',
                            'Quinta-feira: 08:00–18:00',
                            'Sexta-feira: 08:00–18:00',
                            'Sábado: 08:00–12:00',
                            'Domingo: Fechado'
                        ]
                    ]
                ],
                [
                    'place_id' => 'mock_place_2',
                    'name' => 'Estabelecimento Exemplo 2',
                    'formatted_address' => 'Av. Exemplo, 456 - Bairro Exemplo, São Paulo - SP, Brasil',
                    'geometry' => [
                        'location' => [
                            'lat' => -23.5489,
                            'lng' => -46.6388
                        ]
                    ],
                    'rating' => 4.2,
                    'user_ratings_total' => 89,
                    'types' => ['establishment', 'point_of_interest'],
                    'business_status' => 'OPERATIONAL',
                    'formatted_phone_number' => '(11) 9876-5432',
                    'website' => 'https://exemplo2.com.br'
                ],
                [
                    'place_id' => 'mock_place_3',
                    'name' => 'Estabelecimento Exemplo 3',
                    'formatted_address' => 'Rua Teste, 789 - Vila Exemplo, São Paulo - SP, Brasil',
                    'geometry' => [
                        'location' => [
                            'lat' => -23.5558,
                            'lng' => -46.6396
                        ]
                    ],
                    'rating' => 3.8,
                    'user_ratings_total' => 45,
                    'types' => ['establishment', 'point_of_interest'],
                    'business_status' => 'OPERATIONAL'
                ]
            ],
            'status' => 'OK',
            'next_page_token' => null
        ];
    }
}
