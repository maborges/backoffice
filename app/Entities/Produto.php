<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Produto extends Entity
{
    protected $attributes = [
        'codigo' => null,
        'nome'   => null,
    ];

    // Mapea os campos da tabela do banco para campos da entidade
    protected $datamap = [
        'nome'     => 'descricao',   
    ];

    protected $dates   = [];
    protected $casts   = [];
}
