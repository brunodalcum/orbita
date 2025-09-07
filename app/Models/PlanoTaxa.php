<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanoTaxa extends Model
{
    use HasFactory;

    protected $table = 'planos_taxas';

    protected $fillable = [
        'plano_id',
        'modalidade',
        'bandeira',
        'parcelas',
        'taxa_percent',
        'comissao_percent',
        'ativo'
    ];

    protected $casts = [
        'taxa_percent' => 'decimal:4',
        'comissao_percent' => 'decimal:4',
        'ativo' => 'boolean',
        'parcelas' => 'integer'
    ];

    /**
     * Relacionamento com Plano
     */
    public function plano(): BelongsTo
    {
        return $this->belongsTo(Plano::class);
    }

    /**
     * Scope para modalidades específicas
     */
    public function scopeModalidade($query, $modalidades)
    {
        if (is_array($modalidades)) {
            return $query->whereIn('modalidade', $modalidades);
        }
        return $query->where('modalidade', $modalidades);
    }

    /**
     * Scope para bandeiras específicas
     */
    public function scopeBandeira($query, $bandeiras)
    {
        if (is_array($bandeiras)) {
            return $query->whereIn('bandeira', $bandeiras);
        }
        return $query->where('bandeira', $bandeiras);
    }

    /**
     * Scope para faixa de taxa
     */
    public function scopeTaxaRange($query, $min = null, $max = null)
    {
        if ($min !== null) {
            $query->where('taxa_percent', '>=', $min);
        }
        if ($max !== null) {
            $query->where('taxa_percent', '<=', $max);
        }
        return $query;
    }

    /**
     * Scope para faixa de comissão
     */
    public function scopeComissaoRange($query, $min = null, $max = null)
    {
        if ($min !== null) {
            $query->where('comissao_percent', '>=', $min);
        }
        if ($max !== null) {
            $query->where('comissao_percent', '<=', $max);
        }
        return $query;
    }

    /**
     * Scope para faixa de parcelas
     */
    public function scopeParcelasRange($query, $min = null, $max = null)
    {
        if ($min !== null) {
            $query->where('parcelas', '>=', $min);
        }
        if ($max !== null) {
            $query->where('parcelas', '<=', $max);
        }
        return $query;
    }

    /**
     * Scope para apenas taxas ativas
     */
    public function scopeAtivo($query, $ativo = true)
    {
        return $query->where('ativo', $ativo);
    }
}
