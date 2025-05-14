<?php

namespace App\Models;

use CodeIgniter\Model;

class FilialCompradorModel extends Model
{
    protected $table            = 'filial_comprador';
    protected $primaryKey       = 'codigo';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'filial',
        'comprador'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;

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
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getValidations($operation): array
    {
        if (in_array($operation, [OR_INSERT, OR_UPDATE])) {
            // input fields
            $resultFields['filial'] = [
                'label' => 'Filial',
                'rules' => 'required',
                'errors' => [
                    'required' => FIELD_MESSAGE_REQUIRED
                ]
            ];
            $resultFields['comprador'] = [
                'label' => 'Comprador',
                'rules' => 'required',
                'errors' => [
                    'required' => FIELD_MESSAGE_REQUIRED
                ]
            ];
        }

        return $resultFields;
    }

    public function alreadyExists($filial, $comprador): bool
    {
        return $this->where('filial', $filial)
                    ->where('comprador', $comprador)
                    ->countAllResults() > 0;
    }

    public function getList()
    {
        $builder = $this->db->table('filial_comprador fc');
        $builder->select('fc.filial, fc.comprador, f.apelido, u.primeiro_nome');
        $builder->join('filiais f', 'f.codigo = fc.filial', 'inner');
        $builder->join('usuarios u', 'u.username = fc.comprador', 'inner');
        $builder->where('f.estado_registro', 'ATIVO');
        $builder->orderBy('f.apelido, u.primeiro_nome');
        
        return $builder->get()->getResult();
    }
}
