<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ComprasModel;
use App\Models\EstoqueModel;
use App\Models\FavorecidoModel;
use App\Models\UsuarioModel;
use CodeIgniter\HTTP\ResponseInterface;
use DateTime;

class Dashboard extends BaseController
{
    public function dataPage()
    {
        // user data
        $userData['activeUsersCount']    = $this->contaUsuarioPorSituacao('ATIVO');
        $userData['inactiveUsersCount']  = $this->contaUsuarioPorSituacao('BLOQUEADO');

        $comprasModel = new ComprasModel();
        $dataUltimaCompra = $comprasModel->dataUltimaCompra(session()->user['selectedBranch'])->dataUltimaCompra;

        $userData['quantidadeCompras'] = $comprasModel->quantidadeComprasPeriodo(session()->user['selectedBranch'], $dataUltimaCompra, $dataUltimaCompra)->quantidade;
        $dataRange = new DateTime($dataUltimaCompra);

        $userData['periodoInicial']    = $dataRange->format('d/m/Y');
        $userData['periodoFinal']      = $dataRange->format('d/m/Y');
        $userData['selectedBranch']    = session()->user['selectedBranch'];

        $favorecidoModel                 = new FavorecidoModel();
        $userData['favorecidosAtivos']   = $favorecidoModel->count('ATIVO')->value;
        $userData['favorecidosInativos'] = $favorecidoModel->count('INATIVO')->value;

        $estoqueModel = new EstoqueModel();
        $userData['totalEstoque'] = $estoqueModel->totalEstoque(session()->user['selectedBranch'], [2, 3, 4, 11], 3);

        $data['userData'] = $userData;

        return $data;
    }

    private function contaUsuarioPorSituacao($status)
    {
        $usuarioModel = new UsuarioModel();
        return $usuarioModel->countByStatus(session()->user['selectedBranch'], $status);
    }
}
