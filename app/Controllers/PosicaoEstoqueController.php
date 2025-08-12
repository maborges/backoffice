<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FilialModel;
use App\Models\ComprasModel;
use App\Models\ProdutoModel;
use App\Models\SaldoArmazenadoGerencialModel;
use App\Models\ContratoPosicaoEstoqueModel;
use App\Services\Sankhya;

class PosicaoEstoqueController extends BaseController
{

    protected $filialModel;
    protected $comprasModel;
    protected $produtosModel;
    protected $contratoPosicaoEstoqueModel;
    protected $saldoArmazenadoGerencialModel;
    private const TITULO = 'Posição de Estoque';

    public function __construct()
    {
        $this->filialModel = new FilialModel();
        $this->comprasModel = new ComprasModel();
        $this->produtosModel = new ProdutoModel();
        $this->contratoPosicaoEstoqueModel = new ContratoPosicaoEstoqueModel();
        $this->saldoArmazenadoGerencialModel = new SaldoArmazenadoGerencialModel();
    }

    public function contratoPosicaoEstoque()
    {
        $data = [
            'title' => SELF::TITULO,
            'page' => 'Contrato de Posição de Estoque',
            'server_success' => session()->getFlashdata('server_success'),
            'server_warning' => session()->getFlashdata('server_warning'),
            'datatables'     => true,
            'contratos'      => []
        ];

        return view('posicao_estoque/contrato/index', $data);        
    }


    public function index()
    {
        //
    }

    public function saldoGerencial()
    {
        $data = [
            'title' => SELF::TITULO,
            'page' => 'Saldo Gerencial',
            'server_success' => session()->getFlashdata('server_success'),
            'server_warning' => session()->getFlashdata('server_warning'),
            'datatables'     => true,
            'baseDate'       => date('Y-m-d'), 
            'produto'        => '-1',
            'nomeProduto'    => '',    
            'filial'         => '',
            'data'           => [],
        ];

        return view('posicao_estoque/saldo_gerencial/index', $data);
    }

    public function saldoSuif()
    {
        $data = [
            'title' => SELF::TITULO,
            'page' => 'Saldo SUIF',
            'server_success' => session()->getFlashdata('server_success'),
            'server_warning' => session()->getFlashdata('server_warning'),
            'datatables'     => true,
            'baseDate'       => date('Y-m-d'),
            'produto'        => '-1',
            'nomeProduto'    => '',
            'filial'         => '',
            'data'           => [],
        ];

        return view('posicao_estoque/saldo_suif/index', $data);
    }

    public function saldoFiscal()
    {
        $data = [
            'title' => SELF::TITULO,
            'page' => 'Saldo Fiscal',
            'server_success' => session()->getFlashdata('server_success'),
            'server_warning' => session()->getFlashdata('server_warning'),
            'datatables'     => true,
            'baseDate'       => date('Y-m-d'),
            'produto'        => '-1',
            'nomeProduto'    => '',
            'filial'         => '',
            'data'           => [],
        ];

        return view('posicao_estoque/saldo_fiscal/index', $data);
    }

    public function saldo4c()
    {
        $data = [
            'title' => SELF::TITULO,
            'page' => 'Saldo 4C',
            'server_success' => session()->getFlashdata('server_success'),
            'server_warning' => session()->getFlashdata('server_warning'),
            'datatables'     => true,
            'baseDate'       => date('Y-m-d'),
            'produto'        => '-1',
            'nomeProduto'    => '',
            'filial'         => '',
            'data'           => [],
        ];

        return view('posicao_estoque/saldo_4c/index', $data);
    }

