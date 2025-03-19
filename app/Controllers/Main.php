<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FilialModel;
use CodeIgniter\HTTP\ResponseInterface;

class Main extends BaseController
{
    public function index()
    {
        $dashboardModel = new Dashboard();
        $data = $dashboardModel->dataPage();
        $data['title'] = 'Dashboard Administrativo';
        $data['page'] = 'Dashboard Administrativo';
    
        return view('dashboard/home',$data);
    }

    public function changeBranch($branch)
    {
        $where = [
            'codigo' => "$branch",
            'estado_registro' => 'ATIVO'
        ];

        $filiaisModel = new FilialModel();
        $filial = $filiaisModel->select('codigo,descricao,apelido')->where($where)->first();

        session()->push('user', ['selectedBranchId'         => $filial->codigo]);
        session()->push('user', ['selectedBranch'           => $filial->descricao]);
        session()->push('user', ['selectedBranchNickname'   => $filial->apelido]);

        return redirect()->to('/');
    }

}
