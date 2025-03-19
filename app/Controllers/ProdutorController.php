<?php

namespace App\Controllers;

use App\Models\ProdutorModel;

class ProdutorController extends BaseController
{
    protected $produtorModel;
    private const TITULO = 'Produtores';


    public function __construct()
    {
        $this->produtorModel = new ProdutorModel();
    }

    public function index()
    {
        $data = [
            'title' => SELF::TITULO,
            'page' => 'Lista de Produtores',
            'server_success' => session()->getFlashdata('server_success'),
            'server_warning' => session()->getFlashdata('server_warning'),
            'datatables'     => true,
            'produtores'     => $this->produtorModel->getWithDetails()
        ];

        return view('cadastro/produtor/index', $data);
    }

    public function getSituacaoProdutor()
    {
        $produto   = $this->request->getPost('produto');
        $produtor  = $this->request->getPost('produtor');
        $ativo     = $this->request->getPost('ativo');
        
        $situacaoProdutor = $this->produtorModel->getSituacaoProdutor($produto, $produtor, $ativo);

        return $this->response->setJSON(['data' => $situacaoProdutor]);
    }


    public function listaSituacaoProdutor()
    {
        $produto  = $this->request->getGet('produto');
        $produtor = $this->request->getGet('produtor');
        $ativo    = $this->request->getGet('ativo') ?? 0;

        $data = [
            'title'          => SELF::TITULO,
            'page'           => 'Lista de Produtores Ativos/Inativos',
            'server_success' => session()->getFlashdata('server_success'),
            'server_warning' => session()->getFlashdata('server_warning'),
            'datatables'     => true,
            'produto'        => $produto,
            'nomeProduto'    => '',
            'produtor'       => $produtor,
            'nomeProdutor'   => '',
            'ativo'          => $ativo, 
            'produtores'     => $this->produtorModel->getSituacaoProdutor($produto, $produtor, $ativo)
        ];
        
        return view('cadastro/produtor/lista_situacao_produtor', $data);
    }

    public function listaAtivos()
    {
        $startDate = $this->request->getGet('startDate');
        $endDate   = $this->request->getGet('endDate');

        // Validar o período
        list($startDate, $endDate) = $this->getAtivosValida($startDate, $endDate);

        $data = [
            'title'          => SELF::TITULO,
            'page'           => 'Lista de Produtores Ativos',
            'server_success' => session()->getFlashdata('server_success'),
            'server_warning' => session()->getFlashdata('server_warning'),
            'datatables'     => true,
            'startDate'      => $startDate,
            'endDate'        => $endDate,
            'produtores'     => $this->produtorModel->getActiveProducers($startDate, $endDate)
        ];

        return view('cadastro/produtor/lista_ativos', $data);
    }

    public function getAtivos()
    {
        $startDate = $this->request->getPost('startDate');
        $endDate   = $this->request->getPost('endDate');

        // Validar o período
        list($startDate, $endDate) = $this->getAtivosValida($startDate, $endDate);

        $produtores = $this->produtorModel->getActiveProducers($startDate, $endDate);
        return $this->response->setJSON(['data' => $produtores]);
    }

    private function getAtivosValida($startDate, $endDate)
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

        return [$startDate, $endDate];
    }

    // ------------------------------------
    // Edita Produtor
    // ------------------------------------
    public function edita($id)
    {
        $data = [
            'title' => SELF::TITULO,
            'page' => 'Altera Produtor'
        ];

        if (empty($id)) {
            return redirect()->to('cadastro/produtor');
        }

        // Validação
        $data['validation_errors'] = session()->getFlashdata('validation_errors');

        $data['produtor'] = $this->produtorModel->getWithDetails($id);
   
        return view('cadastro/produtor/edita_produtor', $data);
    }

    public function atualiza()
    {
        $data = $this->request->getPost();

        if (empty($data['codigo'])) {
            return redirect()->to('cadastro/produtor');
        }

        // Validação da tela de edição
        $validation = $this->validate($this->produtorModel->getValidations(OR_UPDATE));

        if (!$validation) {
            return redirect()->back()->withInput()->with('validation_errors', $this->validator->getErrors());
        }
        
        // Prepara dados para fazer a alteração
        $data['usuario_alteracao'] = session()->user['username'];
        $data['cadastro_validado'] = isset($data['cadastro_validado']) ? 'S' : 'N';
        $data['validado_serasa']   = isset($data['validado_serasa']) ? 'S' : 'N';
        $data['embargado']         = isset($data['embargado']) ? 'S' : 'N';
        $data['data_alteracao']    = date('Y/m/d');
        $data['hora_alteracao']    = date('H:i:s');

        // Altera registro no bando de dados
        $this->produtorModel->update($data['codigo'], $data);

        // redireciona tela
        return redirect()->to('/cadastro/produtor')->withInput()->with('server_success', 'Atualização efetuada com sucesso.');
    }    

    /**
     * Pesquisa um produtor pelo código.
     *
     * @param string $codigo
     */
    public function findById($codigo)
    {
        $produtor = $this->produtorModel->findById($codigo);

        if ($produtor) {
            $data['produtor'] = $produtor;
            return view('produtor/view', $data);
        } else {
            return redirect()->to('/produtor')->with('error', 'Produtor não encontrado.');
        }
    }

    public function findLikeName() 
    {
        $term = htmlspecialchars($this->request->getGet('term'), ENT_QUOTES, 'UTF-8');

        // Buscar produtores que correspondam ao termo
        $produtores = $this->produtorModel
                            ->select("codigo, concat(codigo,' - ', nome) as nome")
                            ->orlike('nome', $term)
                            ->orlike('codigo', $term)
                            ->findAll();

        // Estruturar a resposta para evitar vazamento de campos desnecessários
        $response = array_map(function ($produtor) {
            return [
                'codigo' => $produtor->codigo,
                'nome'   => $produtor->nome,
            ];
        }, $produtores);

        return $this->response->setJSON($response);     
    }
}
