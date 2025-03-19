<?php

namespace App\Models;

use CodeIgniter\Model;

class CarteiraProdutorModel extends Model
{
    protected $table            = 'carteira_produtor';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nome_carteira_produtor',
        'id_comprador'
    ];

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
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getValidations($operation): array
    {
        if (in_array($operation, [OR_INSERT, OR_UPDATE])) {
            // input fields
            $resultFields['nome_carteira_produtor'] = [
                'label' => 'Nome da Carteira de Produtores',
                'rules' => 'required',
                'errors' => [
                    'required' => FIELD_MESSAGE_REQUIRED
                ]
            ];
        }

        return $resultFields;
    }

    public function alreadyExists($nomeCarteiraProdutor)
    {
        return $this->select('id')->where('nome_carteira_produtor', $nomeCarteiraProdutor)->first();
    }

    public function getWithDetails($id = null)
    {
        $builder = $this->select('carteira_produtor.id, carteira_produtor.nome_carteira_produtor, carteira_produtor.id_comprador,
                                  usuarios.username, usuarios.nome_completo')
        ->join('usuarios', 'carteira_produtor.id_comprador = usuarios.username', 'left');

        if ($id !== null) {
            $builder->where('cadastro_pessoa.codigo', $id);
            return $builder->first();
        }

        return $builder->findAll();
    }

}
