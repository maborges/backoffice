<?php

namespace App\Models;

use CodeIgniter\Model;

class LimiteCreditoModel extends Model
{
    protected $table = 'limite_credito';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields = [
        'id_produtor',
        'valor_limite',
        'valor_utilizado',
        'criado_por',
        'atualizado_por'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'criado_em';
    protected $updatedField  = 'atualizado_em';
    protected $deletedField  = 'excluido_em';

    protected $validationRules = [];

    protected $validationMessages = [];

    public function getValidations($operation): array
    {
        if (in_array($operation, [OR_INSERT, OR_UPDATE])) {
            // input fields
            $resultFields['fldProdutor'] = [
                'label' => 'Produtor',
                'rules' => 'required',
                'errors' => [
                    'required' => FIELD_MESSAGE_REQUIRED
                ]
            ];

            $resultFields['fldLimiteCredito'] = [
                'label' => 'Valor do Limite',
                'rules' => 'required|greater_than_equal_to[0]',
                'errors' => [
                    'required' => FIELD_MESSAGE_REQUIRED,
                    'greater_than_equal_to' => sprintf(FIELD_MESSAGE_GREATER_THAN_EQUAL_TO, '0')
                ]
            ];
        }

        return $resultFields;
    }

    public function getWithDetails($id = null)
    {
        $builder = $this->select('limite_credito.*, cadastro_pessoa.nome as nome_produtor')
            ->join('cadastro_pessoa', 'limite_credito.id_produtor = cadastro_pessoa.codigo', 'left');

        if ($id !== null) {
            $builder->where('limite_credito.id', $id);
            return $builder->first();
        }

        return $builder->findAll();
    }

    public function validateProdutor($idProdutor)
    {
        $produtorModel = new ProdutorModel();
        $produtor = $produtorModel->select('codigo')->where(['codigo' => $idProdutor])->first();
        return $produtor !== null;
    }

    public function alreadyExists($produtor)
    {
        return $this->select('id')->where('id_produtor', $produtor)->first();
    }

}
