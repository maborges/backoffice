<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SaldoArmazenadoGerencialModel;
use App\Models\ProdutoModel;
use App\Models\FilialModel;
use CodeIgniter\HTTP\ResponseInterface;

class SaldoArmazenadoGerencialController extends BaseController
{
    protected $saldoArmazenadoModel;
    protected $produtoModel;
    protected $filialModel;
    
    private const TITULO = 'Saldo Armazenado Gerencial';

    public function __construct()
    {
        $this->saldoArmazenadoModel = new SaldoArmazenadoGerencialModel();
        $this->produtoModel = new ProdutoModel();
        $this->filialModel = new FilialModel();
    }

    /**
     * Lista todos os registros de saldo armazenado.
     */
    public function index()
    {
        $data = [
            'title' => SELF::TITULO,
            'page' => 'Lista de Saldos Armazenados',
            'server_success' => session()->getFlashdata('server_success'),
            'server_warning' => session()->getFlashdata('server_warning'),
            'datatables'     => true,
            'saldos'         => $this->saldoArmazenadoModel->findAll()
        ];

        return view('gerencial/saldo_armazenado/saldo_armazenado', $data);
    }

    /**
     * Exibe o formulário para criar um novo registro.
     */
    public function new()
    {
        $data = [
            'title' => SELF::TITULO,
            'page' => 'Novo Saldo Armazenado',
            'produtos' => $this->produtoModel->findAll(),
            'filiais' => $this->filialModel->findAll()
        ];

        return view('gerencial/saldo_armazenado/saldo_armazenado_form', $data);
    }

    /**
     * Processa o formulário de criação e salva o novo registro.
     */
    public function create()
    {
        $data = [
            'produto' => $this->request->getPost('produto'),
            'filial' => $this->request->getPost('filial'),
            'data_saldo' => $this->request->getPost('data_saldo'),
            'saldo' => $this->request->getPost('saldo')
        ];

        if ($this->saldoArmazenadoModel->insert($data)) {
            return redirect()->to('/saldo-armazenado')
                ->with('server_success', 'Saldo armazenado cadastrado com sucesso.');
        } else {
            return redirect()->back()
                ->with('server_warning', 'Erro ao cadastrar saldo armazenado.')
                ->withInput()
                ->with('validation', $this->saldoArmazenadoModel->errors());
        }
    }

    /**
     * Exibe o formulário para editar um registro existente.
     */
    public function edit($id = null)
    {
        $saldo = $this->saldoArmazenadoModel->find($id);

        if (!$saldo) {
            return redirect()->to('/saldo-armazenado')
                ->with('server_warning', 'Registro não encontrado.');
        }

        $data = [
            'title' => SELF::TITULO,
            'page' => 'Editar Saldo Armazenado',
            'saldo' => $saldo,
            'produtos' => $this->produtoModel->findAll(),
            'filiais' => $this->filialModel->findAll()
        ];

        return view('gerencial/saldo_armazenado/saldo_armazenado_form', $data);
    }

    /**
     * Processa o formulário de edição e atualiza o registro.
     */
    public function update($id = null)
    {
        $data = [
            'produto' => $this->request->getPost('produto'),
            'filial' => $this->request->getPost('filial'),
            'data_saldo' => $this->request->getPost('data_saldo'),
            'saldo' => $this->request->getPost('saldo')
        ];

        if ($this->saldoArmazenadoModel->update($id, $data)) {
            return redirect()->to('/saldo-armazenado')
                ->with('server_success', 'Saldo armazenado atualizado com sucesso.');
        } else {
            return redirect()->back()
                ->with('server_warning', 'Erro ao atualizar saldo armazenado.')
                ->withInput()
                ->with('validation', $this->saldoArmazenadoModel->errors());
        }
    }

    /**
     * Exclui um registro.
     */
    public function delete($id = null)
    {
        if ($this->saldoArmazenadoModel->delete($id)) {
            return redirect()->to('/saldo-armazenado')
                ->with('server_success', 'Saldo armazenado excluído com sucesso.');
        } else {
            return redirect()->to('/saldo-armazenado')
                ->with('server_warning', 'Erro ao excluir saldo armazenado.');
        }
    }

    /**
     * Busca registros filtrados por produto e filial para DataTable.
     */
    public function busca()
    {
        $produto = $this->request->getPost('produto');
        $filial = $this->request->getPost('filial');
        
        $data = $this->saldoArmazenadoModel->buscarPorProdutoEFilial($produto, $filial);
        
        return $this->response->setJSON(['data' => $data]);
    }
}