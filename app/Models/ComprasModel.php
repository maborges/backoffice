<?php

namespace App\Models;

use CodeIgniter\Model;
use DateTime;

class ComprasModel extends Model
{
    protected $table            = 'compras cpr';
    protected $primaryKey       = 'codigo';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

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

    public function quantidadeComprasPeriodo($filial, $dtInicio, $dtFim)
    {

        $where = [
            "estado_registro" => 'ATIVO',
            "movimentacao = " => 'COMPRA',
            'data_compra >= ' => $dtInicio,
            'data_compra <= ' => $dtFim
        ];

        if ($filial) {
            $where['filial'] = $filial;
        }

        return $this->selectCount('codigo', 'quantidade')->where($where)->first();
    }

    public function dataUltimaCompra($filial = '')
    {
        $where = [
            'movimentacao' => 'COMPRA',
            'estado_registro' => 'ATIVO'
        ];

        if ($filial) {
            $where = ['filial' => $filial];
        }

        return $this->selectMax('data_compra', 'dataUltimaCompra')->where($where)->first();
    }

    public function getEntregaPendente($produto = null, $produtor = null)
    {
        // produtores com entrega pendente
        $builder = $this->select('fornecedor produtor, fornecedor_print nomeProdutor, cod_produto produto, produto nomeProduto,
                                  SUM(CASE 
                                        WHEN movimentacao IN ("ENTRADA", "TRANSFERENCIA_ENTRADA", "ENTRADA_FUTURO") THEN quantidade 
                                        ELSE -quantidade 
                                      END
                                  ) AS saldo_pendente')
            ->where('estado_registro', 'ATIVO')
            ->whereIn('movimentacao', ['ENTRADA', 'TRANSFERENCIA_ENTRADA', 'ENTRADA_FUTURO', 'COMPRA', 'TRANSFERENCIA_SAIDA', 'SAIDA', 'SAIDA_FUTURO']);

        if ($produto) {
            $builder->where('cod_produto', $produto);
        } else {
            $builder->where('cod_produto', null);
        }

        if ($produtor) {
            $builder->where('fornecedor', $produtor);
        }

        $builder->groupBy('fornecedor, cod_produto');
        $builder->orderBy('fornecedor, cod_produto');
        $builder->having('saldo_pendente <', 0);

        $saldosPendentes = $builder->get()->getResultObject();

        $comprasPendentes = [];

        foreach ($saldosPendentes as $saldo) {
            $quantidadeRestante = abs($saldo->saldo_pendente);

            $offset = 0;

            while ($quantidadeRestante > 0) {
                $compras = $this->select('produto, cod_produto, numero_compra, filial, fornecedor, fornecedor_print, data_compra, quantidade, preco_unitario,
                                          valor_total, total_pago, saldo_pagar, unidade, tipo, situacao_pagamento, id_pedido_sankhya')
                    ->where('estado_registro', 'ATIVO')
                    ->where('movimentacao', 'COMPRA')
                    ->where('cod_produto', $saldo->produto)
                    ->where('fornecedor', $saldo->produtor)
                    ->limit(3, $offset)
                    ->orderBy('data_compra desc, numero_compra desc')
                    ->get()
                    ->getResultObject();

                if (empty($compras)) {
                    break;
                }

                foreach ($compras as $compra) {
                    if ($quantidadeRestante <= 0) {
                        break;
                    }

                    $quantidadeDistribuir = min($quantidadeRestante, $compra->quantidade);

                    $comprasPendentes[] = [
                        'produtor'      => $compra->fornecedor,
                        'nomeProdutor'  => $compra->fornecedor_print,
                        'produto'       => $compra->cod_produto,
                        'nomeProduto'   => $compra->produto,
                        'filial'        => $compra->filial,
                        'numeroCompra'  => $compra->numero_compra,
                        'dataCompra'    => $compra->data_compra,
                        'quantidade'    => $compra->quantidade,
                        'unidade'       => $compra->unidade,
                        'precoUnitario' => $compra->preco_unitario,
                        'totalCompra'   => $compra->valor_total,
                        'totalPago'     => $compra->total_pago,
                        'pendente'      => $quantidadeDistribuir
                    ];

                    $quantidadeRestante -= $quantidadeDistribuir;
                }

                $offset += 3;
            }
        }
        return $comprasPendentes;
    }

    public function getValidations($operation): array
    {
        if ($operation == OR_RESEARCH) {
            // input fields
            $resultFields['produtor'] = [
                'label' => 'Produtor',
                'rules' => 'required',
                'errors' => [
                    'required' => FIELD_MESSAGE_REQUIRED
                ]
            ];
        }

        return $resultFields;
    }

    public function getSaldoAnterior($produto, $produtor, $startDate)
    {
        $builder = $this->select('SUM(CASE 
                                        WHEN movimentacao IN ("ENTRADA", "TRANSFERENCIA_ENTRADA", "ENTRADA_FUTURO") THEN quantidade 
                                        ELSE -quantidade 
                                      END
                                     ) AS saldo_anterior')
            ->where('estado_registro', 'ATIVO')
            ->whereIn('movimentacao', ['ENTRADA', 'TRANSFERENCIA_ENTRADA', 'ENTRADA_FUTURO', 'COMPRA', 'TRANSFERENCIA_SAIDA', 'SAIDA', 'SAIDA_FUTURO'])
            ->where('cod_produto', $produto)
            ->where('fornecedor', $produtor)
            ->where('data_compra <', $startDate);

        return $builder->get()->getResultObject();
    }

    public function getEntradasNoPeriodo($produto, $produtor, $startDate, $endDate)
    {
        $builder = $this->select('fornecedor, data_compra, quantidade')
            ->where('estado_registro', 'ATIVO')
            ->whereIn('movimentacao', ['ENTRADA', 'TRANSFERENCIA_ENTRADA', 'ENTRADA_FUTURO'])
            ->where('cod_produto', $produto)
            ->where('fornecedor', $produtor)
            ->where('data_compra >=', $startDate)
            ->where('data_compra <=', $endDate);
        $builder->orderBy('data_compra, codigo');

        return $builder->get()->getResultObject();
    }

    public function getCompraTotal($produto, $produtor, $startDate, $endDate)
    {
        $builder = $this->select('cod_produto, produto, fornecedor, fornecedor_print, data_compra, quantidade, numero_compra, id_pedido_sankhya, tipo')
            ->where('estado_registro', 'ATIVO')
            ->whereIn('movimentacao', ['COMPRA', 'TRANSFERENCIA_SAIDA', 'SAIDA', 'SAIDA_FUTURO'])
            ->where('cod_produto', $produto)
            ->where('fornecedor', $produtor)
            ->where('data_compra >=', $startDate)
            ->where('data_compra <=', $endDate);

        return $builder->get()->getResultObject();
    }

    public function getGapCompraEntrega($startDate, $endDate, $produto, $produtor)
    {
        // Obter saldo anterior
        $saldoAnterior = $this->getSaldoAnterior($produto, $produtor, $startDate);
        $saldoAnteriorValor = 0;

        if (!empty($saldoAnterior)) {
            $saldoAnteriorValor = reset($saldoAnterior)->saldo_anterior;
        }

        // Obter entradas no período
        $entradas = $this->getEntradasNoPeriodo($produto, $produtor, $startDate, $endDate);

        // Obter compras no período especificado
        $compras = $this->getCompraTotal($produto, $produtor, $startDate, $endDate);
        $comprasPendentes = [];

        // Processar compras com base no saldo anterior e nas entradas
        foreach ($compras as $compra) {
            $quantidadeRestanteCompra = $compra->quantidade;
            $dataEntregaCompleta      = null;

            // Abater saldo anterior
            if ($saldoAnteriorValor > 0) {
                if ($saldoAnteriorValor >= $quantidadeRestanteCompra) {
                    $dataEntregaCompleta = $compra->data_compra;
                    $saldoAnteriorValor -= $quantidadeRestanteCompra;
                    $quantidadeRestanteCompra = 0;
                } else {
                    $quantidadeRestanteCompra -= $saldoAnteriorValor;
                    $saldoAnteriorValor = 0;
                }
            }

            // Processar entradas para abater a compra
            foreach ($entradas as $entrada) {
                if ($quantidadeRestanteCompra > 0) {
                    if ($entrada->quantidade       >= $quantidadeRestanteCompra) {
                        $dataEntregaCompleta       = $entrada->data_compra;
                        $entrada->quantidade      -= $quantidadeRestanteCompra;
                        $quantidadeRestanteCompra  = 0;
                    } else {
                        $quantidadeRestanteCompra -= $entrada->quantidade;
                        $entrada->quantidade = 0;
                    }
                }
            }

            // Calcular dias para entrega total
            if ($dataEntregaCompleta !== null) {
                $diasParaEntregaTotal = (strtotime($dataEntregaCompleta) - strtotime($compra->data_compra)) / (60 * 60 * 24);
                $comprasPendentes[] = (object) [
                    'codigoProduto' => $compra->cod_produto,
                    'nomeProduto' => $compra->produto,
                    'codigoProdutor' => $compra->fornecedor,
                    'nomeProdutor' => $compra->fornecedor_print,
                    'numeroCompra' => $compra->numero_compra,
                    'idSankhya' => $compra->id_pedido_sankhya,
                    'tipo' => $compra->tipo,
                    'dataCompra' => $compra->data_compra,
                    'dataEntregaFinal' => $dataEntregaCompleta,
                    'gapDias' => $diasParaEntregaTotal,
                ];
            }
        }
        return $comprasPendentes;
    }

    public function getPrecoGerencial($startDate, $endDate, $produto, $produtor, $filial)
    {
        $builder = $this->select("pes.nome, cpr.data_compra, cpr.numero_compra, cpr.produto, cpr.quantidade, cpr.valor_total, cpr.filial,
                                  cpr.preco_unitario,
                                  round(cpr.valor_total * (0.012 * (pes.tipo='PJ')),2) inss, 
                                  round(cpr.valor_total * (0.001 * (pes.tipo='PJ')),2) rat, 
                                  round(cpr.valor_total * (0.002 * (pes.tipo='PJ')),2) senar,
                                  round(cpr.valor_total * (0.015 * (pes.tipo='PJ')),2) valor_funrural,
                                  ifnull(cpr.modalidade_frete, '') modalidade_frete,
                                  cpr.quantidade * (prd.valor_desconto_frete * (ifnull(cpr.modalidade_frete, '')='CIF')) valor_frete,
                                  cpr.valor_total - (round(cpr.valor_total * (0.015 * (pes.tipo='PJ')),2) 
                                  + (cpr.quantidade * (prd.valor_desconto_frete * (ifnull(cpr.modalidade_frete, '')='CIF')))) valor_gerencial,
                                  ((cpr.valor_total - (round(cpr.valor_total * (0.015 * (pes.tipo='PJ')),2) 
                                  + (cpr.quantidade * (prd.valor_desconto_frete * (ifnull(cpr.modalidade_frete, '')='CIF'))))) / cpr.quantidade) valor_media_gerencial
                                  ")
            ->join('cadastro_pessoa pes', 'pes.codigo = cpr.fornecedor', 'inner')
            ->join('cadastro_produto prd', 'cpr.cod_produto = prd.codigo', 'inner')
            ->where('cpr.estado_registro = "ATIVO"')
            ->where('cpr.movimentacao = "COMPRA"')
            ->where('cpr.data_compra >=', $startDate)
            ->where('cpr.data_compra <=', $endDate);


        if ($produto) {
            $builder->where('cpr.cod_produto', $produto);
        }

        if ($produtor) {
            $builder->where('cpr.fornecedor', $produtor);
        }

        if ($filial) {
            $builder->where('cpr.filial', $filial);
        }

        return $builder->findAll();
    }

    public function getPrecoGerencialResumo($startDate, $endDate, $produto, $produtor, $filial)
    {
        $builder = $this->select("prd.descricao as produto,
                                  prd.unidade_print,
                                  count(1) quantidade_compra,
                                  sum(cpr.quantidade) as quantidade_total,
                                  sum(cpr.valor_total - (round(cpr.valor_total * (0.015 * (pes.tipo='PJ')),2) 
                                  + (cpr.quantidade * (prd.valor_desconto_frete * (ifnull(cpr.modalidade_frete, '')='CIF'))))) as valor_total,
                                  sum(cpr.valor_total - (round(cpr.valor_total * (0.015 * (pes.tipo='PJ')),2) 
                                  + (cpr.quantidade * (prd.valor_desconto_frete * (ifnull(cpr.modalidade_frete, '')='CIF'))))) / sum(cpr.quantidade) as valor_media,
                                  prd.nome_imagem")
            ->join('cadastro_pessoa pes', 'pes.codigo = cpr.fornecedor', 'inner')
            ->join('cadastro_produto prd', 'cpr.cod_produto = prd.codigo', 'inner')
            ->where('cpr.estado_registro = "ATIVO"')
            ->where('cpr.movimentacao = "COMPRA"')
            ->where('cpr.data_compra >=', $startDate)
            ->where('cpr.data_compra <=', $endDate);


        if ($produto) {
            $builder->where('cpr.cod_produto', $produto);
        }

        if ($produtor) {
            $builder->where('cpr.fornecedor', $produtor);
        }

        if ($filial) {
            $builder->where('cpr.filial', $filial);
        }

        $builder->groupBy('cpr.cod_produto');

        return $builder->findAll();
    }

    public function getResumoComprador($startDate, $endDate, $produto)
    {

        $sql =  "select ifnull(b.primeiro_nome, b.nome_completo) comprador,
                        sum(c.quantidade) volume_comprado,
                        avg(c.quantidade) ticket_medio,
                        f.clientes_ativos,
                        sum(c.valor_total - (round(c.valor_total * (0.015 * (p.tipo='PJ')),2) 
                           + (c.quantidade * (prd.valor_desconto_frete * (ifnull(c.modalidade_frete, '')='CIF')))) / c.quantidade) 
                           / sum(c.quantidade) preco_medio,
                        sum(c.quantidade * (ifnull(c.modalidade_frete, '')='FOB')) volume_puxar,
                        0 tempo_puxar
                   from (select distinct comprador
                           from cadastro_pessoa p
                          where p.estado_registro <> 'EXCLUIDO'
                            and p.comprador is not null
                         union 
                         select distinct comprador 
                           from compras c
                          where c.estado_registro = 'ATIVO'
                            and c.movimentacao    = 'COMPRA'
                            and c.cod_produto = '$produto'
                            and c.comprador is not null
                        order by 1) a
                        inner join usuarios b
                            on a.comprador = b.username 
                        inner join (select comprador, 
                                           count(*) clientes_ativos
                                      from cadastro_pessoa a
                                     where a.estado_registro ='ATIVO'
                                       and a.comprador is not NULL 
                                    group by comprador) f
                               on a.comprador = f.comprador
                        inner join compras c
                            on a.comprador = c.comprador
                           and c.movimentacao = 'COMPRA'
                           and c.cod_produto = '$produto'
                           and c.estado_registro = 'ATIVO'
                           and c.data_compra between '$startDate' 
                                                 and '$endDate'
                        inner join cadastro_produto prd
                            on c.cod_produto = prd.codigo
                        inner join cadastro_pessoa p
                            on c.fornecedor = p.codigo
                   group by ifnull(b.primeiro_nome, b.nome_completo)      
                   order by 3 desc
        ";

        $query = $this->query($sql);

        // Obtém os resultados
        return $query->getResult();
    }

    public function getResumoFilial($startDate, $endDate, $produto)
    {

        $sql =  "select a.apelido nome_filial,
                        sum(b.quantidade) volume_comprado,
                        avg(b.quantidade) ticket_medio,
                        f.clientes_ativos,
                        sum(b.valor_total - (round(b.valor_total * (0.015 * (p.tipo='PJ')),2) 
                           + (b.quantidade * (prd.valor_desconto_frete * (ifnull(b.modalidade_frete, '')='CIF')))) / b.quantidade) 
                           / sum(b.quantidade) preco_medio,
                        sum(b.quantidade * (ifnull(b.modalidade_frete, '')='FOB')) volume_puxar,
                        0 tempo_puxar
                    from filiais a
                        inner join compras b
                                on a.descricao = b.filial
                            and b.movimentacao = 'COMPRA'
                            and b.estado_registro = 'ATIVO'
                            and b.cod_produto = '$produto'
                            and b.data_compra between '$startDate' 
                                                    and '$endDate'
                            left join (select a.filial, 
                                            count(distinct c.codigo) clientes_ativos
                                        from compras a
                                            inner join cadastro_pessoa c
                                                    on a.fornecedor = c.codigo 
                                                and c.estado_registro = 'ATIVO'
                                                and c.comprador is not null
                                        where a.movimentacao    = 'COMPRA'
                                        and a.estado_registro = 'ATIVO'
                                        group by filial) f
                                on a.descricao = f.filial
                            inner join cadastro_pessoa p
                                on b.fornecedor = p.codigo
                            inner join cadastro_produto prd
                                on b.cod_produto = prd.codigo
                    group by a.apelido		 
        ";

        $query = $this->query($sql);

        // Obtém os resultados
        return $query->getResult();
    }

    public function getTop10Cliente($startDate, $endDate, $produto)
    {

        $sql =  "select b.nome,
                        (sum(a.quantidade) / (select sum(x.quantidade)
                                                from compras x
                                                where x.movimentacao = 'COMPRA'
                                                and x.estado_registro = 'ATIVO'
                                                and x.cod_produto = '$produto'
                                                and x.data_compra between '$startDate' 
                                                                      and '$endDate')) * 100 as participacao
                    from compras a
                        inner join cadastro_pessoa b
                                on a.fornecedor = b.codigo
                    where a.movimentacao = 'COMPRA'
                    and a.estado_registro = 'ATIVO'
                    and a.cod_produto = '$produto'
                    and a.data_compra between '$startDate' 
                                          and '$endDate'
                    group by b.nome		                         
                    order by participacao desc
                    limit 10	 
        ";

        $query = $this->query($sql);

        // Obtém os resultados
        return $query->getResult();
    }

    public function getTop10Regiao($startDate, $endDate, $produto)
    {

        $sql =  "select IFNULL(c.nome_regiao,'SEM REGIÃO') regiao,
                        (sum(a.quantidade) / (select sum(x.quantidade)
                                                from compras x
                                                where x.movimentacao = 'COMPRA'
                                                and x.estado_registro = 'ATIVO'
                                                and x.cod_produto = '$produto'
                                                and x.data_compra between '$startDate' 
                                                                      and '$endDate')) * 100 as participacao
                    from compras a
                        inner join cadastro_pessoa b
                                on a.fornecedor = b.codigo
                        left join regiao c
                                on b.codigo_regiao = c.id
                    where a.movimentacao = 'COMPRA'
                    and a.estado_registro = 'ATIVO'
                    and a.cod_produto = '$produto'
                    and a.data_compra between '$startDate' 
                                          and '$endDate'
                    group by IFNULL(c.nome_regiao,'SEM REGIÃO')	                         
                    order by participacao desc
                    limit 10	 
        ";

        $query = $this->query($sql);

        // Obtém os resultados
        return $query->getResult();
    }

    public function getResumoClassificacao($endDate, $produto)
    {
        $startDate = date('Y-m-d', strtotime('-12 months', strtotime($endDate)));
        $labels = gerarPeriodoAnoMesIndexJSON($endDate);
        $meses = json_decode($labels, true);
        $lablesFields = json_decode(gerarPeriodoAnoMesJSON($endDate), true);

        $builder = $this->db->table('cadastro_pessoa a')
            ->select("b.nome classificacao,
                        SUM(CASE WHEN MONTH(c.data_compra) = $meses[0] THEN c.quantidade ELSE 0 END) AS '$meses[0]',
                        SUM(CASE WHEN MONTH(c.data_compra) = $meses[1] THEN c.quantidade ELSE 0 END) AS '$meses[1]',
                        SUM(CASE WHEN MONTH(c.data_compra) = $meses[2] THEN c.quantidade ELSE 0 END) AS '$meses[2]',
                        SUM(CASE WHEN MONTH(c.data_compra) = $meses[3] THEN c.quantidade ELSE 0 END) AS '$meses[3]',
                        SUM(CASE WHEN MONTH(c.data_compra) = $meses[4] THEN c.quantidade ELSE 0 END) AS '$meses[4]',
                        SUM(CASE WHEN MONTH(c.data_compra) = $meses[5] THEN c.quantidade ELSE 0 END) AS '$meses[5]',
                        SUM(CASE WHEN MONTH(c.data_compra) = $meses[6] THEN c.quantidade ELSE 0 END) AS '$meses[6]',
                        SUM(CASE WHEN MONTH(c.data_compra) = $meses[7] THEN c.quantidade ELSE 0 END) AS '$meses[7]',
                        SUM(CASE WHEN MONTH(c.data_compra) = $meses[8] THEN c.quantidade ELSE 0 END) AS '$meses[8]',
                        SUM(CASE WHEN MONTH(c.data_compra) = $meses[9] THEN c.quantidade ELSE 0 END) AS '$meses[9]',
                        SUM(CASE WHEN MONTH(c.data_compra) = $meses[10] THEN c.quantidade ELSE 0 END) AS '$meses[10]',
                        SUM(CASE WHEN MONTH(c.data_compra) = $meses[11] THEN c.quantidade ELSE 0 END) AS '$meses[11]'")
            ->join('classificacao_pessoa b', 'a.classificacao_1 = b.codigo', 'inner')
            ->join('compras c', 'a.codigo = c.fornecedor', 'inner')
            ->where('a.estado_registro', 'ATIVO')
            ->where('c.estado_registro', 'ATIVO')
            ->where('c.movimentacao', 'COMPRA')
            ->where('c.cod_produto', $produto)
            ->where('c.data_compra >=', $startDate)
            ->where('c.data_compra <=', $endDate)
            ->where('b.movimenta_estoque', 'S')
            ->groupBy('b.nome');

        $classificacoes = $builder->get()->getResult();
        $totaisColunas = [
            $meses[0] => 0,
            $meses[1] => 0,
            $meses[2] => 0,
            $meses[3] => 0,
            $meses[4] => 0,
            $meses[5] => 0,
            $meses[6] => 0,
            $meses[7] => 0,
            $meses[8] => 0,
            $meses[9] => 0,
            $meses[10] => 0,
            $meses[11] => 0,
        ];

        foreach ($classificacoes as $classificacao) {
            foreach ($meses as $mes) {
                $totaisColunas[$mes] += $classificacao->{$mes} ?? 0;
            }
        }

        $areaChartData = [];
        $areaChartData['labels'] = $lablesFields;

        $datasets = [];
        foreach ($classificacoes as $classificacao) {
            $datasets[] = [
                'label' => $classificacao->classificacao,
                'pointRadius' => true,
                'data' => [
                    (ROUND(($classificacao->{$meses[0]} / ($totaisColunas[$meses[0]] == 0 ? 1 : $totaisColunas[$meses[0]])) * 100, 2)),
                    (ROUND(($classificacao->{$meses[1]} / ($totaisColunas[$meses[1]] == 0 ? 1 : $totaisColunas[$meses[1]])) * 100, 2)),
                    (ROUND(($classificacao->{$meses[2]} / ($totaisColunas[$meses[2]] == 0 ? 1 : $totaisColunas[$meses[2]])) * 100, 2)),
                    (ROUND(($classificacao->{$meses[3]} / ($totaisColunas[$meses[3]] == 0 ? 1 : $totaisColunas[$meses[3]])) * 100, 2)),
                    (ROUND(($classificacao->{$meses[4]} / ($totaisColunas[$meses[4]] == 0 ? 1 : $totaisColunas[$meses[4]])) * 100, 2)),
                    (ROUND(($classificacao->{$meses[5]} / ($totaisColunas[$meses[5]] == 0 ? 1 : $totaisColunas[$meses[5]])) * 100, 2)),
                    (ROUND(($classificacao->{$meses[6]} / ($totaisColunas[$meses[6]] == 0 ? 1 : $totaisColunas[$meses[6]])) * 100, 2)),
                    (ROUND(($classificacao->{$meses[7]} / ($totaisColunas[$meses[7]] == 0 ? 1 : $totaisColunas[$meses[7]])) * 100, 2)),
                    (ROUND(($classificacao->{$meses[8]} / ($totaisColunas[$meses[8]] == 0 ? 1 : $totaisColunas[$meses[8]])) * 100, 2)),
                    (ROUND(($classificacao->{$meses[9]} / ($totaisColunas[$meses[9]] == 0 ? 1 : $totaisColunas[$meses[9]]) ) * 100, 2)),
                    (ROUND(($classificacao->{$meses[10]} / ($totaisColunas[$meses[10]] == 0 ? 1 : $totaisColunas[$meses[10]])) * 100, 2)),
                    (ROUND(($classificacao->{$meses[11]} / ($totaisColunas[$meses[11]] == 0 ? 1 : $totaisColunas[$meses[11]])) * 100, 2))
                ]
            ];
        }
        
        $areaChartData['datasets'] = $datasets;
        return json_encode($areaChartData);
    }

    public function getDashboardFiliais($endDate, $produto)
    {
        $startDate = date('Y-m-d', strtotime('-12 months', strtotime($endDate)));
        $labels = gerarPeriodoAnoMesIndexJSON($endDate, 'Ym');

        log_message('info', 'Labels: ' . json_encode($labels));


        $meses = json_decode($labels, true);
        $lablesFields = json_decode(gerarPeriodoAnoMesJSON($endDate), true);
        
        $builder = $this->db->table('filiais a')
            ->select("a.apelido filial,
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[0] THEN c.quantidade ELSE 0 END) AS '$meses[0]',
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[1] THEN c.quantidade ELSE 0 END) AS '$meses[1]',
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[2] THEN c.quantidade ELSE 0 END) AS '$meses[2]',
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[3] THEN c.quantidade ELSE 0 END) AS '$meses[3]',
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[4] THEN c.quantidade ELSE 0 END) AS '$meses[4]',
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[5] THEN c.quantidade ELSE 0 END) AS '$meses[5]',
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[6] THEN c.quantidade ELSE 0 END) AS '$meses[6]',
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[7] THEN c.quantidade ELSE 0 END) AS '$meses[7]',
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[8] THEN c.quantidade ELSE 0 END) AS '$meses[8]',
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[9] THEN c.quantidade ELSE 0 END) AS '$meses[9]',
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[10] THEN c.quantidade ELSE 0 END) AS '$meses[10]',
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[11] THEN c.quantidade ELSE 0 END) AS '$meses[11]'")
            ->join('compras c', 'a.descricao = c.filial', 'inner')
            ->where('a.estado_registro', 'ATIVO')
            ->where('c.estado_registro', 'ATIVO')
            ->where('c.movimentacao', 'COMPRA')
            ->where('c.cod_produto', $produto)
            ->where('c.data_compra >=', $startDate)
            ->where('c.data_compra <=', $endDate)
            ->groupBy('c.filial')
            ->orderBy('c.data_compra, c.filial', 'ASC');

        $filiais = $builder->get()->getResult();

        $totaisColunas = [
            $meses[0] => 0,
            $meses[1] => 0,
            $meses[2] => 0,
            $meses[3] => 0,
            $meses[4] => 0,
            $meses[5] => 0,
            $meses[6] => 0,
            $meses[7] => 0,
            $meses[8] => 0,
            $meses[9] => 0,
            $meses[10] => 0,
            $meses[11] => 0,
        ];

        foreach ($filiais as $filial) {
            foreach ($meses as $mes) {
                $totaisColunas[$mes] += $filial->{$mes} ?? 0;
            }
        }

        $areaChartData = [];
        $areaChartData['labels'] = $lablesFields;

        $datasets = [];
        foreach ($filiais as $filial) {
            $datasets[] = [
                'label' => $filial->filial,
                'pointRadius' => true,
                'data' => [
                    ROUND(($filial->{$meses[0]} / ($totaisColunas[$meses[0]] == 0 ? 1 : $totaisColunas[$meses[0]])) * 100, 2),
                    ROUND(($filial->{$meses[1]} / ($totaisColunas[$meses[1]] == 0 ? 1 : $totaisColunas[$meses[1]])) * 100, 2),
                    ROUND(($filial->{$meses[2]} / ($totaisColunas[$meses[2]] == 0 ? 1 : $totaisColunas[$meses[2]])) * 100, 2),
                    ROUND(($filial->{$meses[3]} / ($totaisColunas[$meses[3]] == 0 ? 1 : $totaisColunas[$meses[3]])) * 100, 2),
                    ROUND(($filial->{$meses[4]} / ($totaisColunas[$meses[4]] == 0 ? 1 : $totaisColunas[$meses[4]])) * 100, 2),
                    ROUND(($filial->{$meses[5]} / ($totaisColunas[$meses[5]] == 0 ? 1 : $totaisColunas[$meses[5]])) * 100, 2),
                    ROUND(($filial->{$meses[6]} / ($totaisColunas[$meses[6]] == 0 ? 1 : $totaisColunas[$meses[6]])) * 100, 2),
                    ROUND(($filial->{$meses[7]} / ($totaisColunas[$meses[7]] == 0 ? 1 : $totaisColunas[$meses[7]])) * 100, 2),
                    ROUND(($filial->{$meses[8]} / ($totaisColunas[$meses[8]] == 0 ? 1 : $totaisColunas[$meses[8]])) * 100, 2),
                    ROUND(($filial->{$meses[9]} / ($totaisColunas[$meses[9]] == 0 ? 1 : $totaisColunas[$meses[9]])) * 100, 2),
                    ROUND(($filial->{$meses[10]} / ($totaisColunas[$meses[10]] == 0 ? 1 : $totaisColunas[$meses[10]])) * 100, 2),
                    ROUND(($filial->{$meses[11]} / ($totaisColunas[$meses[11]] == 0 ? 1 : $totaisColunas[$meses[11]])) * 100, 2)
                ]
            ];
        }
        
        $areaChartData['datasets'] = $datasets;
        return json_encode($areaChartData);
    }

    public function getDashboardComprador($endDate, $produto)
    {
        $startDate = date('Y-m-d', strtotime('-12 months', strtotime($endDate)));
        $labels = gerarPeriodoAnoMesIndexJSON($endDate, 'Ym');
        $meses = json_decode($labels, true);
        $lablesFields = json_decode(gerarPeriodoAnoMesJSON($endDate), true);
        
        $builder = $this->db->table('usuarios a')
            ->select("ifnull(a.primeiro_nome, a.nome_completo) comprador,
                        SUM(c.quantidade) total,
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[0] THEN c.quantidade ELSE 0 END) AS '$meses[0]',
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[1] THEN c.quantidade ELSE 0 END) AS '$meses[1]',
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[2] THEN c.quantidade ELSE 0 END) AS '$meses[2]',
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[3] THEN c.quantidade ELSE 0 END) AS '$meses[3]',
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[4] THEN c.quantidade ELSE 0 END) AS '$meses[4]',
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[5] THEN c.quantidade ELSE 0 END) AS '$meses[5]',
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[6] THEN c.quantidade ELSE 0 END) AS '$meses[6]',
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[7] THEN c.quantidade ELSE 0 END) AS '$meses[7]',
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[8] THEN c.quantidade ELSE 0 END) AS '$meses[8]',
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[9] THEN c.quantidade ELSE 0 END) AS '$meses[9]',
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[10] THEN c.quantidade ELSE 0 END) AS '$meses[10]',
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[11] THEN c.quantidade ELSE 0 END) AS '$meses[11]'")
            ->join('compras c', 'a.username = c.comprador', 'inner')
            ->where('c.estado_registro', 'ATIVO')
            ->where('c.movimentacao', 'COMPRA')
            ->where('c.cod_produto', $produto)
            ->where('c.data_compra >=', $startDate)
            ->where('c.data_compra <=', $endDate)
            ->groupBy('ifnull(a.primeiro_nome, a.nome_completo)')
            ->orderBy('total', 'DESC')
            ->limit(10);

        $compradores = $builder->get()->getResult();

        $totaisColunas = [
            $meses[0] => 0,
            $meses[1] => 0,
            $meses[2] => 0,
            $meses[3] => 0,
            $meses[4] => 0,
            $meses[5] => 0,
            $meses[6] => 0,
            $meses[7] => 0,
            $meses[8] => 0,
            $meses[9] => 0,
            $meses[10] => 0,
            $meses[11] => 0,
        ];

        foreach ($compradores as $comprador) {
            foreach ($meses as $mes) {
                $totaisColunas[$mes] += $comprador->{$mes} ?? 0;
            }
        }

        $areaChartData = [];
        $areaChartData['labels'] = $lablesFields;

        $datasets = [];
        foreach ($compradores as $comprador) {
            $datasets[] = [
                'label' => $comprador->comprador,
                'pointRadius' => true,
                'data' => [
                    (ROUND(($comprador->{$meses[0]} / ($totaisColunas[$meses[0]] == 0 ? 1 : $totaisColunas[$meses[0]])) * 100, 2)),
                    (ROUND(($comprador->{$meses[1]} / ($totaisColunas[$meses[1]] == 0 ? 1 : $totaisColunas[$meses[1]])) * 100, 2)),
                    (ROUND(($comprador->{$meses[2]} / ($totaisColunas[$meses[2]] == 0 ? 1 : $totaisColunas[$meses[2]])) * 100, 2)),
                    (ROUND(($comprador->{$meses[3]} / ($totaisColunas[$meses[3]] == 0 ? 1 : $totaisColunas[$meses[3]])) * 100, 2)),
                    (ROUND(($comprador->{$meses[4]} / ($totaisColunas[$meses[4]] == 0 ? 1 : $totaisColunas[$meses[4]])) * 100, 2)),
                    (ROUND(($comprador->{$meses[5]} / ($totaisColunas[$meses[5]] == 0 ? 1 : $totaisColunas[$meses[5]])) * 100, 2)),
                    (ROUND(($comprador->{$meses[6]} / ($totaisColunas[$meses[6]] == 0 ? 1 : $totaisColunas[$meses[6]])) * 100, 2)),
                    (ROUND(($comprador->{$meses[7]} / ($totaisColunas[$meses[7]] == 0 ? 1 : $totaisColunas[$meses[7]])) * 100, 2)),
                    (ROUND(($comprador->{$meses[8]} / ($totaisColunas[$meses[8]] == 0 ? 1 : $totaisColunas[$meses[8]])) * 100, 2)),
                    (ROUND(($comprador->{$meses[9]} / ($totaisColunas[$meses[9]] == 0 ? 1 : $totaisColunas[$meses[9]])) * 100, 2)),
                    (ROUND(($comprador->{$meses[10]} / ($totaisColunas[$meses[10]] == 0 ? 1 : $totaisColunas[$meses[10]])) * 100, 2)),
                    (ROUND(($comprador->{$meses[11]} / ($totaisColunas[$meses[11]] == 0 ? 1 : $totaisColunas[$meses[11]])) * 100, 2))
                ]
            ];
        }
        
        $areaChartData['datasets'] = $datasets;
        return json_encode($areaChartData);
    }

    public function getDashboardFiliaisClassificacao($endDate, $produto)
    {
        $startDate = date('Y-m-d', strtotime('-12 months', strtotime($endDate)));
        $labels = gerarPeriodoAnoMesIndexJSON($endDate, 'Ym');
        $meses = json_decode($labels, true);
        $lablesFields = json_decode(gerarPeriodoAnoMesJSON($endDate), true);

        $builder = $this->db->table('cadastro_pessoa a')
            ->select("concat(f.apelido, '-',b.nome) classificacao,
                        SUM(c.quantidade) total,
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[0] THEN c.quantidade ELSE 0 END) AS '$meses[0]',
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[1] THEN c.quantidade ELSE 0 END) AS '$meses[1]',
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[2] THEN c.quantidade ELSE 0 END) AS '$meses[2]',
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[3] THEN c.quantidade ELSE 0 END) AS '$meses[3]',
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[4] THEN c.quantidade ELSE 0 END) AS '$meses[4]',
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[5] THEN c.quantidade ELSE 0 END) AS '$meses[5]',
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[6] THEN c.quantidade ELSE 0 END) AS '$meses[6]',
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[7] THEN c.quantidade ELSE 0 END) AS '$meses[7]',
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[8] THEN c.quantidade ELSE 0 END) AS '$meses[8]',
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[9] THEN c.quantidade ELSE 0 END) AS '$meses[9]',
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[10] THEN c.quantidade ELSE 0 END) AS '$meses[10]',
                        SUM(CASE WHEN DATE_FORMAT(c.data_compra, '%Y%m') = $meses[11] THEN c.quantidade ELSE 0 END) AS '$meses[11]'")
            ->join('classificacao_pessoa b', 'a.classificacao_1 = b.codigo', 'inner')
            ->join('compras c', 'a.codigo = c.fornecedor', 'inner')
            ->join('filiais f', 'c.filial = f.descricao', 'inner')
            ->where('a.estado_registro', 'ATIVO')
            ->where('c.estado_registro', 'ATIVO')
            ->where('c.movimentacao', 'COMPRA')
            ->where('c.cod_produto', $produto)
            ->where('c.data_compra >=', $startDate)
            ->where('c.data_compra <=', $endDate)
            ->where('b.movimenta_estoque', 'S')
            ->groupBy('f.apelido, b.nome')
            ->orderBy('total', 'ASC');

        $classificacoes = $builder->get()->getResult();
        $totaisColunas = [
            $meses[0] => 0,
            $meses[1] => 0,
            $meses[2] => 0,
            $meses[3] => 0,
            $meses[4] => 0,
            $meses[5] => 0,
            $meses[6] => 0,
            $meses[7] => 0,
            $meses[8] => 0,
            $meses[9] => 0,
            $meses[10] => 0,
            $meses[11] => 0,
        ];

        foreach ($classificacoes as $classificacao) {
            foreach ($meses as $mes) {
                $totaisColunas[$mes] += $classificacao->{$mes} ?? 0;
            }
        }

        $areaChartData = [];
        $areaChartData['labels'] = $lablesFields;

        $datasets = [];
        foreach ($classificacoes as $classificacao) {
            $datasets[] = [
                'label'  => $classificacao->classificacao,
                'pointRadius' => true,
                'data' => [
                    (ROUND(($classificacao->{$meses[0]} / ($totaisColunas[$meses[0]] == 0 ? 1 : $totaisColunas[$meses[0]])) * 100, 2)),
                    (ROUND(($classificacao->{$meses[1]} / ($totaisColunas[$meses[1]] == 0 ? 1 : $totaisColunas[$meses[1]])) * 100, 2)),
                    (ROUND(($classificacao->{$meses[2]} / ($totaisColunas[$meses[2]] == 0 ? 1 : $totaisColunas[$meses[2]])) * 100, 2)),
                    (ROUND(($classificacao->{$meses[3]} / ($totaisColunas[$meses[3]] == 0 ? 1 : $totaisColunas[$meses[3]])) * 100, 2)),
                    (ROUND(($classificacao->{$meses[4]} / ($totaisColunas[$meses[4]] == 0 ? 1 : $totaisColunas[$meses[4]])) * 100, 2)),
                    (ROUND(($classificacao->{$meses[5]} / ($totaisColunas[$meses[5]] == 0 ? 1 : $totaisColunas[$meses[5]])) * 100, 2)),
                    (ROUND(($classificacao->{$meses[6]} / ($totaisColunas[$meses[6]] == 0 ? 1 : $totaisColunas[$meses[6]])) * 100, 2)),
                    (ROUND(($classificacao->{$meses[7]} / ($totaisColunas[$meses[7]] == 0 ? 1 : $totaisColunas[$meses[7]])) * 100, 2)),
                    (ROUND(($classificacao->{$meses[8]} / ($totaisColunas[$meses[8]] == 0 ? 1 : $totaisColunas[$meses[8]])) * 100, 2)),
                    (ROUND(($classificacao->{$meses[9]} / ($totaisColunas[$meses[9]] == 0 ? 1 : $totaisColunas[$meses[9]]) ) * 100, 2)),
                    (ROUND(($classificacao->{$meses[10]} / ($totaisColunas[$meses[10]] == 0 ? 1 : $totaisColunas[$meses[10]])) * 100, 2)),
                    (ROUND(($classificacao->{$meses[11]} / ($totaisColunas[$meses[11]] == 0 ? 1 : $totaisColunas[$meses[11]])) * 100, 2))
                ]
            ];
        }
        
        $areaChartData['datasets'] = $datasets;
        return json_encode($areaChartData);
    }

    public function getDashboardCompradorClassificacao($endDate, $produto)
    {
        $startDate = date('Y-m-d', strtotime('-12 months', strtotime($endDate)));
        $labels = gerarPeriodoAnoMesIndexJSON($endDate, 'Ym');
        $meses = json_decode($labels, true);
        $lablesFields = json_decode(gerarPeriodoAnoMesJSON($endDate), true);

        $builder = $this->select("concat(usr.nome_completo, '-', cps.nome) classificacao,
                        SUM(cpr.quantidade) total,
                        SUM(CASE WHEN DATE_FORMAT(cpr.data_compra, '%Y%m') = $meses[0] THEN cpr.quantidade ELSE 0 END) AS '$meses[0]',
                        SUM(CASE WHEN DATE_FORMAT(cpr.data_compra, '%Y%m') = $meses[1] THEN cpr.quantidade ELSE 0 END) AS '$meses[1]',
                        SUM(CASE WHEN DATE_FORMAT(cpr.data_compra, '%Y%m') = $meses[2] THEN cpr.quantidade ELSE 0 END) AS '$meses[2]',
                        SUM(CASE WHEN DATE_FORMAT(cpr.data_compra, '%Y%m') = $meses[3] THEN cpr.quantidade ELSE 0 END) AS '$meses[3]',
                        SUM(CASE WHEN DATE_FORMAT(cpr.data_compra, '%Y%m') = $meses[4] THEN cpr.quantidade ELSE 0 END) AS '$meses[4]',
                        SUM(CASE WHEN DATE_FORMAT(cpr.data_compra, '%Y%m') = $meses[5] THEN cpr.quantidade ELSE 0 END) AS '$meses[5]',
                        SUM(CASE WHEN DATE_FORMAT(cpr.data_compra, '%Y%m') = $meses[6] THEN cpr.quantidade ELSE 0 END) AS '$meses[6]',
                        SUM(CASE WHEN DATE_FORMAT(cpr.data_compra, '%Y%m') = $meses[7] THEN cpr.quantidade ELSE 0 END) AS '$meses[7]',
                        SUM(CASE WHEN DATE_FORMAT(cpr.data_compra, '%Y%m') = $meses[8] THEN cpr.quantidade ELSE 0 END) AS '$meses[8]',
                        SUM(CASE WHEN DATE_FORMAT(cpr.data_compra, '%Y%m') = $meses[9] THEN cpr.quantidade ELSE 0 END) AS '$meses[9]',
                        SUM(CASE WHEN DATE_FORMAT(cpr.data_compra, '%Y%m') = $meses[10] THEN cpr.quantidade ELSE 0 END) AS '$meses[10]',
                        SUM(CASE WHEN DATE_FORMAT(cpr.data_compra, '%Y%m') = $meses[11] THEN cpr.quantidade ELSE 0 END) AS '$meses[11]'")
            ->join('cadastro_pessoa pes', 'cpr.fornecedor = pes.codigo', 'inner')
            ->join('classificacao_pessoa cps', "pes.classificacao_1   = cps.codigo 
                                                AND cps.movimenta_estoque = 'S'", 'inner')
            ->join('usuarios usr', 'pes.comprador = usr.username', 'inner')
            ->where('cpr.estado_registro', 'ATIVO')
            ->where('cpr.movimentacao', 'COMPRA')
            ->where('cpr.cod_produto', $produto)
            ->where('cpr.data_compra >=', $startDate)
            ->where('cpr.data_compra <=', $endDate)
            ->groupBy('usr.nome_completo, cps.nome')
            ->orderBy('total', 'DESC')
            ->limit(10);

        $classificacoes = $builder->get()->getResult();
        $totaisColunas = [
            $meses[0] => 0,
            $meses[1] => 0,
            $meses[2] => 0,
            $meses[3] => 0,
            $meses[4] => 0,
            $meses[5] => 0,
            $meses[6] => 0,
            $meses[7] => 0,
            $meses[8] => 0,
            $meses[9] => 0,
            $meses[10] => 0,
            $meses[11] => 0,
        ];

        foreach ($classificacoes as $classificacao) {
            foreach ($meses as $mes) {
                $totaisColunas[$mes] += $classificacao->{$mes} ?? 0;
            }
        }

        $areaChartData = [];
        $areaChartData['labels'] = $lablesFields;

        $datasets = [];
        foreach ($classificacoes as $classificacao) {
            $datasets[] = [
                'label' => $classificacao->classificacao,
                'pointRadius' => true,
                'data' => [
                    (ROUND(($classificacao->{$meses[0]} / ($totaisColunas[$meses[0]] == 0 ? 1 : $totaisColunas[$meses[0]])) * 100, 2)),
                    (ROUND(($classificacao->{$meses[1]} / ($totaisColunas[$meses[1]] == 0 ? 1 : $totaisColunas[$meses[1]])) * 100, 2)),
                    (ROUND(($classificacao->{$meses[2]} / ($totaisColunas[$meses[2]] == 0 ? 1 : $totaisColunas[$meses[2]])) * 100, 2)),
                    (ROUND(($classificacao->{$meses[3]} / ($totaisColunas[$meses[3]] == 0 ? 1 : $totaisColunas[$meses[3]])) * 100, 2)),
                    (ROUND(($classificacao->{$meses[4]} / ($totaisColunas[$meses[4]] == 0 ? 1 : $totaisColunas[$meses[4]])) * 100, 2)),
                    (ROUND(($classificacao->{$meses[5]} / ($totaisColunas[$meses[5]] == 0 ? 1 : $totaisColunas[$meses[5]])) * 100, 2)),
                    (ROUND(($classificacao->{$meses[6]} / ($totaisColunas[$meses[6]] == 0 ? 1 : $totaisColunas[$meses[6]])) * 100, 2)),
                    (ROUND(($classificacao->{$meses[7]} / ($totaisColunas[$meses[7]] == 0 ? 1 : $totaisColunas[$meses[7]])) * 100, 2)),
                    (ROUND(($classificacao->{$meses[8]} / ($totaisColunas[$meses[8]] == 0 ? 1 : $totaisColunas[$meses[8]])) * 100, 2)),
                    (ROUND(($classificacao->{$meses[9]} / ($totaisColunas[$meses[9]] == 0 ? 1 : $totaisColunas[$meses[9]]) ) * 100, 2)),
                    (ROUND(($classificacao->{$meses[10]} / ($totaisColunas[$meses[10]] == 0 ? 1 : $totaisColunas[$meses[10]])) * 100, 2)),
                    (ROUND(($classificacao->{$meses[11]} / ($totaisColunas[$meses[11]] == 0 ? 1 : $totaisColunas[$meses[11]])) * 100, 2))
                ]
            ];
        }
        
        $areaChartData['datasets'] = $datasets;
        return json_encode($areaChartData);
    }



}

