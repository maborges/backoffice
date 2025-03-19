<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Pessoa extends Entity
{
    protected $attributes = [
        'codigo'          => null,
        'nome'            => null,
        'tipo'            => null,
        'classificacao_1' => null,
        'estado_registro' => null,
    ];

    // Mapea os campos da tabela do banco para campos da entidade
    protected $datamap = [
    ];

    protected $dates   = [];
    protected $casts   = [];

}
