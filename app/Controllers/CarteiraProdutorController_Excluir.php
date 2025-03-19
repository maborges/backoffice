<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CarteiraProdutorModel;

class CarteiraProdutorController extends BaseController
{
    protected $carteiraProdutorModel;
    private const TITULO = 'Carteira de Produtores';

    public function __construct()
    {
        $this->carteiraProdutorModel = new CarteiraProdutorModel();
    }

    public function index()
    {
        $data = [
            'title' => SELF::TITULO,
            'page'               => 'Lista de Carteira de Produtores',
            'server_success'     => session()->getFlashdata('server_success'),
            'server_warning'     => session()->getFlashdata('server_warning'),
            'datatables'         => true,
            'carteiraProdutores' => $this->carteiraProdutorModel->getWithDetails()
        ];

        return view('cadastro/carteira_produtor/index', $data);
    }

    // ------------------------------------
    // Cria Carteira de Produtores
    // ------------------------------------

    public function cria()
    {
        $data = [
            'title' => SELF::TITULO,
            'page' => 'Inclui Carteira de Produtores'
        ];

        $data['validation_errors'] = session()->getFlashdata('validation_errors');
        $data['server_error']      = session()->getFlashdata('server_error');
        $data['server_success']    = session()->getFlashdata('server_success');

        return view('cadastro/carteira_produtor/cria_carteira_produtor', $data);
    }

    public function grava()
    {
        $data = $this->request->getPost();

        $validation = $this->validate($this->carteiraProdutorModel->getValidations(OR_INSERT));

        if (!$validation) {
            return redirect()->back()->withInput()->with('validation_errors', $this->validator->getErrors());
        }

        if ($this->carteiraProdutorModel->alreadyExists($data['nome_carteira_produtor'])) {
            return redirect()->back()->withInput()->with('server_error', 'Já existe uma carteira de produtores cadastrada com este nome.');
        }

        // prepara dados para inclusão
        $data['criado_por']     = session()->user['username'];
        $data['atualizado_por'] = session()->user['username'];

        $this->carteiraProdutorModel->insert($data);
        return redirect()->to('/cadastro/carteira_produtor')->withInput()->with('server_success', 'Inclusão efetuada com sucesso.');
    }

    // ------------------------------------
    // Edita Carteira de Produtores
    // ------------------------------------
    public function edita($id)
    {
        $data = [
            'title' => SELF::TITULO,
            'page' => 'Altera Carteira de Produtores'
        ];


        if (empty($id)) {
            return redirect()->to('cadastro/carteira_produtor');
        }

        // Validação
        $data['validation_errors'] = session()->getFlashdata('validation_errors');

        $data['carteiraProdutor'] = $this->carteiraProdutorModel->find($id);
        return view('cadastro/carteira_produtor/edita_carteira_produtor', $data);
    }

    public function atualiza()
    {
        $data = $this->request->getPost();

        if (empty($data['id'])) {
            return redirect()->to('cadastro/carteira_produtor');
        }

        // Validação da tela de edição
        $validation = $this->validate($this->carteiraProdutorModel->getValidations(OR_UPDATE));

        if (!$validation) {
            return redirect()->back()->withInput()->with('validation_errors', $this->validator->getErrors());
        }

        // Prepara dados para fazer a alteração
        $data['atualizado_por'] = session()->user['username'];

        // Altera registro no bando de dados
        $this->carteiraProdutorModel->update($data['id'], $data);

        // redireciona tela
        return redirect()->to('/cadastro/carteira_produtor')->withInput()->with('server_success', 'Atualização efetuada com sucesso.');
    }

    // ------------------------------------
    // Exclui registro
    // ------------------------------------

    public function exclui($id)
    {
        $carteiraProdutor = $this->carteiraProdutorModel->find($id);

        if (!$carteiraProdutor) {
            return redirect()->to('/cadastro/carteira_produtor')->withInput()->with('server_warning', "Registro $id não existe mais no banco de dados.");
        }

        // Prepara dados para fazer a alteração
        $data = [
            'excluido_por' => session()->user['username']
        ];

        $this->carteiraProdutorModel->delete($id);
        redirect()->to('/cadastro/carteira_produtor')->withInput()->with('server_success', 'Registro excluído com sucesso.');
    }

    public function findLikeName()
    {
        $term = htmlspecialchars($this->request->getGet('term'), ENT_QUOTES, 'UTF-8');

        // Buscar região que correspondam ao termo
        $carteiraProdutores = $this->carteiraProdutorModel->select("id, nome_carteira_produtor")->like('nome_carteira_produtor', $term)->findAll();

        // Estruturar a resposta para evitar vazamento de campos desnecessários
        $response = array_map(function ($carteiraProdutor) {
            return [
                'id' => $carteiraProdutor->id,
                'nome_carteira_produtor' => $carteiraProdutor->nome_carteira_produtor,
            ];
        }, $carteiraProdutores);

        return $this->response->setJSON($response);
    }

}
