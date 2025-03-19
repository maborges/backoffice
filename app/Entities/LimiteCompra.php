<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class LimiteCompra extends Entity
{
    protected $attributes = [
        'id_produtor'          => null,
        'id_produto'           => null,
        'quantidade_limite'    => null,
        'quantidade_utilizada' => null,
        'criado_em'            => null,
        'criado_por'           => null,
        'atualizado_em'        => null,
        'atualizado_por'       => null,
        'excluido_em'          => null,
    ];
    protected $datamap = [];
    protected $dates   = ['criado_em', 'atualizado_em', 'excluido_em'];
    protected $casts   = [];

    /**
     * Calcula a quantidade saldo para compras.
     *
     * @return float
     */
    public function getValorSaldo(): float
    {
        return $this->quantidade_limite - $this->quantidade_utilizada;
    }
}
