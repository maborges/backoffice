<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FilialModel;
use CodeIgniter\HTTP\ResponseInterface;

class FilialController extends BaseController
{
    protected $filialModel;
    private const TITULO = 'Filiais';

    public function __construct()
    {
        $this->filialModel = new FilialModel();
    }

    public function index()
    {
        //
    }

    public function findLikeName()
    {
        $term = htmlspecialchars($this->request->getGet('term'), ENT_QUOTES, 'UTF-8');

        // Buscar flial que correspondam ao termo
        $filiais = $this->filialModel->select("codigo, descricao")->like('descricao', $term)->findAll();

        // Estruturar a resposta para evitar vazamento de campos desnecessários
        $response = array_map(function ($filial) {
            return [
                'codigo' => $filial->codigo,
                'descricao' => $filial->descricao,
            ];
        }, $filiais);

        return $this->response->setJSON($response);
    }      
}
