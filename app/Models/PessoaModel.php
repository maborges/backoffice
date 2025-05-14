<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Pessoa;

class PessoaModel extends Model
{
    protected $table            = 'cadastro_pessoa';
    protected $primaryKey       = 'codigo';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'codigo_regiao',
        'limite_credito',
        'categoria',
        'usuario_alteracao',
        'data_alteracao',
        'hora_alteracao',
        'cadastro_validado',
        'validado_serasa',
        'embargado',
        'comprador',
        'categoria_pessoa'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = ['getFilters'];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Filtra somente os registro não excluidos
     */
    protected function getFilters()
    {
        $this->where('cadastro_pessoa.estado_registro', 'ATIVO');
    }

    /**
     * Sobrescreve o método find para utilizar a fábrica.
     *
     * @param mixed $id
     * @return \App\Entities\Pessoa|null
     */
    public function find($id = null)
    {
        $data = parent::find($id);
        if ($data) {
            return \App\Factories\PessoaFactory::create($data);
        }
        return null;
    }

    /**
     * Sobrescreve o método findAll para utilizar a fábrica.
     *
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function findAll($limit = 0, $offset = 0): array
    {
        $results = parent::findAll($limit, $offset);
        $entities = [];
        foreach ($results as $data) {
            $entities[] = \App\Factories\PessoaFactory::create($data);
        }

        return $entities;
    }

    /**
     * Sobrescreve o método first para utilizar a fábrica.
     *
     * @return \App\Entities\Pessoa|null
     */
    public function first()
    {
        $data = parent::first();
        
        if ($data) {
            return \App\Factories\PessoaFactory::create($data);
        }

        return null;
    }


}