    public function getSaldoGerencial() 
    {
        $endDate   = $this->request->getPost('baseDate') ?? date('Y-m-d');
        $startDate = date('Y-m-d', strtotime('-1 month', strtotime($endDate)));
        $produto   = $this->request->getPost('produto') ?? 2;
        $filial    = $this->request->getPost('filial');

        // Verifica se o produto foi fornecido
        if (empty($produto) || $produto == '-1') {
            return json_encode(["data" =>[]]);
        }

        $periodo = self::geraPeriodo($endDate);

        // Verifica se o período foi gerado corretamente
        if (empty($periodo)) {
            return json_encode([
                "error" => "Erro ao gerar período de datas.",
                "data" => []
            ]);
        }

        $saldoArmazenadoGerencial = $this->saldoArmazenadoGerencialModel->buscaSaldoAte($produto, $filial, $startDate);

        // Calculate total balance by summing all balance elements
        $saldoTotal = 0;
        $dataInicialSaldo = '2000-01-01';
        $dataFinalSaldo   =  date('Y-m-d', strtotime($startDate . ' -1 day'));
        
        if (!empty($saldoArmazenadoGerencial)) {
            foreach ($saldoArmazenadoGerencial as $saldo) {
                $saldoTotal += $saldo->saldo;
                // Data do último saldo + 1 dia
                $dataInicialSaldo = date('Y-m-d', strtotime($saldo->data_saldo . ' +1 day'));
            }
        }

        // Verifica se o período foi gerado corretamente
        if (empty($saldoTotal)) {
            return json_encode([
                "error" => "Nenhuma informação de saldo armazenado gerencial encontrada para o produto/filial.",
                "data" => []
            ]);
        }        

        $saldo = $this->comprasModel->getSaldoGerencialAnterior(
                                        startDate: $dataInicialSaldo, 
                                        endDate: $dataFinalSaldo,
                                        produto: $produto, 
                                        filial: $filial);

        $periodo[0]['saldo_anterior'] = $saldo + $saldoTotal;

        $compras = $this->comprasModel->getQuantidadeGerencialCompras(
                                        $produto,
                                        $startDate,
                                        $endDate,
                                        $filial);

        // Obtém informações do produto, código Sankhya e quantidade da unidade comercializada
        $produtoData = $this->produtosModel->where('codigo', $produto)->first();
        
        // Verifica se o produto foi encontrado
        if (!$produtoData) {
            $idProdutoSankhya = -1;
            $quantidadeUn = 1;
        } else {
            $idProdutoSankhya = $produtoData->id_sankhya;
            $quantidadeUn = $produtoData->quantidade_un;    
        }
        
        // Obtém as vendas do Sankhya
        $vendasSankhya = $this->getVendasSankhya($startDate, $endDate, $idProdutoSankhya, $filial);

        // Recalcular quantidade_venda se $quantidadeUn for maior que 1
        if ($quantidadeUn > 1) {
            foreach ($vendasSankhya as &$venda) {
                $venda[1] /= $quantidadeUn;
            }
        }

        // Obtém valores gerenciados de contratos
        $contratos = $this->contratoPosicaoEstoqueModel->getContratosGerenciados($startDate, $endDate, $produto, $filial);

        // Para cada item do período, buscar dados de compras e vendas correspondentes
        $offsetCompras = 0;
        $offsetVendas = 0;
        $offsetContratos = 0;
        // Inicializar valores padrão para compras
        foreach ($periodo as $key => $value) {
            $periodo[$key]['compras_suif'] = 0.0;
            $periodo[$key]['preco_medio'] = 0.0;
            $periodo[$key]['preco_medio_gerencial'] = 0.0;
            $periodo[$key]['vendas_sankhya'] = 0.0;

            // Converter a data do período para o formato Y-m-d para comparação
            $dataPeriodo = date('Y-m-d', strtotime($value['data_movimento']));

            // Buscar compras para esta data específica
            for ($i = $offsetCompras; $i < count($compras); $i++) {
                $compra = $compras[$i];
                if ($compra->data_compra == $dataPeriodo) {
                    $periodo[$key]['compras_suif'] = $compra->compra;
                    $periodo[$key]['preco_medio'] = $compra->media_ponderada;
                    $periodo[$key]['preco_medio_gerencial'] = $compra->media_gerencial;
                    $offsetCompras = $i + 1; // Atualiza o offset para a próxima iteração
                    break; // Encontrou a data, pode sair do loop
                } elseif ($compra->data_compra > $dataPeriodo) {
                    // Se a data da compra é maior, não precisamos continuar procurando
                    break;
                }
            }

            // Buscar vendas do Sankhya para esta data específica
            for ($j = $offsetVendas; $j < count($vendasSankhya); $j++) {
                $venda = $vendasSankhya[$j];
                // Converter a data da venda para o formato Y-m-d para comparação
                $dataVenda = date('Y-m-d', strtotime($venda[0]));

                if ($dataVenda == $dataPeriodo) {
                    $periodo[$key]['vendas_sankhya'] = $venda[1];
                    $offsetVendas = $j + 1; // Atualiza o offset para a próxima iteração
                    break; // Encontrou a data, pode sair do loop
                } elseif ($dataVenda > $dataPeriodo) {
                    // Se a data da venda é maior, não precisamos continuar procurando
                    break;
                }
            }

            // Buscar contratos para esta data específica
            for ($k = $offsetContratos; $k < count($contratos); $k++) {
                $contrato = $contratos[$k];
                if ($contrato->data_referencia == $dataPeriodo) {
                    $periodo[$key]['quantidade_gerenciada'] = $contrato->quantidade;
                    $offsetContratos = $k + 1; // Atualiza o offset para a próxima iteração
                    break; // Encontrou a data, pode sair do loop
                } elseif ($contrato->data_referencia > $dataPeriodo) {
                    // Se a data do contrato é maior, não precisamos continuar procurando
                    break;
                }
            }
            // Calcular saldo anterior a partir da segunda ocorrência
            if ($key > 0) {
                $linhaAnterior = $periodo[$key - 1];
                $periodo[$key]['saldo_anterior'] = 
                    $linhaAnterior['saldo_anterior'] + 
                    ($linhaAnterior['compras_suif'] ?? 0) - 
                    ($linhaAnterior['vendas_sankhya'] ?? 0) - 
                    ($linhaAnterior['quantidade_gerenciada'] ?? 0);
            }
        }

        // Preenche o saldo anterior do primeiro dia do período
        return json_encode(["data" => $periodo]);
    }

