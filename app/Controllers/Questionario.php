<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\QuestionarioModel;
use CodeIgniter\HTTP\ResponseInterface;

class Questionario extends BaseController
{

    private const TITULO = 'Questionarios';

    public function index()
    {
        $questionarioModel = new QuestionarioModel();

        $data = [
            'title' => SELF::TITULO,
            'page' => 'Lista de Questionários',
            'server_success' => session()->getFlashdata('server_success'),
            'server_warning' => session()->getFlashdata('server_warning'),
            'datatables'     => true,
            'questionarios'  => $questionarioModel->findAll()
        ];

        return view('certificacao/questionario/index', $data);
    }

    // ------------------------------------
    // Cria orgão certificador
    // ------------------------------------

    public function cria()
    {
        $data = [
            'title' => SELF::TITULO,
            'page' => 'Inclui Questinário'
        ];

        $data['validation_errors'] = session()->getFlashdata('validation_errors');
        $data['server_error']      = session()->getFlashdata('server_error');
        $data['server_success']    = session()->getFlashdata('server_success');

        return view('certificacao/questionario/cria_questionario', $data);
    }

    public function grava()
    {
        $validation = $this->validate($this->camposValidacao(OR_INSERT));

        if (!$validation) {
            return redirect()->back()->withInput()->with('validation_errors', $this->validator->getErrors());
        }

        $pontuacaoMaxima = (int) $this->request->getPost('fldPontuacaoMaxima');
        $pontuacaoMinima = (int) $this->request->getPost('fldPontuacaoMinima');

        if ($pontuacaoMinima > $pontuacaoMaxima) {
            return redirect()->back()->withInput()->with('server_error', 'Pontuação mínima não pode ser superior à máxima.');
        }

        // prepara dados para inclusão
        $data = [
            'nome' => $this->request->getPost('fldNome'),
            'descricao' => $this->request->getPost('fldDescricao'),
            'avaliativo' => $this->request->getPost('fldAvaliativo') ? 1 : 0,
            'pontuacao_maxima' => $this->request->getPost('fldPontuacaoMaxima'),
            'pontuacao_minima' => $this->request->getPost('fldPontuacaoMinima'),
            'situacao' => $this->request->getPost('fldSituacao') ? 1 : 0,
            'criado_por' => session()->user['username'],
            'atualizado_por' => session()->user['username'],
        ];

        // inclui dados
        $questionarioModel = new QuestionarioModel();
        $questionarioModel->insert($data);

        // redireciona tela
        return redirect()->to('/certificacao/questionario')->withInput()->with('server_success', 'Inclusão efetuada com sucesso.');
    }

    // ------------------------------------
    // Edita questionario
    // ------------------------------------
    public function edita($codigo)
    {
        $data = [
            'title' => SELF::TITULO,
            'page' => 'Altera Questionário'
        ];

        if (empty($codigo)) {
            return redirect()->to('certificacao/questionario');
        }

        // Validação
        $data['validation_errors'] = session()->getFlashdata('validation_errors');

        $questionarioModel = new QuestionarioModel();
        $data['questionario'] = $questionarioModel->find($codigo);

        return view('certificacao/questionario/edita_questionario', $data);
    }

    public function atualiza()
    {
        // Validação da tela de edição
        $validation = $this->validate($this->camposValidacao(OR_UPDATE));

        // Lê o campo codigo do POST da tela e verifica se está válido
        $codigo = $this->request->getPost('fldCodigo');
        if (empty($codigo)) {
            return redirect()->to('certificacao/questionario');
        }

        if (!$validation) {
            return redirect()->back()->withInput()->with('validation_errors', $this->validator->getErrors());
        }

        $pontuacaoMaxima = (int) $this->request->getPost('fldPontuacaoMaxima');
        $pontuacaoMinima = (int) $this->request->getPost('fldPontuacaoMinima');

        if ($pontuacaoMinima > $pontuacaoMaxima) {
            return redirect()->back()->withInput()->with('server_error', 'Pontuação mínima não pode ser superior à máxima.');
        }

        $questionarioModel = new QuestionarioModel();

        // Prepara dados para fazer a alteração
        $data = [
            'descricao' => $this->request->getPost('fldDescricao'),
            'texto_explicativo' => $this->request->getPost('fldTexto_explicativo'),
            'avaliativo' => $this->request->getPost('fldAvaliativo') ? 1 : 0,
            'pontuacao_maxima' => $this->request->getPost('fldPontuacaoMaxima'),
            'pontuacao_minima' => $this->request->getPost('fldPontuacaoMinima'),
            'situacao' => $this->request->getPost('fldSituacao') ? 1 : 0,
            'atualizado_por' => session()->user['username']
        ];

        // Altera registro no bando de dados
        $questionarioModel->update($codigo, $data);

        // redireciona tela
        return redirect()->to('/certificacao/questionario')->withInput()->with('server_success', 'Atualização efetuada com sucesso.');
    }

