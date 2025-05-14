<?php

namespace App\Models;

use App\Models\PessoaModel;

class ProdutorModel extends PessoaModel
{
    protected $allowedFields = [
        'codigo_regiao',
        'limite_credito',
        'acumulado_credito',
        'categoria',
        'usuario_alteracao',
        'data_alteracao',
        'hora_alteracao',
        'cadastro_validado',
        'comprador',
        'validado_serasa',
        'embargado',
        'categoria_pessoa'
    ];

    /**
     * Filtra somente os registro não excluidos
     */
    protected function getFilters()
    {
        parent::getFilters();
        $this->whereIn('cadastro_pessoa.categoria_pessoa', ['P','PC','C']);
    }

    public function getWithDetails($id = null)
    {
        $builder =
        $this->db->table('cadastro_pessoa as p')
                      ->select("p.codigo,
                                p.nome,
                                p.estado,
                                p.cidade,
                                p.bairro,
                                p.limite_credito,
                                (p.limite_credito - p.acumulado_credito) as saldo_credito,
                                p.categoria,
                                p.comprador,
                                p.codigo_regiao,
                                p.cadastro_validado,
                                p.validado_serasa,
                                p.embargado,
                                max(c.data_compra) ultima_compra, 
                                r.nome_regiao,
                                u.nome_completo,
                                case
                                    when 
                                        DateDiff(CURDATE(), max(c.data_compra)) > f.dias_cliente_ativo then 'I'
                                    else 'A'
                                end as situacao")
                        ->join('limite_credito as l', 'l.id_produtor = p.codigo and l.excluido_em is null', 'left')
                        ->join('compras as c', "c.fornecedor = p.codigo and c.movimentacao = 'COMPRA' and c.estado_registro = 'ATIVO'", 'inner')
                        ->join('regiao as r', "p.codigo_regiao = r.id", 'left')
                        ->join('usuarios as u', "p.comprador = u.username", 'left')
                        ->join('configuracoes as f', "1=1", 'inner')
                        ->groupBy('p.codigo');

        if ($id !== null) {
            $builder->where('p.codigo', $id);
            return $builder->get()->getRow();
        }

        return $builder->get()->getResultArray();
    }

    public function getSituacaoProdutor($produto = null, $produtor = null, $ativo = 1)
    {

        $builder = $this->db->table('cadastro_pessoa as c')
                            ->select("c.nome AS produtor_nome,
                                      p.descricao AS produto_nome,
                                      p.qtde_dia_inatividade,
                                      MAX(co.data_compra) AS ultima_compra,
                                      co.quantidade, 
                                      co.unidade,
                                      co.valor_total,
                                      cf.dias_cliente_ativo,
                                      DATEDIFF(CURRENT_DATE, MAX(co.data_compra)) qtde_dias,
                                      CASE
                                          WHEN 
                                              DateDiff(CURDATE(), max(co.data_compra)) > cf.dias_cliente_ativo THEN 'I'
                                          ELSE 'A'
                                      END AS situacao")
                            ->join('compras as co', 'co.fornecedor = c.codigo and co.movimentacao = "COMPRA" and co.estado_registro = "ATIVO"', 'inner')
                            ->join('cadastro_produto as p', 'p.codigo = co.cod_produto', 'inner')
                            ->join('configuracoes as cf', '1=1', 'inner')
                            ->where('c.estado_registro', 'ATIVO')
                            ->groupBy('c.codigo, p.codigo');

        if ($produto) {
            $builder->where('p.codigo', $produto);
        } 

        if ($produtor) {
            $builder->where('c.codigo', $produtor);
        }

        if ($ativo == 1) {
            $builder->having('DATEDIFF(CURRENT_DATE, MAX(co.data_compra)) <= cf.dias_cliente_ativo');
        } else {
            $builder->having('DATEDIFF(CURRENT_DATE, MAX(co.data_compra)) > cf.dias_cliente_ativo');
        }

        return $builder->get()->getResultArray();
    }
    
    public function getActiveProducers($startDate = null, $endDate = null)
    {
        // Verifica se os parâmetros de data são nulos
        if (empty($startDate) || empty($endDate)) {
            // força uma range de data inválido para não trazar registros
            $startDate = date('Y-m-01', strtotime('-24 months'));
            $endDate   = date('Y-m-01');
        }
        
        $builder = $this->select("cadastro_pessoa.nome AS produtor,
                                  GROUP_CONCAT(distinct cadastro_produto.descricao SEPARATOR '/') AS produto,
                                  CONCAT(cadastro_pessoa.cidade, '-', cadastro_pessoa.estado) AS localizacao,
                                  CAST(cadastro_pessoa.id_sankhya AS CHAR(20)) AS id_sankhya, regiao.nome_regiao AS regiao,
                                  COUNT(1) AS total_pedidos,FORMAT(SUM(compras.valor_total), 2, 'de_DE') AS valor_pedidos,
                                  cadastro_pessoa.categoria, cadastro_pessoa.cadastro_validado, max(compras.data_compra) data_ultima_compra,
                                  cadastro_pessoa.validado_serasa, cadastro_pessoa.embargado")
                        ->join(
                            'compras',
                            "compras.fornecedor = cadastro_pessoa.codigo 
                                            and compras.estado_registro = 'ATIVO' 
                                            and compras.movimentacao    = 'COMPRA'",
                            'inner'
                        )
                        ->join('cadastro_produto', "cadastro_produto.codigo  = compras.cod_produto", 'inner')
                        ->join('regiao', "regiao.id = cadastro_pessoa.codigo_regiao", 'left')
                        ->where('compras.data_compra >=', $startDate)
                        ->where('compras.data_compra <=', $endDate)
                        ->groupBy('cadastro_pessoa.nome, cadastro_pessoa.estado, 
                                            cadastro_pessoa.cidade, cadastro_pessoa.id_sankhya, regiao.nome_regiao');

        return $builder->get()->getResultArray();
    }
    
    public function getValidations($operation): array
    {
        if (in_array($operation, [OR_INSERT, OR_UPDATE])) {
            // input fields
            $resultFields['limite_credito'] = [
                'label' => 'Limite de Crédito',
                'rules' => 'required|greater_than_equal_to[0]',
                'errors' => [
                    'required' => FIELD_MESSAGE_REQUIRED,
                    'greater_than_equal_to' => sprintf(FIELD_MESSAGE_GREATER_THAN_EQUAL_TO, '0')
                ]
            ];
        }

        return $resultFields;
    }

    public function getProdutoresPorComprador($comprador)
    {
        $builder = $this->select("cadastro_pessoa.codigo, cadastro_pessoa.nome")
                        ->where('cadastro_pessoa.comprador', $comprador)
                        ->where('cadastro_pessoa.estado_registro', 'ATIVO')
                        ->orderBy('cadastro_pessoa.nome');

        return $builder->get()->getResultArray();
    }
    
}
