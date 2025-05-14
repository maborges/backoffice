<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FilialCompradorModel;
use App\Models\FilialModel;
use App\Models\CompradorModel;
use CodeIgniter\HTTP\ResponseInterface;

class FilialCompradorController extends BaseController
{
    protected $filialCompradorModel;
    protected $filialModel;
    protected $compradorModel;
    private const TITULO = 'Filial x Comprador';

    public function __construct()
    {
        $this->filialCompradorModel = new FilialCompradorModel();
        $this->filialModel = new FilialModel();
        $this->compradorModel = new CompradorModel();
    }

    public function index()
    {
        $data = [
            'title' => SELF::TITULO,
            'page' => 'Lista de Filial x Comprador',
            'server_success' => session()->getFlashdata('server_success'),
            'server_warning' => session()->getFlashdata('server_warning'),
            'datatables'     => true,
            'filiaisCompradores' => $this->filialCompradorModel->getList()
        ];

        return view('cadastro/filial_comprador/index', $data);
    }

    public function cria()
    {
        $data = [
            'title' => SELF::TITULO,
            'page' => 'Inclui Filial x Comprador',
            'filiais' => $this->filialModel->select('codigo, apelido')
                                         ->where('id_sankhya IS NOT NULL')
                                         ->findAll(),
            'compradores' => $this->compradorModel->getCompradores()
        ];

        $data['validation_errors'] = session()->getFlashdata('validation_errors');
        $data['server_error'] = session()->getFlashdata('server_error');
        $data['server_success'] = session()->getFlashdata('server_success');

        return view('cadastro/filial_comprador/cria_filial_comprador', $data);
    }

    public function grava()
    {
        $data = $this->request->getPost();

        $validation = $this->validate($this->filialCompradorModel->getValidations(OR_INSERT));

        if (!$validation) {
            return redirect()->back()->withInput()->with('validation_errors', $this->validator->getErrors());
        }

        if ($this->filialCompradorModel->alreadyExists($data['filial'], $data['comprador'])) {
            return redirect()->back()->withInput()->with('server_error', 'Já existe um registro com esta combinação de Filial e Comprador.');
        }

        $this->filialCompradorModel->insert($data);
        return redirect()->to('/cadastro/filial_comprador')->withInput()->with('server_success', 'Inclusão efetuada com sucesso.');
    }

    public function edita($filial, $comprador)
    {
        $data = [
            'title' => SELF::TITULO,
            'page' => 'Altera Filial x Comprador'
        ];

        if (empty($filial) || empty($comprador)) {
            return redirect()->to('cadastro/filial_comprador');
        }

        $data['validation_errors'] = session()->getFlashdata('validation_errors');
        $data['filialComprador'] = $this->filialCompradorModel->where('filial', $filial)
                                                             ->where('comprador', $comprador)
                                                             ->first();
        
        if (!$data['filialComprador']) {
            return redirect()->to('cadastro/filial_comprador')->with('server_warning', 'Registro não encontrado.');
        }

        return view('cadastro/filial_comprador/edita_filial_comprador', $data);
    }

    public function atualiza()
    {
        $data = $this->request->getPost();
        
        if (empty($data['filial']) || empty($data['comprador'])) {
            return redirect()->to('cadastro/filial_comprador');
        }

        $validation = $this->validate($this->filialCompradorModel->getValidations(OR_UPDATE));
     
        if (!$validation) {
            return redirect()->back()->withInput()->with('validation_errors', $this->validator->getErrors());
        }

        $this->filialCompradorModel->where('filial', $data['filial'])
                                  ->where('comprador', $data['comprador'])
                                  ->set($data)
                                  ->update();

        return redirect()->to('/cadastro/filial_comprador')->withInput()->with('server_success', 'Atualização efetuada com sucesso.');
    }

    public function exclui($filial, $comprador)
    {
        if (empty($filial) || empty($comprador)) {
            return redirect()->to('/cadastro/filial_comprador');
        }

        $filialComprador = $this->filialCompradorModel->where('filial', $filial)
                                                     ->where('comprador', $comprador)
                                                     ->first();

        if (!$filialComprador) {
            return redirect()->to('/cadastro/filial_comprador')->withInput()->with('server_warning', "Registro não encontrado.");
        }

        $this->filialCompradorModel->where('filial', $filial)
                                  ->where('comprador', $comprador)
                                  ->delete();

        return redirect()->to('/cadastro/filial_comprador')->withInput()->with('server_success', 'Registro excluído com sucesso.');
    }

} 