    private function getVendasSankhya($startDate, $endDate, $produtoSankhya, $filial)
    {
        log_message('info', 'getVendasSankhya: ' . $startDate . ' ' . $endDate . ' ' . $produtoSankhya . ' ' . $filial);

        // Formata as datas para o formato esperado pelo Sankhya (YYYYMMDD)
        $startDateFormatted = date('Y-m-d', strtotime($startDate));
        $endDateFormatted = date('Y-m-d', strtotime($endDate));

        $sql = "SELECT TO_CHAR(DTENTSAI, 'DD-MM-YYYY') DATA_MOVIMENTO, 
                       SUM(QTDNEG) QUANTIDADE_VENDA
                  FROM GRAN_VIEW_MOV_ENT_SAI_GRAOS
                 WHERE CODPROD = $produtoSankhya
                   AND TIPMOV = 'V'
                   AND DTENTSAI >= TO_DATE('$startDateFormatted','YYYY-MM-DD') 
                   AND DTENTSAI <= TO_DATE('$endDateFormatted','YYYY-MM-DD')";

        // Adiciona filtro de filial se fornecido
        if (!empty($filial)) {
            // Busca o id_sankhya da filial
            $filialData = $this->filialModel->where('codigo', $filial)->first();
            
            // Verifica se a filial foi encontrada
            if (!$filialData) {
                return [];
            }
            
            $filialSankhya = $filialData->id_sankhya;
            $sql .= " AND CODEMP = $filialSankhya";
        }

        $sql .= " GROUP BY DTENTSAI ORDER BY DTENTSAI";

        log_message('info', 'getVendasSankhya: ' . $sql);
        // Executa a query através da API do Sankhya
        $result = Sankhya::queryExecuteAPI($sql);

        // Verifica se houve erro na execução
        if ($result['errorCode'] !== 0) {
            // Log do erro ou tratamento adequado
            return [];
        }

        return $result['rows'] ?? [];
    }

