<?php

namespace App\Controllers;

use App\Models\LimiteCompraModel;
use App\Controllers\BaseController;

class LimiteCompraController extends BaseController
{
    protected $limiteCompraModel;

    private const TITULO = 'Limite de Compras';

    public function __construct()
    {
        $this->limiteCompraModel = new LimiteCompraModel();
    }    

    public function index()
    {
        $data = [
            'title' => SELF::TITULO,
            'page' => 'Lista de Limites de Compras',
            'server_success' => session()->getFlashdata('server_success'),
            'server_warning' => session()->getFlashdata('server_warning'),
            'datatables'     => true,
            'limiteCompras'  => $this->limiteCompraModel->getWithDetails()
        ];

        return view('gerencial/limite_compra/index', $data);
    }

    // ------------------------------------
    // Cria limite de compra
    // ------------------------------------

    public function cria()
    {
        $data = [
            'title' => SELF::TITULO,
            'page' => 'Inclui Limite de Compras'
        ];

        $data['validation_errors'] = session()->getFlashdata('validation_errors');
        $data['server_error']      = session()->getFlashdata('server_error');
        $data['server_success']    = session()->getFlashdata('server_success');

        return view('gerencial/limite_compra/cria_limite_compra', $data);
    }

    public function grava()
    {
        $data = $this->request->getPost();

        $validation = $this->validate($this->limiteCompraModel->getValidations(OR_INSERT));

        if (!$validation) {
            return redirect()->back()->withInput()->with('validation_errors', $this->validator->getErrors());
        }

        if (!$this->limiteCompraModel->validateProdutor($data['fldProdutor'])) {
            return redirect()->back()->withInput()->with('server_error', 'Produtor não cadastrado .');
        }

        if (!$this->limiteCompraModel->validateProduto($data['fldProduto'])) {
            return redirect()->back()->withInput()->with('server_error', 'Produto não cadastrado.');
        }

        if ($this->limiteCompraModel->alreadyExists($data['fldProdutor'], $data['fldProduto'] )) {
            return redirect()->back()->withInput()->with('server_error', 'Já existe limite de compras cadastrado para este Produtor/Produto.');
        }

        // prepara dados para inclusão
        $data['id_produtor']       = $this->request->getPost('fldProdutor');
        $data['id_produto']        = $this->request->getPost('fldProduto');
        $data['quantidade_limite'] = $this->request->getPost('fldLimiteCompra');
        $data['criado_por']        = session()->user['username'];
        $data['atualizado_por']    = session()->user['username'];

        $this->limiteCompraModel->insert($data);
        return redirect()->to('/gerencial/limite_compra')->withInput()->with('server_success', 'Inclusão efetuada com sucesso.');
    }

    // ------------------------------------
    // Edita Limite de Compra
    // ------------------------------------
    public function edita($id)
    {
        $data = [
            'title' => SELF::TITULO,
            'page' => 'Altera Limite de Compra'
        ];
        

        if (empty($id)) {
            return redirect()->to('gerencial/limite_compra');
        }

        // Validação
        $data['validation_errors'] = session()->getFlashdata('validation_errors');

        $data['limiteCompra'] = $this->limiteCompraModel->getWithDetails($id);

        return view('gerencial/limite_compra/edita_limite_compra', $data);
    }

    public function atualiza()
    {
        // Lê o campo id do POST da tela e verifica se está válido
        $id = $this->request->getPost('fldId');

        if (empty($id)) {
            return redirect()->to('gerencial/limite_compra');
        }

        // Validação da tela de edição
        $validation = $this->validate($this->limiteCompraModel->getValidations(OR_UPDATE));

        if (!$validation) {
            return redirect()->back()->withInput()->with('validation_errors', $this->validator->getErrors());
        }

        // Prepara dados para fazer a alteração
        $data = [
            'quantidade_limite' => $this->request->getPost('fldLimiteCompra'),
            'atualizado_por' => session()->user['username']
        ];

        // Altera registro no bando de dados
        $this->limiteCompraModel->update($id, $data);

        // redireciona tela
        return redirect()->to('/gerencial/limite_compra')->withInput()->with('server_success', 'Atualização efetuada com sucesso.');
    }

    // ------------------------------------
    // Exclui registro
    // ------------------------------------
   
    public function exclui($id)
    {
        $limiteCompra = $this->limiteCompraModel->first($id);

        if (!$limiteCompra) {
            return redirect()->to('/gerencial/limite_compra')->withInput()->with('server_warning', "Registro $id não existe mais no banco de dados.");
        }

        // Prepara dados para fazer a alteração
        $data = [
            'excluido_por' => session()->user['username']
        ];

        $this->limiteCompraModel->delete($id);
        redirect()->to('/gerencial/limite_compra')->withInput()->with('server_success', 'Registro excluído com sucesso.');
    }




}
