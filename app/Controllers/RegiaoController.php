<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RegiaoModel;
use CodeIgniter\HTTP\ResponseInterface;

class RegiaoController extends BaseController
{

    protected $regiaoModel;
    private const TITULO = 'Regiões';

    public function __construct()
    {
        $this->regiaoModel = new RegiaoModel();
    }


    public function index()
    {
        $data = [
            'title' => SELF::TITULO,
            'page' => 'Lista de Regiões',
            'server_success' => session()->getFlashdata('server_success'),
            'server_warning' => session()->getFlashdata('server_warning'),
            'datatables'     => true,
            'regioes'        => $this->regiaoModel->findAll()
        ];

        return view('cadastro/regiao/index', $data);
    }

    // ------------------------------------
    // Cria Região
    // ------------------------------------

    public function cria()
    {
        $data = [
            'title' => SELF::TITULO,
            'page' => 'Inclui Região'
        ];

        $data['validation_errors'] = session()->getFlashdata('validation_errors');
        $data['server_error']      = session()->getFlashdata('server_error');
        $data['server_success']    = session()->getFlashdata('server_success');

        return view('cadastro/regiao/cria_regiao', $data);
    }
    
    public function grava()
    {
        $data = $this->request->getPost();

        $validation = $this->validate($this->regiaoModel->getValidations(OR_INSERT));

        if (!$validation) {
            return redirect()->back()->withInput()->with('validation_errors', $this->validator->getErrors());
        }

        if ($this->regiaoModel->alreadyExists($data['nome_regiao'])) {
            return redirect()->back()->withInput()->with('server_error', 'Já existe uma região cadastrada com este nome.');
        }

        // prepara dados para inclusão
        $data['criado_por']     = session()->user['username'];
        $data['atualizado_por'] = session()->user['username'];

        $this->regiaoModel->insert($data);
        return redirect()->to('/cadastro/regiao')->withInput()->with('server_success', 'Inclusão efetuada com sucesso.');
    }

    // ------------------------------------
    // Edita Região
    // ------------------------------------
    public function edita($id)
    {
        $data = [
            'title' => SELF::TITULO,
            'page' => 'Altera Região'
        ];


        if (empty($id)) {
            return redirect()->to('cadastro/regiao');
        }

        // Validação
        $data['validation_errors'] = session()->getFlashdata('validation_errors');

        $data['regiao'] = $this->regiaoModel->find($id);
        return view('cadastro/regiao/edita_regiao', $data);
    }

    public function atualiza()
    {
        $data = $this->request->getPost();
        
        if (empty($data['id'])) {
            return redirect()->to('cadastro/regiao');
        }

        // Validação da tela de edição
        $validation = $this->validate($this->regiaoModel->getValidations(OR_UPDATE));
     
        if (!$validation) {
            return redirect()->back()->withInput()->with('validation_errors', $this->validator->getErrors());
        }

        // Prepara dados para fazer a alteração
        $data['atualizado_por'] = session()->user['username'];

        // Altera registro no bando de dados
        $this->regiaoModel->update($data['id'], $data);

        // redireciona tela
        return redirect()->to('/cadastro/regiao')->withInput()->with('server_success', 'Atualização efetuada com sucesso.');
    }

    // ------------------------------------
    // Exclui registro
    // ------------------------------------

    public function exclui($id)
    {
        $regiao = $this->regiaoModel->find($id);

        if (!$regiao) {
            return redirect()->to('/cadastro/regiao')->withInput()->with('server_warning', "Registro $id não existe mais no banco de dados.");
        }

        // Prepara dados para fazer a alteração
        $data = [
            'excluido_por' => session()->user['username']
        ];

        $this->regiaoModel->delete($id);
        redirect()->to('/cadastro/regiao')->withInput()->with('server_success', 'Registro excluído com sucesso.');
    }

    public function findLikeName()
    {
        $term = htmlspecialchars($this->request->getGet('term'), ENT_QUOTES, 'UTF-8');

        // Buscar região que correspondam ao termo
        $regioes = $this->regiaoModel->select("id, nome_regiao")->like('nome_regiao', $term)->findAll();

        // Estruturar a resposta para evitar vazamento de campos desnecessários
        $response = array_map(function ($regiao) {
            return [
                'id' => $regiao->id,
                'nome_regiao' => $regiao->nome_regiao,
            ];
        }, $regioes);

        return $this->response->setJSON($response);
    }


}
