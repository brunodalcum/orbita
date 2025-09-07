<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plano extends Model
{
    protected $fillable = [
        'nome',
        'descricao',
        'taxa',
        'taxas_detalhadas',
        'comissoes_detalhadas',
        'comissao_media',
        'operacoes',
        'status',
        'ativo',
        'adquirente_id',
        'parceiro_id'
    ];

    protected $casts = [
        'operacoes' => 'array',
        'taxa' => 'decimal:4',
        'taxas_detalhadas' => 'array',
        'comissoes_detalhadas' => 'array',
        'comissao_media' => 'decimal:4',
        'ativo' => 'boolean'
    ];

    public function operacoesRelacionadas()
    {
        return $this->belongsToMany(Operacao::class, 'operacao_plano', 'plano_id', 'operacao_id');
    }

    public function getOperacoesNomesAttribute()
    {
        if (!$this->operacoes) {
            return [];
        }
        
        return Operacao::whereIn('id', $this->operacoes)->pluck('nome')->toArray();
    }

    /**
     * Calcular comissão média baseada nas comissões detalhadas
     */
    public function calcularComissaoMedia()
    {
        if (!$this->comissoes_detalhadas) {
            return 0;
        }

        $comissaoTotal = 0;
        $contador = 0;

        // Comissões de débito
        if (isset($this->comissoes_detalhadas['debito'])) {
            foreach ($this->comissoes_detalhadas['debito'] as $comissao) {
                if (is_numeric($comissao)) {
                    $comissaoTotal += $comissao;
                    $contador++;
                }
            }
        }

        // Comissões de crédito à vista
        if (isset($this->comissoes_detalhadas['credito_vista'])) {
            foreach ($this->comissoes_detalhadas['credito_vista'] as $comissao) {
                if (is_numeric($comissao)) {
                    $comissaoTotal += $comissao;
                    $contador++;
                }
            }
        }

        // Comissões parceladas
        if (isset($this->comissoes_detalhadas['parcelado'])) {
            foreach ($this->comissoes_detalhadas['parcelado'] as $bandeira) {
                foreach ($bandeira as $comissao) {
                    if (is_numeric($comissao)) {
                        $comissaoTotal += $comissao;
                        $contador++;
                    }
                }
            }
        }

        return $contador > 0 ? $comissaoTotal / $contador : 0;
    }

    /**
     * Obter comissão para uma modalidade específica
     */
    public function getComissao($modalidade, $bandeira = null, $parcela = null)
    {
        if (!$this->comissoes_detalhadas) {
            return 0;
        }

        switch ($modalidade) {
            case 'debito':
                return $this->comissoes_detalhadas['debito'][$bandeira] ?? 0;
            
            case 'credito_vista':
                return $this->comissoes_detalhadas['credito_vista'][$bandeira] ?? 0;
            
            case 'parcelado':
                if ($bandeira && $parcela) {
                    return $this->comissoes_detalhadas['parcelado'][$bandeira][$parcela] ?? 0;
                }
                return 0;
            
            default:
                return 0;
        }
    }

    /**
     * Obter todas as comissões formatadas para exibição
     */
    public function getComissoesFormatadas()
    {
        if (!$this->comissoes_detalhadas) {
            return [];
        }

        $comissoes = [];

        // Comissões de débito
        if (isset($this->comissoes_detalhadas['debito'])) {
            $comissoes['debito'] = [];
            foreach ($this->comissoes_detalhadas['debito'] as $bandeira => $comissao) {
                $comissoes['debito'][$bandeira] = [
                    'valor' => $comissao,
                    'formatted' => number_format($comissao, 2, ',', '.') . '%'
                ];
            }
        }

        // Comissões de crédito à vista
        if (isset($this->comissoes_detalhadas['credito_vista'])) {
            $comissoes['credito_vista'] = [];
            foreach ($this->comissoes_detalhadas['credito_vista'] as $bandeira => $comissao) {
                $comissoes['credito_vista'][$bandeira] = [
                    'valor' => $comissao,
                    'formatted' => number_format($comissao, 2, ',', '.') . '%'
                ];
            }
        }

        // Comissões parceladas
        if (isset($this->comissoes_detalhadas['parcelado'])) {
            $comissoes['parcelado'] = [];
            foreach ($this->comissoes_detalhadas['parcelado'] as $bandeira => $parcelas) {
                $comissoes['parcelado'][$bandeira] = [];
                foreach ($parcelas as $parcela => $comissao) {
                    $comissoes['parcelado'][$bandeira][$parcela] = [
                        'valor' => $comissao,
                        'formatted' => number_format($comissao, 2, ',', '.') . '%'
                    ];
                }
            }
        }

        return $comissoes;
    }

    /**
     * Relacionamento com PlanoTaxa
     */
    public function taxas(): HasMany
    {
        return $this->hasMany(PlanoTaxa::class);
    }

    /**
     * Relacionamento com PlanoTaxa ativas
     */
    public function taxasAtivas(): HasMany
    {
        return $this->hasMany(PlanoTaxa::class)->where('ativo', true);
    }

    /**
     * Relacionamento com Adquirente
     */
    public function adquirente()
    {
        return $this->belongsTo(Adquirente::class);
    }

    /**
     * Relacionamento com Parceiro/Operacao
     */
    public function parceiro()
    {
        return $this->belongsTo(Operacao::class, 'parceiro_id');
    }
}
