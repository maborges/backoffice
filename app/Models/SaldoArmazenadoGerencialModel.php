<?php

namespace App\Models;

use CodeIgniter\Model;

class SaldoArmazenadoGerencialModel extends Model
{
    protected $table            = 'saldo_armazenado_gerencial';
    protected $primaryKey       = 'codigo';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['produto', 'filial', 'data_saldo', 'saldo'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'produto'    => 'required|integer',
        'filial'     => 'required|integer',
        'data_saldo' => 'required|valid_date',
        'saldo'      => 'required|numeric'
    ];
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

    /**
     * Busca registros filtrados por produto e filial.
     */
    public function buscarPorProdutoEFilial($produto = null, $filial = null)
    {
        $builder = $this->db->table($this->table . ' s')
            ->select('s.codigo, s.produto, s.filial, s.data_saldo, s.saldo')
            ->select('p.descricao as nome_produto')
            ->select('f.descricao as nome_filial')
            ->join('cadastro_produto p', 'p.codigo = s.produto', 'left')
            ->join('filiais f', 'f.codigo = s.filial', 'left');

        if (!empty($produto)) {
            $builder->where('s.produto', $produto);
        }

        if (!empty($filial)) {
            $builder->where('s.filial', $filial);
        }

        return $builder->get()->getResult();
    }

    /*
        Retorna o saldo armazenado gerencial do produto até a data informada por filial
        @param int $produto Código do produto
        @param int $filial Código da filial
        @param string $lastDate Data até a qual o saldo será retornado
        @return array Array com o saldo armazenado gerencial do produto até a data informada por filial
    */
    public function buscaSaldoAte($produto, $filial = null, $lastDate = null) {
        $builder = $this->db->table($this->table . ' s')
            ->select('s.filial, max(s.data_saldo) data_saldo, sum(s.saldo) saldo')
            ->where('s.produto', $produto)
            ->where('s.data_saldo <', $lastDate)
            ->groupBy('s.filial');

        if (!empty($filial)) {
            $builder->where('s.filial', $filial);
        }

        return $builder->get()->getResult();
    }


}