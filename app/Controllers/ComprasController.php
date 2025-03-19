<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ComprasModel;

class ComprasController extends BaseController
{
    protected $comprasModel;
    private const TITULO = 'Compras';


    public function __construct()
    {
        $this->comprasModel = new ComprasModel();
    }

    public function index()
    {
        //
    }

    public function listaEntregasPendentes()
    {
        // Obtém os parâmetros nomeados da query string
        $produto = '';
        $produtor = '';
        $nomeProduto = '';
        $nomeProdutor = '';


        $data = [
            'title'             => SELF::TITULO,
            'page'              => 'Lista de Entregas Pendentes',
            'server_success'    => session()->getFlashdata('server_success'),
            'server_warning'    => session()->getFlashdata('server_warning'),
            'datatables'        => true,
            'produto'           => $produto,
            'nomeProduto'       => $nomeProduto,
            'produtor'          => $produtor,
            'nomeProdutor'      => $nomeProdutor
        ];

        return view('gerencial/compras/lista_entrega_pendente', $data);
    }


    public function getEntregaPendente()
    {
        // Obtém os parâmetros nomeados da query string
        $produto  = $this->request->getPost('produto') ?? null;
        $produtor = $this->request->getPost('produtor') ?? null;

        $entregasPendentes = $this->comprasModel->getEntregaPendente($produto, $produtor);

        return $this->response->setJSON(['data' => $entregasPendentes]);
    }

    public function listaGapCompraEntrega() 
    {
        // Se as datas não forem informadas, definir o período padrão
        $endDateObj = new \DateTime('last day of previous month');
        $startDateObj = (clone $endDateObj)->modify('-24 months');

        $startDate = $startDateObj->format('Y-m-d');
        $endDate = $endDateObj->format('Y-m-d');

        $data = [
            'title'             => SELF::TITULO,
            'page'              => 'Lista Gap Compra X Entrega',
            'server_success'    => session()->getFlashdata('server_success'),
            'server_warning'    => session()->getFlashdata('server_warning'),
            'datatables'        => true,
            'startDate'         => $startDate,
            'endDate'           => $endDate,
            'produto'           => '',
            'nomeProduto'       => '',
            'produtor'          => '',
            'nomeProdutor'      => ''
        ];

        return view('gerencial/compras/lista_gap_compra_entrega', $data);

    }

    public function getGapCompraEntrega()
    {
        $startDate = $this->request->getPost('startDate') ?? '2024-08-01';
        $endDate   = $this->request->getPost('endDate') ?? '2024-11-30';
        $produto   = $this->request->getPost('produto') ?? 2;
        $produtor  = $this->request->getPost('produtor') ?? 10961;
        $filial    = '';

        // Validar o período
        list($startDate,$endDate, $produto, $produtor, $filial) = $this->getGapCompraEntregaValida($startDate, $endDate, $produto, $produtor, $filial);

        $gapEntrega = $this->comprasModel->getGapCompraEntrega($startDate, $endDate, $produto, $produtor);

        return $this->response->setJSON(['data' => $gapEntrega]);
    }

    private function getGapCompraEntregaValida($startDate, $endDate, $produto, $produtor, $filial = null)
    {
        if ($startDate && $endDate) {
            $startDateObj = new \DateTime($startDate);
            $endDateObj = new \DateTime($endDate);

            if ($startDateObj > $endDateObj) {
                $startDateObj = $endDateObj;
                throw new \InvalidArgumentException('Data inicial não pode ser maior que a data final.');
            }

        } else {
            // Se as datas não forem informadas, definir o período padrão
            $endDateObj = new \DateTime('last day of previous month');
            $startDateObj = (clone $endDateObj)->modify('-24 months');

            $startDate = $startDateObj->format('Y-m-d');
            $endDate = $endDateObj->format('Y-m-d');
        }

        return [$startDate, $endDate, $produto, $produtor, $filial];
    }

