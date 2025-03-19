<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Produto;

class ProdutoModel extends Model
{
    protected $table            = 'cadastro_produto';
    protected $primaryKey       = 'codigo';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['codigo','descricao', 'estado_registro'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'criado_em';
    protected $updatedField  = 'atualizado_em';
    protected $deletedField  = 'excluido_em';

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
     * Método para buscar um produto pelo código.
     *
     * @param string $id
     * @return Produto|null
     */
    public function findById($id): ?Produto
    {
        return $this->where('codigo', $id)->first();
    }

    /**
     * Filtra somente os registro não excluidos
     */
    protected function getFilters()
    {
        $this->where('estado_registro', 'ATIVO');
    }


}
