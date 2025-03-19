<?php

namespace App\Controllers;

use App\Models\LimiteCreditoModel;
use App\Controllers\BaseController;

class LimiteCreditoController extends BaseController
{
    protected $limiteCreditoModel;

    private const TITULO = 'Limite de Credito';

    public function __construct()
    {
        $this->limiteCreditoModel = new LimiteCreditoModel();
    }

    public function index()
    {
        $data = [
            'title' => SELF::TITULO,
            'page' => 'Lista de Limites de Crédito',
            'server_success' => session()->getFlashdata('server_success'),
            'server_warning' => session()->getFlashdata('server_warning'),
            'datatables'     => true,
            'limiteCreditos' => $this->limiteCreditoModel->getWithDetails()
        ];

        return view('gerencial/limite_credito/index', $data);
    }

    // ------------------------------------
    // Cria limite de crédito
    // ------------------------------------

    public function cria()
    {
        $data = [
            'title' => SELF::TITULO,
            'page' => 'Inclui Limite de Credito'
        ];

        $data['validation_errors'] = session()->getFlashdata('validation_errors');
        $data['server_error']      = session()->getFlashdata('server_error');
        $data['server_success']    = session()->getFlashdata('server_success');

        return view('gerencial/limite_credito/cria_limite_credito', $data);
    }

    public function grava()
    {
        $data = $this->request->getPost();

        $validation = $this->validate($this->limiteCreditoModel->getValidations(OR_INSERT));

        if (!$validation) {
            return redirect()->back()->withInput()->with('validation_errors', $this->validator->getErrors());
        }

        if (!$this->limiteCreditoModel->validateProdutor($data['fldProdutor'])) {
            return redirect()->back()->withInput()->with('server_error', 'Produtor não cadastrado .');
        }

        if ($this->limiteCreditoModel->alreadyExists($data['fldProdutor'])) {
            return redirect()->back()->withInput()->with('server_error', 'Já existe limite de crédito cadastrado para este produtor.');
        }

        // prepara dados para inclusão
        $data['id_produtor']    = $this->request->getPost('fldProdutor');
        $data['valor_limite']   = preg_replace("/\./", ',', $this->request->getPost('fldLimiteCredito'));
        $data['criado_por']     = session()->user['username'];
        $data['atualizado_por'] = session()->user['username'];

        $this->limiteCreditoModel->insert($data);
        return redirect()->to('/gerencial/limite_credito')->withInput()->with('server_success', 'Inclusão efetuada com sucesso.');
    }

    // ------------------------------------
    // Edita Limite de Crédito
    // ------------------------------------
    public function edita($id)
    {
        $data = [
            'title' => SELF::TITULO,
            'page' => 'Altera Limite de Crédito'
        ];


        if (empty($id)) {
            return redirect()->to('gerencial/limite_credito');
        }

        // Validação
        $data['validation_errors'] = session()->getFlashdata('validation_errors');
        $data['limiteCredito']     = $this->limiteCreditoModel->getWithDetails($id);

        return view('gerencial/limite_credito/edita_limite_credito', $data);
    }

    public function atualiza()
    {
        // Lê o campo id do POST da tela e verifica se está válido
        $id = $this->request->getPost('fldId');

        if (empty($id)) {
            return redirect()->to('gerencial/limite_credito');
        }

        // Validação da tela de edição
        $validation = $this->validate($this->limiteCreditoModel->getValidations(OR_UPDATE));

        if (!$validation) {
            return redirect()->back()->withInput()->with('validation_errors', $this->validator->getErrors());
        }

        // Prepara dados para fazer a alteração
        $data = [
            'valor_limite' => $this->request->getPost('fldLimiteCredito'),
            'atualizado_por' => session()->user['username']
        ];

        // Altera registro no bando de dados
        $this->limiteCreditoModel->update($id, $data);

        // redireciona tela
        return redirect()->to('/gerencial/limite_credito')->withInput()->with('server_success', 'Atualização efetuada com sucesso.');
    }

    // ------------------------------------
    // Exclui registro
    // ------------------------------------

    public function exclui($id)
    {
        $limiteCredito = $this->limiteCreditoModel->first($id);

        if (!$limiteCredito) {
            return redirect()->to('/gerencial/limite_credito')->withInput()->with('server_warning', "Registro $id não existe mais no banco de dados.");
        }

        // Prepara dados para fazer a alteração
        $data = [
            'excluido_por' => session()->user['username']
        ];

        $this->limiteCreditoModel->delete($id);
        redirect()->to('/gerencial/limite_credito')->withInput()->with('server_success', 'Registro excluído com sucesso.');
    }
}