    // ------------------------------------
    // Exclui registro
    // ------------------------------------
    public function exclui($codigo)
    {
        // verifica se o registro
        $questionarioModel = new QuestionarioModel();
        $questionario = $questionarioModel->find($codigo);

        if (!$questionario) {
            return redirect()->to('/certificacao/questionario')->withInput()->with('server_error', "Registro $codigo não encontrado.");
        }

        $data = [
            'title' => Self::TITULO,
            'page' => 'Exclui Questionário',
            'questionario' => $questionario
        ];

        return view('certificacao/questionario/exclui_questionario', $data);
    }

    public function confirma($codigo)
    {
        $questionarioModel = new QuestionarioModel();
        $questionario = $questionarioModel->find($codigo);

        if (!$questionario) {
            return redirect()->to('/certificacao/questionario')->withInput()->with('server_warning', "Registro $codigo não existe mais no banco de dados.");
        }

        // Prepara dados para fazer a alteração
        $data = [
            'excluido_por' => session()->user['username']
        ];

        $questionarioModel->delete($codigo);
        redirect()->to('/certificacao/questionario')->withInput()->with('server_success', 'Registro excluído com sucesso.');
    }

    private function camposValidacao($operacao)
    {
        if (in_array($operacao, [OR_INSERT, OR_UPDATE])) {
            // input fields
            $resultFields['fldNome'] = [
                'label' => 'Nome',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => FIELD_MESSAGE_REQUIRED,
                    'min_length' => sprintf(FIELD_MESSAGE_MIN_LENGTH, '3'),
                    'max_length' => sprintf(FIELD_MESSAGE_MAX_LENGTH, '100')
                ]
            ];

            $resultFields['fldDescricao'] = [
                'label' => 'Descrição',
                'rules' => 'required|min_length[3]|max_length[200]',
                'errors' => [
                    'required'  => FIELD_MESSAGE_REQUIRED,
                    'min_length' => sprintf(FIELD_MESSAGE_MIN_LENGTH, '3'),
                    'max_length' => sprintf(FIELD_MESSAGE_MAX_LENGTH, '200')
                ]
            ];

            $resultFields['fldPontuacaoMaxima'] = [
                'label' => 'Pontuação Máxima',
                'rules' => 'required|greater_than_equal_to[0]|less_than_equal_to[100]',
                'errors' => [
                    'required'  => FIELD_MESSAGE_REQUIRED,
                    'greater_than_equal_to' => sprintf(FIELD_MESSAGE_GREATER_THAN_EQUAL_TO, '0'),
                    'less_than_equal_to' => sprintf(FIELD_MESSAGE_LESS_THAN_EQUAL_TO, '100')
                ]
            ];

            $resultFields['fldPontuacaoMinima'] = [
                'label' => 'Pontuação Mínima',
                'rules' => 'required|greater_than_equal_to[0]|less_than_equal_to[100]',
                'errors' => [
                    'required'  => FIELD_MESSAGE_REQUIRED,
                    'greater_than_equal_to' => sprintf(FIELD_MESSAGE_GREATER_THAN_EQUAL_TO, '0'),
                    'less_than_equal_to' => sprintf(FIELD_MESSAGE_LESS_THAN_EQUAL_TO, '100')
                ]
            ];
        }

        return $resultFields;
    }
}
