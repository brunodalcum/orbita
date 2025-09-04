<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Operacao;

class Licenciado extends Model
{
    use HasFactory;

    protected $table = 'licenciados';

    protected $fillable = [
        'razao_social',
        'nome_fantasia',
        'cnpj_cpf',
        'endereco',
        'bairro',
        'cidade',
        'estado',
        'cep',
        'email',
        'telefone',
        'cartao_cnpj_path',
        'contrato_social_path',
        'rg_cnh_path',
        'comprovante_residencia_path',
        'comprovante_atividade_path',
        'operacoes',
        'status',
        'observacoes'
    ];

    protected $casts = [
        'operacoes' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Override the getAttribute method to ensure operacoes is always an array
     */
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);
        
        if ($key === 'operacoes') {
            // If it's already an array, return it
            if (is_array($value)) {
                return $value;
            }
            
            // If it's a string, try to decode it
            if (is_string($value)) {
                $decoded = json_decode($value, true);
                return is_array($decoded) ? $decoded : [];
            }
            
            // If it's null or other, return empty array
            return [];
        }
        
        return $value;
    }

    // Acessors
    public function getCnpjCpfFormatadoAttribute()
    {
        $cnpj_cpf = preg_replace('/[^0-9]/', '', $this->cnpj_cpf);
        
        if (strlen($cnpj_cpf) === 11) {
            // CPF: 000.000.000-00
            return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cnpj_cpf);
        } elseif (strlen($cnpj_cpf) === 14) {
            // CNPJ: 00.000.000/0000-00
            return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $cnpj_cpf);
        }
        
        return $this->cnpj_cpf;
    }

    public function getCepFormatadoAttribute()
    {
        $cep = preg_replace('/[^0-9]/', '', $this->cep);
        return preg_replace('/(\d{5})(\d{3})/', '$1-$2', $cep);
    }

    public function getTelefoneFormatadoAttribute()
    {
        $telefone = preg_replace('/[^0-9]/', '', $this->telefone);
        
        if (strlen($telefone) === 11) {
            // (11) 99999-9999
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $telefone);
        } elseif (strlen($telefone) === 10) {
            // (11) 9999-9999
            return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $telefone);
        }
        
        return $this->telefone;
    }

    public function getDataCadastroFormatadaAttribute()
    {
        return $this->created_at->format('d/m/Y');
    }

    public function getTipoPessoaAttribute()
    {
        $cnpj_cpf = preg_replace('/[^0-9]/', '', $this->cnpj_cpf);
        return strlen($cnpj_cpf) === 11 ? 'Pessoa Física' : 'Pessoa Jurídica';
    }

    public function getOperacoesSelecionadasAttribute()
    {
        if (!$this->operacoes) {
            return [];
        }
        
        // Ensure operacoes is an array
        $operacoesIds = is_array($this->operacoes) ? $this->operacoes : json_decode($this->operacoes, true);
        
        if (!$operacoesIds || !is_array($operacoesIds)) {
            return [];
        }
        
        $operacoes = Operacao::whereIn('id', $operacoesIds)->pluck('nome')->toArray();
        return $operacoes;
    }

    /**
     * Get operacoes as array, handling both string and array formats
     */
    public function getOperacoesArrayAttribute()
    {
        if (!$this->operacoes) {
            return [];
        }
        
        // If it's already an array, return it
        if (is_array($this->operacoes)) {
            return $this->operacoes;
        }
        
        // If it's a string, try to decode it
        if (is_string($this->operacoes)) {
            $decoded = json_decode($this->operacoes, true);
            return is_array($decoded) ? $decoded : [];
        }
        
        return [];
    }

    public function getStatusBadgeAttribute()
    {
        $statusMap = [
            'aprovado' => ['bg-green-100 text-green-800', 'fa-check-circle'],
            'recusado' => ['bg-red-100 text-red-800', 'fa-times-circle'],
            'em_analise' => ['bg-yellow-100 text-yellow-800', 'fa-clock'],
            'risco' => ['bg-orange-100 text-orange-800', 'fa-exclamation-triangle'],
            'ativo' => ['bg-green-100 text-green-800', 'fa-check-circle'],
            'pendente' => ['bg-yellow-100 text-yellow-800', 'fa-clock'],
            'inativo' => ['bg-red-100 text-red-800', 'fa-times-circle'],
            'vencendo' => ['bg-orange-100 text-orange-800', 'fa-exclamation-triangle'],
        ];

        $status = $this->status ?? 'em_analise';
        $config = $statusMap[$status] ?? $statusMap['em_analise'];

        return '<span class="px-2 py-1 rounded-full text-xs font-medium ' . $config[0] . '"><i class="fas ' . $config[1] . ' mr-1"></i>' . ucfirst(str_replace('_', ' ', $status)) . '</span>';
    }

    // Mutators
    public function setCnpjCpfAttribute($value)
    {
        $this->attributes['cnpj_cpf'] = preg_replace('/[^0-9]/', '', $value);
    }

    public function setCepAttribute($value)
    {
        $this->attributes['cep'] = preg_replace('/[^0-9]/', '', $value);
    }

    public function setTelefoneAttribute($value)
    {
        $this->attributes['telefone'] = preg_replace('/[^0-9]/', '', $value);
    }

    // Scopes
    public function scopeAtivos($query)
    {
        return $query->where('status', 'ativo');
    }

    public function scopePendentes($query)
    {
        return $query->where('status', 'pendente');
    }

    public function scopeVencendo($query)
    {
        return $query->where('status', 'vencendo');
    }

    public function scopeAprovados($query)
    {
        return $query->where('status', 'aprovado');
    }

    public function scopeRecusados($query)
    {
        return $query->where('status', 'recusado');
    }

    public function scopeEmAnalise($query)
    {
        return $query->where('status', 'em_analise');
    }

    public function scopeRisco($query)
    {
        return $query->where('status', 'risco');
    }

    public function scopePorOperacao($query, $operacao)
    {
        return $query->whereJsonContains('operacoes', $operacao);
    }

    // Relacionamentos
    public function followUps()
    {
        return $this->hasMany(FollowUp::class)->orderBy('created_at', 'desc');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function transacoes()
    // {
    //     return $this->hasMany(Transacao::class);
    // }
}