    public function getPrecoGerencial()
    {
        $startDate = $this->request->getPost('startDate') ?? '2024-08-01';
        $endDate   = $this->request->getPost('endDate') ?? '2024-11-30';
        $produto   = $this->request->getPost('produto');
        $produtor  = $this->request->getPost('produtor');
        $filial    = $this->request->getPost('filial');

        // Validar o período
        list($startDate, $endDate, $produto, $produtor, $filial) = $this->getGapCompraEntregaValida($startDate, $endDate, $produto, $produtor, $filial);

        $precoGerencial = $this->comprasModel->getPrecoGerencial($startDate, $endDate, $produto, $produtor, $filial);
 
        return $this->response->setJSON(['data' => $precoGerencial]);
    }

    public function listaPrecoGerencial()
    {
        // Se as datas não forem informadas, definir o período padrão
        $endDateObj = new \DateTime('last day of previous month');
        $startDateObj = (clone $endDateObj)->modify('-1 months');

        $startDate = $startDateObj->format('Y-m-d');
        $endDate = $endDateObj->format('Y-m-d');

        $data = [
            'title'             => SELF::TITULO,
            'page'              => 'Lista de Preço Gerencial',
            'server_success'    => session()->getFlashdata('server_success'),
            'server_warning'    => session()->getFlashdata('server_warning'),
            'datatables'        => true,
            'startDate'         => $startDate,
            'endDate'           => $endDate,
            'produto'           => '',
            'nomeProduto'       => '',
            'produtor'          => '',
            'nomeProdutor'      => '',
            'filial'            => ''
        ];

        return view('gerencial/compras/lista_preco_gerencial', $data);
    }

    public function getPrecoGerencialResumo()
    {
        $startDate = $this->request->getPost('startDate');
        $endDate   = $this->request->getPost('endDate');
        $produto   = $this->request->getPost('produto');
        $produtor  = $this->request->getPost('produtor');
        $filial    = $this->request->getPost('filial');

        // Validar o período
        list($startDate, $endDate, $produto, $produtor, $filial) = $this->getGapCompraEntregaValida($startDate, $endDate, $produto, $produtor, $filial);

        $resumoGerencial = $this->comprasModel->getPrecoGerencialResumo($startDate, $endDate, $produto, $produtor, $filial);

        return $this->response->setJSON([
            'data' => $resumoGerencial
        ]);
    }

    public function getResumoComprador()
    {
        $startDate = $this->request->getPost('startDate');
        $endDate   = $this->request->getPost('endDate');
        $produto   = $this->request->getPost('produto');

        if ($produto == '-2') {
            return $this->response->setJSON([
                'data' => []
            ]);
        }

        // Validar o período
        list($startDate, $endDate, $produto) = $this->validaParametroDashboard($startDate, $endDate, $produto);

        $resumoComprador = $this->comprasModel->getResumoComprador($startDate, $endDate, $produto);

        return $this->response->setJSON([
            'data' => $resumoComprador
        ]);

    }

    public function getResumoFilial()
    {
        $startDate = $this->request->getPost('startDate');
        $endDate   = $this->request->getPost('endDate');
        $produto   = $this->request->getPost('produto');

        if ($produto == '-2') {
            return $this->response->setJSON([
                'data' => []
            ]);
        }

        // Validar o período
        list($startDate, $endDate, $produto) = $this->validaParametroDashboard($startDate, $endDate, $produto);

        $resumoComprador = $this->comprasModel->getResumoFilial($startDate, $endDate, $produto);

        return $this->response->setJSON([
            'data' => $resumoComprador
        ]);
    }

    public function getTop10Cliente()
    {
        $startDate = $this->request->getPost('startDate');
        $endDate   = $this->request->getPost('endDate');
        $produto   = $this->request->getPost('produto');

        if ($produto == '-2') {
            return $this->response->setJSON([
                'data' => []
            ]);
        }

        // Validar o período
        list($startDate, $endDate, $produto) = $this->validaParametroDashboard($startDate, $endDate, $produto);

        $resumo = $this->comprasModel->getTop10Cliente($startDate, $endDate, $produto);

        return $this->response->setJSON([
            'data' => $resumo
        ]);
    }

    public function getTop10Regiao()
    {
        $startDate = $this->request->getPost('startDate');
        $endDate   = $this->request->getPost('endDate');
        $produto   = $this->request->getPost('produto');

        if ($produto == '-2') {
            return $this->response->setJSON([
                'data' => []
            ]);
        }

        // Validar o período
        list($startDate, $endDate, $produto) = $this->validaParametroDashboard($startDate, $endDate, $produto);

        $resumo = $this->comprasModel->getTop10Regiao($startDate, $endDate, $produto);

        return $this->response->setJSON([
            'data' => $resumo
        ]);
    }

