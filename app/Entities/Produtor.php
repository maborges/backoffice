<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Produtor extends Entity
{
    /**
     * Inicializa a entidade como um Produtor.
     */
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->classificacao_1 = 63; // Garantir que o tipo do registro seja 63 - Produtor
    }

    /**
     * Método específico para Produtor.
     *
     * @return string
     */
    public function getTipoRegistroDescricao()
    {
        return 'Produtor';
    }
}
