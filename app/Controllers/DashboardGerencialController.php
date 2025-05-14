<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ComprasModel;
use App\Models\UsuarioModel;
use App\Models\PessoaModel;
use CodeIgniter\HTTP\ResponseInterface;
use DateTime;

class DashboardGerencialController extends BaseController
{
    protected $comprasModel;
    protected $usuarioModel;
    protected $pessoaModel;

    private const TITULO = 'Dashboard Gerencial';

    public function __construct()
    {
        $this->comprasModel = new ComprasModel();
        $this->usuarioModel = new UsuarioModel();   
        $this->pessoaModel = new PessoaModel(); 
    }    

    public function index()
    {
        // Se as datas não forem informadas, definir o período padrão
        $endDateObj = new \DateTime('last day of previous month');
        $startDateObj = (clone $endDateObj)->modify('-1 months');

        $startDate = $startDateObj->format('Y-m-d');
        $endDate = $endDateObj->format('Y-m-d');

        $data = [
            'title'             => SELF::TITULO,
            'page'              => 'Dashboard Gerencial',
            'server_success'    => session()->getFlashdata('server_success'),
            'server_warning'    => session()->getFlashdata('server_warning'),
            'datatables'        => true,
            'startDate'         => $startDate,
            'endDate'           => $endDate,
            'produto'           => '-2',
            'nomeProduto'       => '',
            'filial'            => ''
        ];

        return view('dashboard/gerencial', $data);
   }

}
