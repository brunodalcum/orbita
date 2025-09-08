<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContractTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'version',
        'engine',
        'file_path',
        'placeholders_json',
        'content',
        'is_active',
        'description',
        'variables',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'placeholders_json' => 'array',
        'variables' => 'array',
        'is_active' => 'boolean'
    ];

    // Relacionamentos
    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class, 'template_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByEngine($query, $engine)
    {
        return $query->where('engine', $engine);
    }

    // Métodos auxiliares
    public function getPlaceholders(): array
    {
        return $this->placeholders_json ?? $this->getDefaultPlaceholders();
    }

    public function getDefaultPlaceholders(): array
    {
        return [
            // Contratante (Licenciado)
            'contratante' => [
                'nome' => 'licensee.company_name',
                'cnpj' => 'licensee.cnpj',
                'ie' => 'licensee.ie',
                'endereco' => 'licensee.address',
                'cidade' => 'licensee.city',
                'uf' => 'licensee.state',
                'cep' => 'licensee.zipcode'
            ],
            
            // Representante
            'representante' => [
                'nome' => 'licensee.representative_name',
                'cpf' => 'licensee.representative_cpf',
                'email' => 'licensee.representative_email',
                'telefone' => 'licensee.representative_phone'
            ],
            
            // Contratada (Operação)
            'contratada' => [
                'nome' => 'operation.company_name',
                'cnpj' => 'operation.cnpj',
                'endereco' => 'operation.address',
                'cidade' => 'operation.city',
                'uf' => 'operation.state',
                'cep' => 'operation.zipcode'
            ],
            
            // Contrato
            'contrato' => [
                'data' => 'contract.date',
                'id' => 'contract.id',
                'hash' => 'contract.hash'
            ]
        ];
    }

    public function processTemplate(array $data): string
    {
        $content = $this->content;
        
        if ($this->engine === 'blade') {
            return $this->processBlade($content, $data);
        }
        
        return $this->processPlaceholders($content, $data);
    }

    private function processBlade(string $content, array $data): string
    {
        // Criar view temporária
        $tempView = 'contract_temp_' . time();
        $viewPath = resource_path("views/contracts/temp/{$tempView}.blade.php");
        
        // Criar diretório se não existir
        if (!file_exists(dirname($viewPath))) {
            mkdir(dirname($viewPath), 0755, true);
        }
        
        // Escrever conteúdo
        file_put_contents($viewPath, $content);
        
        try {
            // Renderizar view
            $rendered = view("contracts.temp.{$tempView}", $data)->render();
            
            // Limpar arquivo temporário
            unlink($viewPath);
            
            return $rendered;
        } catch (\Exception $e) {
            // Limpar arquivo temporário em caso de erro
            if (file_exists($viewPath)) {
                unlink($viewPath);
            }
            throw $e;
        }
    }

    private function processPlaceholders(string $content, array $data): string
    {
        $placeholders = $this->getPlaceholders();
        
        foreach ($placeholders as $section => $fields) {
            foreach ($fields as $field => $path) {
                $placeholder = "{{ {$section}.{$field} }}";
                $value = $this->getValueFromPath($data, $path);
                $content = str_replace($placeholder, $value, $content);
            }
        }
        
        return $content;
    }

    private function getValueFromPath(array $data, string $path): string
    {
        $keys = explode('.', $path);
        $value = $data;
        
        foreach ($keys as $key) {
            if (is_array($value) && isset($value[$key])) {
                $value = $value[$key];
            } elseif (is_object($value) && isset($value->$key)) {
                $value = $value->$key;
            } else {
                return '';
            }
        }
        
        return (string) $value;
    }

    public function validateRequiredFields(array $data): array
    {
        $missing = [];
        $placeholders = $this->getPlaceholders();
        
        foreach ($placeholders as $section => $fields) {
            foreach ($fields as $field => $path) {
                $value = $this->getValueFromPath($data, $path);
                
                // Campos obrigatórios
                if (in_array("{$section}.{$field}", $this->getRequiredFields()) && empty($value)) {
                    $missing[] = "{$section}.{$field}";
                }
            }
        }
        
        return $missing;
    }

    public function getRequiredFields(): array
    {
        return [
            'contratante.nome',
            'contratante.cnpj',
            'contratante.endereco',
            'contratante.cidade',
            'contratante.uf',
            'representante.nome',
            'representante.cpf',
            'representante.email',
            'contratada.nome',
            'contratada.cnpj',
            'contrato.data',
            'contrato.id'
        ];
    }

    public static function getDefault(): ?self
    {
        return self::active()->orderBy('created_at', 'desc')->first();
    }

    public function duplicate(?string $newVersion = null): self
    {
        $newVersion = $newVersion ?: $this->incrementVersion();
        
        $duplicate = $this->replicate();
        $duplicate->version = $newVersion;
        $duplicate->is_active = false; // Nova versão inicia inativa
        $duplicate->save();
        
        return $duplicate;
    }

    private function incrementVersion(): string
    {
        $parts = explode('.', $this->version);
        $parts[count($parts) - 1] = (int)$parts[count($parts) - 1] + 1;
        return implode('.', $parts);
    }
}