    public function getResumoClassificacao() {
        $endDate   = $this->request->getPost('endDate') ?? date('Y-m-d');
        $startDate = $endDate; // Somenta para deixar os parâmetros de validação compativeis
        $produto   = $this->request->getPost('produto') ?? 2;

        list($startDate, $endDate, $produto) = $this->validaParametroDashboard($startDate, $endDate, $produto);

        $result =  $this->comprasModel->getResumoClassificacao($endDate, $produto);
        if (!$result) {
            return $this->response->setJSON([
                'error' => 'Erro ao obter o resumo da classificação.'
            ]);
        }
        return $this->response->setJSON($result);
    }

    public function getDashboardFiliais() {
        $endDate   = $this->request->getPost('endDate');
        $startDate = $endDate; // Somenta para deixar os parâmetros de validação compativeis
        $produto   = $this->request->getPost('produto') ?? -2;
        
        list($startDate, $endDate, $produto) = $this->validaParametroDashboard($startDate, $endDate, $produto);

        $result =  $this->comprasModel->getDashboardFiliais($endDate, $produto);
        if (!$result) {
            return $this->response->setJSON([
                'error' => 'Erro ao obter o resumo da classificação.'
            ]);
        }
        return $this->response->setJSON($result);
    }

    public function getDashboardComprador() {
        $endDate   = $this->request->getPost('endDate') ?? date('Y-m-d');
        $startDate = $endDate; // Somenta para deixar os parâmetros de validação compativeis
        $produto   = $this->request->getPost('produto') ?? -2;

        list($startDate, $endDate, $produto) = $this->validaParametroDashboard($startDate, $endDate, $produto);

        $result =  $this->comprasModel->getDashboardComprador($endDate, $produto);
        if (!$result) {
            return $this->response->setJSON([
                'error' => 'Erro ao obter o resumo da classificação.'
            ]);
        }
        return $this->response->setJSON($result);
    }


    private function validaParametroDashboard($startDate, $endDate, $produto)
    {
        if ($startDate && $endDate) {
            $startDateObj = new \DateTime($startDate);
            $endDateObj = new \DateTime($endDate);

            if ($startDateObj > $endDateObj) {
                $startDateObj = $endDateObj;
                throw new \InvalidArgumentException('Data inicial não pode ser maior que a data final.');
            }
        } else {
            // Se as datas não forem informadas, definir o período padrão
            $endDateObj = new \DateTime('last day of previous month');
            $startDateObj = (clone $endDateObj)->modify('-24 months');

            $startDate = $startDateObj->format('Y-m-d');
            $endDate = $endDateObj->format('Y-m-d');
        }
        return [$startDate, $endDate, $produto];
    }

    public function getDashboardFiliaisClassificacao() {
        $endDate   = $this->request->getPost('endDate') ?? date('Y-m-d');
        $startDate = $endDate; // Somenta para deixar os parâmetros de validação compativeis
        $produto   = $this->request->getPost('produto') ?? -2;

        list($startDate, $endDate, $produto) = $this->validaParametroDashboard($startDate, $endDate, $produto);

        $result =  $this->comprasModel->getDashboardFiliaisClassificacao($endDate, $produto);
        if (!$result) {
            return $this->response->setJSON([
                'error' => 'Erro ao obter o resumo da classificação.'
            ]);
        }
        return $this->response->setJSON($result);
    }

    public function getDashboardCompradorClassificacao() {
        $endDate   = $this->request->getPost('endDate') ?? date('Y-m-d');
        $startDate = $endDate; // Somenta para deixar os parâmetros de validação compativeis
        $produto   = $this->request->getPost('produto') ?? -2;

        list($startDate, $endDate, $produto) = $this->validaParametroDashboard($startDate, $endDate, $produto);

        $result =  $this->comprasModel->getDashboardCompradorClassificacao($endDate, $produto);
        if (!$result) {
            return $this->response->setJSON([
                'error' => 'Erro ao obter o resumo da classificação.'
            ]);
        }
        return $this->response->setJSON($result);
    }

    
}
