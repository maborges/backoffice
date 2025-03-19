<?php

namespace App\Factories;

use App\Entities\Pessoa;
use App\Entities\Produtor;

class PessoaFactory
{
    /**
     * Cria uma instância de Pessoa ou Produtor com base nos dados fornecidos.
     *
     * @param array $data
     * @return Pessoa
     */
    public static function create(array $data): Pessoa
    {
        if (isset($data['classificacao_1']) && $data['classificacao_1'] == 63) {
            return new Produtor($data);
        }

        return new Pessoa($data);
    }
}
