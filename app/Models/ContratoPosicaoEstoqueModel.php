<?php

namespace App\Models;

use CodeIgniter\Model;

class ContratoPosicaoEstoqueModel extends Model
{
    protected $table            = 'contrato_posicao_estoque';
    protected $primaryKey       = 'codigo';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = [
        'numero_contrato',
        'data_referencia',
        'filial',
        'produto',
        'fixacao',
        'quebra',
        'desconto',
        'observacao'
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
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getValidations($operation): array
    {
        if (in_array($operation, [OR_INSERT, OR_UPDATE])) {
            // input fields
            $resultFields['numero_contrato'] = [
                'label' => 'Número do Contrato',
                'rules' => 'required',
                'errors' => [
                    'required' => FIELD_MESSAGE_REQUIRED
                ]
            ];

            $resultFields['data_referencia'] = [
                'label' => 'Data de Referência',
                'rules' => 'required|valid_date',
                'errors' => [
                    'required' => FIELD_MESSAGE_REQUIRED,
                    'valid_date' => 'Data inválida.'
                ]
            ];

            $resultFields['filial'] = [
                'label' => 'Filial',
                'rules' => 'required',
                'errors' => [
                    'required' => FIELD_MESSAGE_REQUIRED
                ]
            ];

            $resultFields['produto'] = [
                'label' => 'Produto',
                'rules' => 'required',
                'errors' => [
                    'required' => FIELD_MESSAGE_REQUIRED
                ]
            ];

            $resultFields['fixacao'] = [
                'label' => 'Fixacão',
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => FIELD_MESSAGE_REQUIRED,
                    'numeric' => 'Fixacão deve ser um número.'
                ]
            ];

            $resultFields['quebra'] = [
                'label' => 'Quebra',
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => FIELD_MESSAGE_REQUIRED,
                    'numeric' => 'Quebra deve ser um número.'
                ]
            ];

            $resultFields['desconto'] = [
                'label' => 'Desconto',
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => FIELD_MESSAGE_REQUIRED,
                    'numeric' => 'Quebra deve ser um número.'
                ]
            ];
        }

        return $resultFields;
    }

    public function alreadyExists($numeroContrato): bool
    {
        return $this->where('numero_contrato', $numeroContrato)
                    ->countAllResults() > 0;
    }

    public function getContratosGerenciados($startDate, $endDate, $produto, $filial)
    {
        $builder = $this->select('data_referencia, SUM(fixacao+quebra+desconto) as quantidade')
            ->where('produto', $produto)
            ->where('data_referencia >=', $startDate)
            ->where('data_referencia <=', $endDate);        

        if (isset($filial) && $filial !== '') {
            $builder->where('filial', $filial);
        }

        $builder->groupBy('data_referencia')->orderBy('data_referencia');

        return $builder->get()->getResultObject();
    }


    /**
     * Busca registros filtrando por produto (opcional) e filial (opcional)
     * com associações para trazer descrições de filial e produto
     * 
     * @param string $produto Código do produto (opcional)
     * @param string|null $filial Código da filial (opcional)
     * @return array
     */
    public function buscarPorProdutoEFilial($produto = null, $filial = null)
    {
        $builder = $this->db->table($this->table . ' AS a');
        $builder->select('
                a.codigo,
                a.numero_contrato,
                a.data_referencia,
                a.filial,
                b.descricao as nome_filial,
                a.produto,
                c.descricao as nome_produto,
                a.fixacao,
                a.quebra,
                a.desconto,
                (a.fixacao + a.quebra + a.desconto) as quantidade,
                a.observacao
            ')
            ->join('filiais b', 'b.codigo = a.filial')
            ->join('cadastro_produto c', 'c.codigo = a.produto');

        // Filtro de produto (opcional)
        if (!empty($produto)) {
            $builder->where('a.produto', $produto);
        }

        // Filtro de filial (opcional)
        if (!empty($filial)) {
            $builder->where('a.filial', $filial);
        }

        return $builder->get()->getResultObject();
    }

    public function buscarContratoPosicaoEstoqueById($codigo)
    {
        $builder = $this->db->table($this->table . ' AS a');
        $builder->select('
                a.codigo,
                a.numero_contrato,
                a.data_referencia,
                a.filial,
                b.descricao as nome_filial,
                a.produto,
                c.descricao as nome_produto,
                a.fixacao,
                a.quebra,
                a.desconto,
                (a.fixacao + a.quebra + a.desconto) as quantidade,
                a.observacao
            ')
            ->join('filiais b', 'b.codigo = a.filial')
            ->join('cadastro_produto c', 'c.codigo = a.produto')
            ->where('a.codigo', $codigo);

        // Retorna apenas o primeiro objeto em vez de um array de objetos
        $result = $builder->get()->getResultObject();
        return $result ? $result[0] : null;
    }    

}
