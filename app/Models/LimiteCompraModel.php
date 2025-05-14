<?php

namespace App\Models;

use CodeIgniter\Model;

class LimiteCompraModel extends Model
{
    protected $table            = 'limite_compra';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_produtor',
        'id_produto',
        'quantidade_limite',
        'quantidade_utilizada',
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

            $resultFields['fldProduto'] = [
                'label' => 'Produto',
                'rules' => 'required',
                'errors' => [
                    'required' => FIELD_MESSAGE_REQUIRED
                ]
            ];

            $resultFields['fldLimiteCompra'] = [
                'label' => 'Quantidade Limite',
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
        $builder = $this->select('limite_compra.id, limite_compra.id_produtor, limite_compra.id_produto, limite_compra.quantidade_utilizada,
                                  CONCAT(descricao, " (", unidade_print, ")") as descricao_produto,c.nome as nome_produtor, d.limite_compra as quantidade_limite')
                            ->join('cadastro_produto as b', 'limite_compra.id_produto = b.codigo', 'left')
                            ->join('cadastro_pessoa as c', 'limite_compra.id_produtor = c.codigo', 'left')
                            ->join('categoria_limite_compra as d', 'd.categoria_produtor = c.categoria and d.produto = b.codigo', 'left');

        if ($id !== null) {
            $builder->where('limite_compra.id', $id);
            return $builder->first();
        }

        return $builder->findAll();
    }

    public function validateProduto($idProduto)
    {
        $produtoModel = new ProdutoModel();
        return $produtoModel->where(['codigo' => $idProduto, 'estado_registro' => 'ATIVO'])->first() !== null;
    }

    public function validateProdutor($idProdutor)
    {
        $produtorModel = new ProdutorModel();
        $produtor = $produtorModel->select('codigo')->where(['codigo' => $idProdutor])->first();
        return $produtor !== null;
    }

    public function alreadyExists($produtor, $produto)
    {
        return $this->select('id')->where('id_produtor', $produtor)->where('id_produto', $produto)->first();
    }

}
