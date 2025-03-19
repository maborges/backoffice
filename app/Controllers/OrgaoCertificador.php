<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrgaoCertificadorModel;
use CodeIgniter\HTTP\ResponseInterface;

class OrgaoCertificador extends BaseController
{
    private const TITULO = 'Orgãos Certificadores';

    public function index()
    {
        $data = [
            'title' => SELF::TITULO,
            'page' => 'Lista de Orgãos Certificadores'
        ];

        $data['server_success'] = session()->getFlashdata('server_success');
        $data['server_warning'] = session()->getFlashdata('server_warning');

        // obtém os orgãos
        $orgaoModel = new OrgaoCertificadorModel();
        $data['orgaos'] = $orgaoModel->findAll();

        return view('certificacao/orgao_certificador/index', $data);
    }

    // ------------------------------------
    // Cria orgão certificador
    // ------------------------------------

    public function cria()
    {
        $data = [
            'title' => SELF::TITULO,
            'page' => 'Inclui Orgão Certificador'
        ];

        $data['validation_errors'] = session()->getFlashdata('validation_errors');
        $data['server_error']      = session()->getFlashdata('server_error');
        $data['server_success']    = session()->getFlashdata('server_success');
        

        return view('certificacao/orgao_certificador/cria_orgao_certificador', $data);
    }

    public function grava()
    {
        $validation = $this->validate($this->camposValidacao(OR_INSERT));

        if (!$validation) {
            return redirect()->back()->withInput()->with('validation_errors', $this->validator->getErrors());
        }

        // varifica de o logo não é igual a 'no_image.png'
        if($this->request->getFile('fldImage')->getName() == 'no_image.png'){
            return redirect()->back()->withInput()->with('validation_errors', ['fldImage' => 'O campo logomarca do orgão é obrigatório.']);
        }

        // verifica se o orgão já existe 
        $orgaoModel = new OrgaoCertificadorModel();
        $orgao = $orgaoModel->where('sigla', $this->request->getPost('fldSigla'))->first();

        if ($orgao) {
            return redirect()->back()->withInput()->with('server_error', 'Ja existe um Orgão Certificador cadastrado com esta sigla.');
        }

        // upload da imagem
        $fileImage = $this->request->getFile('fldImage');
        $fileName = prefixedFileName($fileImage->getName());
        $fileImage->move('./assets/images/uploads', $fileName);
     
        // prepara dados para inclusão
        $data = [
            'sigla' => $this->request->getPost('fldSigla'),
            'nome' => $this->request->getPost('fldNome'),
            'situacao' => $this->request->getPost('fldSituacao') ? 1 : 0,
            'imagem' => $fileImage->getName(),
            'criado_por' => session()->user['username'],
            'atualizado_por' => session()->user['username']
        ];

        // inclui dados
        $orgaoModel->insert($data);

        // redireciona tela
        return redirect()->to('/certificacao/orgao_certificador')->withInput()->with('server_success', 'Inclusão efetuada com sucesso.');
    }

    // ------------------------------------
    // Edita orgao certificador
    // ------------------------------------
    public function edita($codigo) 
    {
        $data = [
            'title' => SELF::TITULO,
            'page' => 'Altera Orgão Certificador'
        ];

        if (empty($codigo)) {
            return redirect()->to('certificacao/orgao_certificador');
        }

        // Validação
        $data['validation_errors'] = session()->getFlashdata('validation_errors');

        $orgaoModel = new OrgaoCertificadorModel();
        $data['orgao'] = $orgaoModel->find($codigo);

        // Verifica se a imagem informada quando da inclusão, ainda existe pois pode ter sido excluída
        // Caso não seja encontrada, então coloca a imagem "No Exists"
        $tmp = ROOTPATH . 'public/assets/images/uploads/' . $data['orgao']->imagem; 

        if(!file_exists($tmp)) { 
            $data['orgao']->imagem = 'no_image.png';
        }

        return view('certificacao/orgao_certificador/edita_orgao_certificador', $data);
    }

