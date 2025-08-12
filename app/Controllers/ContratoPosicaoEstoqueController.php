<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ContratoPosicaoEstoqueModel; 

class ContratoPosicaoEstoqueController extends BaseController
{

    protected $contratoPosicaoEstoqueModel;
    private const TITULO = 'Posição de Estoque-Contratos';

    public function __construct()
    {
        $this->contratoPosicaoEstoqueModel = new ContratoPosicaoEstoqueModel();
    }


    public function index()
    {
        $data = [
            'title' => SELF::TITULO,
            'page' => 'Lista de Contratos de Posição de Estoque',
            'server_success' => session()->getFlashdata('server_success'),
            'server_warning' => session()->getFlashdata('server_warning'),
            'datatables'     => true
            // Removido 'data' => $this->contratoPosicaoEstoqueModel->buscarPorProdutoEFilial()
            // Os dados serão carregados via AJAX
        ];
        return view('cadastro/contrato_posicao_estoque/index', $data);
    }

    public function buscarPorProdutoEFilial($produto=null, $filial=null)
    {
        if (!isset($produto)) {
            $produto = $this->request->getPost('produto') ?? null;
        }

        if (!isset($filial)) {
            $filial = $this->request->getPost('filial') ?? null;
        }

        $data = $this->contratoPosicaoEstoqueModel->buscarPorProdutoEFilial($produto, $filial);

        // Formatar a resposta no formato esperado pelo DataTables
        $response = [
            'data' => $data
        ];

        return $this->response->setJSON($response);
    }

    // ------------------------------------
    // Cria Contrato
    // ------------------------------------

    public function cria()
    {
        $data = [
            'title' => SELF::TITULO,
            'page' => 'Inclui Contrato'
        ];

        $data['validation_errors'] = session()->getFlashdata('validation_errors');
        $data['server_error']      = session()->getFlashdata('server_error');
        $data['server_success']    = session()->getFlashdata('server_success');

        return view('cadastro/contrato_posicao_estoque/cria', $data);
    }

    public function grava()
    {
        $data = $this->request->getPost();

        $validation = $this->validate($this->contratoPosicaoEstoqueModel->getValidations(OR_INSERT));

        if (!$validation) {
            return redirect()->back()->withInput()->with('validation_errors', $this->validator->getErrors());
        }

        if ($this->contratoPosicaoEstoqueModel->alreadyExists($data['numero_contrato'])) {
            return redirect()->back()->withInput()->with('server_error', 'Já existe um contrato cadastrado com este número.');
        }

        $this->contratoPosicaoEstoqueModel->insert($data);
        return redirect()->to('/cadastro/contrato_posicao_estoque')->withInput()->with('server_success', 'Inclusão efetuada com sucesso.');
    }

    // ------------------------------------
    // Edita Contrato
    // ------------------------------------
    public function edita($codigo)
    {
        $data = [
            'title' => SELF::TITULO,
            'page' => 'Altera Contrato'
        ];

        if (empty($codigo)) {
            return redirect()->to('cadastro/contrato_posicao_estoque');
        }

        // Validação
        $data['validation_errors'] = session()->getFlashdata('validation_errors');
        $data['contrato'] = $this->contratoPosicaoEstoqueModel->buscarContratoPosicaoEstoqueById($codigo);
        return view('cadastro/contrato_posicao_estoque/edita', $data);
    }

    public function atualiza()
    {
        $data = $this->request->getPost();

        if (empty($data['codigo'])) {
            return redirect()->to('cadastro/contrato_posicao_estoque');
        }

        // Validação da tela de edição
        $validation = $this->validate($this->contratoPosicaoEstoqueModel->getValidations(OR_UPDATE));

        if (!$validation) {
            return redirect()->back()->withInput()->with('validation_errors', $this->validator->getErrors());
        }

        // Adiciona campo de auditoria
        $data['atualizado_por'] = session()->user['username'] ?? 'sistema';

        // Prepara dados para fazer a alteração
        // Altera registro no bando de dados
        $this->contratoPosicaoEstoqueModel->update($data['codigo'], $data);

        // redireciona tela
        return redirect()->to('/cadastro/contrato_posicao_estoque')->withInput()->with('server_success', 'Atualização efetuada com sucesso.');
    }

    // ------------------------------------
    // Exclui registro
    // ------------------------------------

    public function exclui($codigo)
    {
        $contrato = $this->contratoPosicaoEstoqueModel->find($codigo);

        if (!$contrato) {
            return redirect()->to('/cadastro/contrato_posicao_estoque')->withInput()->with('server_warning', "Registro $codigo não existe mais no banco de dados.");
        }

        // Exclui o registro usando soft delete
        $this->contratoPosicaoEstoqueModel->delete($codigo);
        redirect()->to('/cadastro/contrato_posicao_estoque')->withInput()->with('server_success', 'Registro excluído com sucesso.');
    }

    public function findLikeName()
    {
        $term = htmlspecialchars($this->request->getGet('term'), ENT_QUOTES, 'UTF-8');

        // Buscar contrato que correspondam ao termo
        $contratos = $this->contratoPosicaoEstoqueModel->select("codigo, numero_contrato")->like('numero_contrato', $term)->findAll();

        // Estruturar a resposta para evitar vazamento de campos desnecessários
        $response = array_map(function ($contrato) {
            return [
                'codigo' => $contrato->codigo,
                'numero_contrato' => $contrato->numero_contrato,
            ];
            }, $contratos);

        return $this->response->setJSON($response);
    }
}
