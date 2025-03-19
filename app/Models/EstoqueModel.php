<?php

namespace App\Models;

use CodeIgniter\Model;

class EstoqueModel extends Model
{
    protected $table            = 'estoque';
    protected $primaryKey       = 'codigo';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

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

    public function totalEstoque($filial, $produtos = [2, 3, 4, 11], $quantidadeLinhas = 0)
    {
        if ($filial) {
            $where['filial'] = $filial;
        }

        $where['estoque.estado_registro'] = 'ATIVO';

        $resultSet = $this->Select('filiais.apelido filial, cadastro_produto.produto_print produto, cadastro_produto.unidade_print unidade, cadastro_produto.nome_imagem imagem')->
                                    SelectSum("case 
                                                when estoque.movimentacao = 'SAIDA' then -estoque.quantidade
                                                else estoque.quantidade
                                            end / cadastro_produto.quantidade_un", 'total')->
                            where($where)->
                            whereIn('movimentacao', ['ENTRADA', 'SAIDA'])->
                            join('cadastro_produto', 'cadastro_produto.codigo = estoque.cod_produto')->
                            join('filiais', 'filiais.descricao = estoque.filial')->
                            groupBy('filial, cadastro_produto.produto_print, cadastro_produto.unidade_print, cadastro_produto.nome_imagem');

        if ($produtos) {
            $resultSet->whereIn('cod_produto', $produtos);
        }

        return $resultSet->findAll($quantidadeLinhas);
    }
}