    public function getSaldoSuif()
    {
        $baseDate = $this->request->getPost('baseDate');
        $produto  = $this->request->getPost('produto');
        $filial   = $this->request->getPost('filial');

        return json_encode(["data" => $this->getSaldoSuifFake($baseDate, $produto, $filial)]);
    }

    public function getSaldoFiscal()
    {
        $baseDate = $this->request->getPost('baseDate');
        $produto  = $this->request->getPost('produto');
        $filial   = $this->request->getPost('filial');

        return json_encode(["data" => $this->getSaldoFiscalFake($baseDate, $produto, $filial)]);
    }

    public function getSaldo4c()
    {
        $baseDate = $this->request->getPost('baseDate');
        $produto  = $this->request->getPost('produto');
        $filial   = $this->request->getPost('filial');

        return json_encode(["data" => $this->getSaldo4cFake($baseDate, $produto, $filial)]);
    }

    
    private function getSaldoSuifFake($baseDate, $produto, $filial): array
    {
        if ($produto == '-1') {
            return [];
        }
        return [
            [
                "data_movimento" => "2023-01-01T00:00:00Z",
                "saldo_acumulado" => 1500.500,
                "estoque_total_suif" => 300.500,
                "armazenado_suif" => 200.750,
                "saldo_puxar" => 200.750,
                "venda_acumulada_sankhya" => 50,
                "quantidade_gerenciada" => 1200.300
            ],
            [
                "data_movimento" => "2023-01-02T00:00:00Z",
                "saldo_acumulado" => 1450.200,
                "estoque_total_suif" => 300.500,
                "armazenado_suif" => 180.500,
                "saldo_puxar" => 200.750,
                "venda_acumulada_sankhya" => 45,
                "quantidade_gerenciada" => 1150.750
            ],
            [
                "data_movimento" => "2023-01-03T00:00:00Z",
                "saldo_acumulado" => 1400.000,
                "estoque_total_suif" => 300.500,
                "armazenado_suif" => 150.250,
                "saldo_puxar" => 200.750,
                "venda_acumulada_sankhya" => 40,
                "quantidade_gerenciada" => 1100.200
            ],
            [
                "data_movimento" => "2023-01-04T00:00:00Z",
                "saldo_acumulado" => 1350.750,
                "estoque_total_suif" => 300.500,
                "armazenado_suif" => 120.000,
                "saldo_puxar" => 200.750,
                "venda_acumulada_sankhya" => 35,
                "quantidade_gerenciada" => 1050.500
            ],
            [
                "data_movimento" => "2023-01-05T00:00:00Z",
                "saldo_acumulado" => 1300.300,
                "estoque_total_suif" => 300.500,
                "armazenado_suif" => 100.500,
                "saldo_puxar" => 200.750,
                "venda_acumulada_sankhya" => 30,
                "quantidade_gerenciada" => 1000.000
            ]
        ];
    }