    public function atualiza() 
    {
        // Validação da tela de edição
        $validation = $this->validate($this->camposValidacao(OR_UPDATE));

        // Lê o campo codigo do POST da tela e verifica se está válido
        $codigo = $this->request->getPost('fldCodigo');
        if (empty($codigo)) {
            return redirect()->to('certificacao/orgao_certificador');
        }

        if (!$validation) {
            return redirect()->back()->withInput()->with('validation_errors', $this->validator->getErrors());
        }

        // validates if the image file is not equal to 'no_image.png'
        if($this->request->getFile('fldImage')->getName() == 'no_image.png'){
            return redirect()->back()->withInput()->with('validation_errors', ['fldImage' => 'O campo logomarca do orgão é obrigatório.']);
        }

        $orgaoModel = new OrgaoCertificadorModel();

        // Prepara dados para fazer a alteração
        $data = [
            'sigla' => $this->request->getPost('fldSigla'),
            'nome' => $this->request->getPost('fldNome'),
            'situacao' => $this->request->getPost('fldSituacao') ? 1 : 0,
            'atualizado_por' => session()->user['username']
        ];

        // Verifica se a imagem foi alterada
        $fileImage = $this->request->getFile('fldImage');

        if ($fileImage->getName() != '') {

            // prefix image name
            $fileName = prefixedFileName($fileImage->getName());

            // Carrega imagem
            $fileImage->move('./assets/images/uploads', $fileName);

            // Altera imagem
            $data['imagem'] = $fileName;
        }    

        // Altera registro no banco de dados
        $orgaoModel->update($codigo, $data);

        // redireciona tela
        return redirect()->to('/certificacao/orgao_certificador')->withInput()->with('server_success', 'Atualização efetuada com sucesso.');
    }

    // ------------------------------------
    // Exclui orgão certificados
    // ------------------------------------
    public function exclui($codigo) 
    {
        // verifica se o orgão ainda existe antes de excluí-lo
        $orgaoModel = new OrgaoCertificadorModel();
        $orgao = $orgaoModel->find($codigo);

        if (!$orgao) {
            return redirect()->to('/certificacao/orgao_certificador')->withInput()->with('server_error', "Orgão Certificador $codigo não encontrado.");
        }

        $data = [
            'title' => Self::TITULO,
            'page' => 'Exclui Orgão Certificador',
            'orgao' => $orgao
        ];  

        return view('certificacao/orgao_certificador/exclui_orgao_certificador', $data);
    }

    public function confirma($codigo) 
    {

        $orgaoModel = new OrgaoCertificadorModel();
        $orgao = $orgaoModel->find($codigo);

        if (!$orgao) {
            return redirect()->to('/certificacao/orgao_certificador')->withInput()->with('server_warning', "Registro $codigo não existe mais no banco de dados.");
        }

        // Prepara dados para fazer a alteração
        $data = [
            'excluido_por' => session()->user['username']
        ];
        
        $orgaoModel->delete($codigo);
        return redirect()->to('/certificacao/orgao_certificador')->withInput()->with('server_success', 'Registro excluído com sucesso.');

    }

    private function camposValidacao($operacao) 
    { 
        if ($operacao == OR_INSERT)
        {
            // imagem do orgão
            $resultFields['fldImage'] = [
                'label' => 'imagem do orgão certificador',
                'rules' => [
                    'uploaded[fldImage]',
                    'mime_in[fldImage,image/png]',
                    'max_size[fldImage,200]'
                ],
                'errors' => [
                    'uploaded' => FIELD_MESSAGE_UPLOADED,
                    'mime_in' => sprintf(FIELD_MESSAGE_MINE_IN,'PNG'),
                    'max_size' => sprintf(FIELD_MESSAGE_MAX_SIZE,'200KB')
                ]
            ];
        }

        if (in_array($operacao,[OR_INSERT,OR_UPDATE]))
        {
            // input fields
            $resultFields['fldSigla'] = [
                'label' => 'sigla',
                'rules' => 'required|min_length[3]|max_length[10]',
                'errors' => [
                    'required' => FIELD_MESSAGE_REQUIRED,
                    'min_length' => sprintf(FIELD_MESSAGE_MIN_LENGTH,'3'),
                    'max_length' => sprintf(FIELD_MESSAGE_MAX_LENGTH,'10')
                ]
            ];

            $resultFields ['fldNome'] = [
                'label' => 'nome',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required'  => FIELD_MESSAGE_REQUIRED,
                    'min_length' => sprintf(FIELD_MESSAGE_MIN_LENGTH,'3'),
                    'max_length' => sprintf(FIELD_MESSAGE_MAX_LENGTH,'100')
                ]
            ];
        }

        return $resultFields;

    }

}
