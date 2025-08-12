<?php

namespace App\Models;

use CodeIgniter\Model;

class RegiaoModel extends Model
{
    protected $table            = 'regiao';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'nome_regiao',
        'criado_por',
        'atualizado_por'
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
            $resultFields['nome_regiao'] = [
                'label' => 'Nome da Região',
                'rules' => 'required',
                'errors' => [
                    'required' => FIELD_MESSAGE_REQUIRED
                ]
            ];

        }

        return $resultFields;
    }

    public function alreadyExists($nome): bool
    {
        return $this->where('nome_regiao', $nome)
                    ->countAllResults() > 0;
    }

}
