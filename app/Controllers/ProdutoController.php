<?php

namespace App\Controllers;

use App\Models\ProdutoModel;
use CodeIgniter\Controller;

class ProdutoController extends BaseController
{
    protected $produtoModel;
    private const TITULO = 'Produtos';

    public function __construct()
    {
        $this->produtoModel = new ProdutoModel();
    }

    /**
     * Lista todos os produtos.
     */
    public function index()
    {
        $data = [
            'title' => SELF::TITULO,
            'page' => 'Lista de Produtos',
            'server_success' => session()->getFlashdata('server_success'),
            'server_warning' => session()->getFlashdata('server_warning'),
            'datatables'     => true,
            'produtos'       => $this->produtoModel->findAll()
        ];

        // return view('produto/index', $data);
        echo 'produtos';
    }

    /**
     * Pesquisa um produto pelo código.
     *
     * @param string $id
     */
    public function findById($id)
    {
        $produto = $this->produtoModel->findById($id);

        if ($produto) {
            $data['produto'] = $produto;
            echo $data;
            // return view('produto/view', $data);
        } else {
            return redirect()->to('/produto')->with('error', 'Produto não encontrado.');
        }
    }

    public function findLikeDescription()
    {
        $term = htmlspecialchars($this->request->getGet('term'), ENT_QUOTES, 'UTF-8');

        // Buscar produtos que correspondam ao termo
        $produtos = $this->produtoModel->select("codigo, concat(codigo,' - ', descricao, ' (', unidade_print, ')') as descricao")->like('descricao', $term)->findAll();

        // Estruturar a resposta para evitar vazamento de campos desnecessários
        $response = array_map(function ($produto) {
            return [
                'codigo' => $produto->codigo,
                'descricao' => $produto->descricao,
            ];
        }, $produtos);

        return $this->response->setJSON($response);
    }    
}
