<?php

namespace App\Controllers;

use App\Models\CompradorModel;

class CompradorController extends BaseController
{
    protected $compradorModel;
    private const TITULO = 'Compradores';


    public function __construct()
    {
        $this->compradorModel = new CompradorModel();
    }

    public function index()
    {
        $data = [
            'title' => SELF::TITULO,
            'page' => 'Lista de Compradores',
            'server_success' => session()->getFlashdata('server_success'),
            'server_warning' => session()->getFlashdata('server_warning'),
            'datatables'     => true,
            'compradores'    => $this->compradorModel->getCompradores()
        ];

        return view('cadastro/comprador/index', $data);
    }

    public function findLikeName() 
    {
        $term = htmlspecialchars($this->request->getGet('term'), ENT_QUOTES, 'UTF-8');

        // Buscar produtores que correspondam ao termo
        $compradores = $this->compradorModel->select("username, nome_completo")->like('nome_completo', $term)->findAll();

        // Estruturar a resposta para evitar vazamento de campos desnecessários
        $response = array_map(function ($comprador) {
            return [
                'username' => $comprador->username,
                'nome_completo' => $comprador->nome_completo,
            ];
        }, $compradores);

        return $this->response->setJSON($response);     
    }
}
