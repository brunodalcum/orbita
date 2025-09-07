<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Estabelecimento extends Model
{
    use HasFactory;

    protected $fillable = [
        'licenciado_id',
        'nome_fantasia',
        'razao_social',
        'cnpj',
        'email',
        'telefone',
        'celular',
        'endereco',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'cep',
        'status',
        'tipo_negocio',
        'volume_mensal_estimado',
        'documentos',
        'observacoes',
        'data_aprovacao',
        'data_bloqueio',
        'ativo'
    ];

    protected $casts = [
        'documentos' => 'array',
        'volume_mensal_estimado' => 'decimal:2',
        'data_aprovacao' => 'datetime',
        'data_bloqueio' => 'datetime',
        'ativo' => 'boolean'
    ];

    /**
     * Relacionamento com licenciado (usuário)
     */
    public function licenciado(): BelongsTo
    {
        return $this->belongsTo(User::class, 'licenciado_id');
    }

    /**
     * Scope para estabelecimentos ativos
     */
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Scope para estabelecimentos por status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope para estabelecimentos do licenciado
     */
    public function scopeDoLicenciado($query, $licenciadoId)
    {
        return $query->where('licenciado_id', $licenciadoId);
    }

    /**
     * Accessor para CNPJ formatado
     */
    public function getCnpjFormatadoAttribute()
    {
        $cnpj = preg_replace('/\D/', '', $this->cnpj);
        return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $cnpj);
    }

    /**
     * Accessor para CEP formatado
     */
    public function getCepFormatadoAttribute()
    {
        $cep = preg_replace('/\D/', '', $this->cep);
        return preg_replace('/(\d{5})(\d{3})/', '$1-$2', $cep);
    }

    /**
     * Accessor para endereço completo
     */
    public function getEnderecoCompletoAttribute()
    {
        $endereco = $this->endereco . ', ' . $this->numero;
        if ($this->complemento) {
            $endereco .= ', ' . $this->complemento;
        }
        $endereco .= ' - ' . $this->bairro . ', ' . $this->cidade . '/' . $this->estado;
        return $endereco;
    }

    /**
     * Verificar se está aprovado
     */
    public function isAprovado(): bool
    {
        return $this->status === 'ativo' && $this->data_aprovacao !== null;
    }

    /**
     * Verificar se está pendente
     */
    public function isPendente(): bool
    {
        return $this->status === 'pendente';
    }

    /**
     * Verificar se está bloqueado
     */
    public function isBloqueado(): bool
    {
        return $this->status === 'bloqueado';
    }

    /**
     * Aprovar estabelecimento
     */
    public function aprovar()
    {
        $this->update([
            'status' => 'ativo',
            'data_aprovacao' => Carbon::now(),
            'data_bloqueio' => null
        ]);
    }

    /**
     * Bloquear estabelecimento
     */
    public function bloquear($motivo = null)
    {
        $this->update([
            'status' => 'bloqueado',
            'data_bloqueio' => Carbon::now(),
            'observacoes' => $motivo ? $this->observacoes . "\n\nBloqueado: " . $motivo : $this->observacoes
        ]);
    }
}