    private function getSaldoFiscalFake($baseDate, $produto, $filial): array
    {
        if ($produto == '-1') {
            return [];
        }
        return [
            [
                "data_movimento" => "2023-01-01T00:00:00Z",
                "saldo_acumulado" => 1500.500,
                "estoque_fiscal_sankhya" => 300.500,
                "armazenado_sankhya" => 200.750,
                "saldo_puxar" => 200.750,
                "venda_acumulada_sankhya" => 50
            ],
            [
                "data_movimento" => "2023-01-02T00:00:00Z",
                "saldo_acumulado" => 1450.200,
                "estoque_fiscal_sankhya" => 300.500,
                "armazenado_sankhya" => 180.500,
                "saldo_puxar" => 200.750,
                "venda_acumulada_sankhya" => 45
            ],
            [
                "data_movimento" => "2023-01-03T00:00:00Z",
                "saldo_acumulado" => 1400.000,
                "estoque_fiscal_sankhya" => 300.500,
                "armazenado_sankhya" => 150.250,
                "saldo_puxar" => 200.750,
                "venda_acumulada_sankhya" => 40
            ],
            [
                "data_movimento" => "2023-01-04T00:00:00Z",
                "saldo_acumulado" => 1350.750,
                "estoque_fiscal_sankhya" => 300.500,
                "armazenado_sankhya" => 120.000,
                "saldo_puxar" => 200.750,
                "venda_acumulada_sankhya" => 35
            ],
            [
                "data_movimento" => "2023-01-05T00:00:00Z",
                "saldo_acumulado" => 1300.300,
                "estoque_fiscal_sankhya" => 300.500,
                "armazenado_sankhya" => 100.500,
                "saldo_puxar" => 200.750,
                "venda_acumulada_sankhya" => 30
            ]
        ];
    }

    private function getSaldo4cFake($baseDate, $produto, $filial): array
    {
        if ($produto == '-1') {
            return [];
        }
        return [
            [
                "data_movimento" => "2023-01-01T00:00:00Z",
                "saldo_acumulado" => 1500.500,
                "estoque_fiscal_sankhya" => 300.500,
                "venda_acumulada_sankhya" => 50
            ],
            [
                "data_movimento" => "2023-01-02T00:00:00Z",
                "saldo_acumulado" => 1450.200,
                "estoque_fiscal_sankhya" => 300.500,
                "venda_acumulada_sankhya" => 45
            ],
            [
                "data_movimento" => "2023-01-03T00:00:00Z",
                "saldo_acumulado" => 1400.000,
                "estoque_fiscal_sankhya" => 300.500,
                "venda_acumulada_sankhya" => 40
            ],
            [
                "data_movimento" => "2023-01-04T00:00:00Z",
                "saldo_acumulado" => 1350.750,
                "estoque_fiscal_sankhya" => 300.500,
                "venda_acumulada_sankhya" => 35
            ],
            [
                "data_movimento" => "2023-01-05T00:00:00Z",
                "saldo_acumulado" => 1300.300,
                "estoque_fiscal_sankhya" => 300.500,
                "venda_acumulada_sankhya" => 30
            ]
        ];
    }

    public function geraPeriodo($endDate = null): array
    {
        // Se a data não foi passada, usa a data corrente
        if (empty($endDate)) {
            $endDate = date('Y-m-d');
        // Verifica se a data base é válida
        } elseif (!strtotime($endDate)) {
            // Se a data foi passada mas é inválida, retorna array vazio
            return [];
        }

        $periodo = [];

        // Mesmo dia da baseDate, mas do mês anterior (data inicial)
        $startDate = date('Y-m-d', strtotime('-1 month', strtotime($endDate)));
        $startDate = date('Y-m-d', strtotime('1 day', strtotime($startDate)));

        // Garante que $startDate <= $endDate
        $current = $startDate;

        while (strtotime($current) <= strtotime($endDate)) {
            $identificador = date('Y-m-d', strtotime($current));
            $periodo[] = [
                'data_movimento' => $current,
                'saldo_anterior' => 0.0,
                'compras_suif' => 0.0,
                'preco_medio' => 0.0,
                'preco_medio_gerencial' => 0.0,
                'vendas_sankhya' => 0.0,
                'quantidade_gerenciada' => 0.0,
            ];
            $current = date('Y-m-d', strtotime('+1 day', strtotime($current)));
        }

        return $periodo;
    }
}
