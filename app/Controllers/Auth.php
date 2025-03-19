<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use App\Controllers\BaseController;
use App\Models\FilialModel;
use CodeIgniter\HTTP\ResponseInterface;

class Auth extends BaseController
{
    public function login()
    {
        // verifica se retornou erro validação dos inputs
        $data['validation_errors'] = session()->getFlashdata('validation_errors');

        // verifica se as credienciais estão corretas
        $data['login_error'] = session()->getFlashdata('login_error');

        return view('auth/login', $data);
    }

    public function loginSubmit()
    {
        $validation = $this->validate([
            'username' => [
                'label' => 'Usuário',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Informe o campo {field}.'
                ]
            ],
            'password' => [
                'label' => 'Senha',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Informe o campo {field}.'
                ]
            ]
        ]);

        if (!$validation) {
            return redirect()->back()->withInput()->with('validation_errors', $this->validator->getErrors());
        }

        // verifica se o login é válido
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $usuarioModel = new UsuarioModel();
        $usuario = $usuarioModel->verifyLogin($username, $password);

        if (!$usuario) {
            return redirect()->back()->withInput()->with('login_error', 'Credenciais de acesso inválidas.');
        }

        if (empty($usuario->filial)) {
            return redirect()->back()->withInput()->with('login_error', 'Usuário não está alocado em nenhuma filial.');
        }

        $where = [
            'descricao' => $usuario->filial,
            'estado_registro' => 'ATIVO'
        ];

        // busca dados da filial do usuário
        $filiaisModel = new FilialModel();
        $filial = $filiaisModel->where($where)->first();

        if (!$filial) {
            return redirect()->back()->withInput()->with('login_error', 'Filial do usuário não cadastrada ou inativa.');
        }
            
        // cria sessão do usuário
        $userData = [
            'username'                  => $usuario->username,
            'firstName'                 => $usuario->primeiro_nome,
            'idSankhya'                 => $usuario->id_sankhya,
            'defaultBranchId'           => $filial->codigo,
            'defaultBranch'             => $filial->descricao,
            'defaultBranchNickname'     => $filial->apelido,
            'selectedBranchId'          => $filial->codigo,
            'selectedBranch'            => $filial->descricao,
            'selectedBranchNickname'    => $filial->apelido,

        ];

        // a sessão "user" está relacionada ao filtro "UserIsLoggedIn" na pasta filter
        // este filter condiciona se o usuário deve fazer o login ou não caso já tenha feito
        session()->set('user', $userData);

        // Busca as filiais para seleção
        $filiais = $filiaisModel->select('codigo,descricao,apelido,estado_registro')->findAll();
        session()->set('filiais', $filiais);

        return redirect()->to('/');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/auth/login');
    }
}
