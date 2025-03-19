<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuarios';
    protected $primaryKey       = 'username';
    protected $useAutoIncrement = false;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username', 'senha', 'primeiro_nome', 'nome_completo', 'email_principal', 'usuario_interno', 'departamento',
        'funcao', 'telefone_fixo', 'celular', 'contador_bloqueio', 'estado_registro', 'filial', 'usuario_rovereti',
        'codigo_rovereti', 'usuario_cadastro', 'data_cadastro', 'hora_cadastro', 'usuario_alteracao', 'data_alteracao',
        'hora_alteracao', 'usuario_exclusao', 'data_exclusao', 'hora_exclusao', 'nome_filial', 'id_sankhya'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = ['getFilters'];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Filtra somente os registro não excluidos
     */
    protected function getFilters()
    {
        $this->where('usuarios.estado_registro', 'ATIVO');
    }    

    public function verifyLogin($username, $password)
    {
        // where clauses
        $where = [
            'username' => $username
        ];

        // busca dados do usuário
        $usuario = $this->where($where)->first();

        if (empty($usuario)) {
            return false;
        }

        // Troca por if (password_verify($password, $usuario->senha)) { ... }
        if (md5($password) <> $usuario->senha) {
            return false;
        }

        // Colocar aqui a atualização do campo que representa a data do último login
        /* Exemplo
            $this->update($usuario->username,['campo' => date('Y-m-d H:i:s')]);
        */

        return $usuario;
    }

    public function countByStatus($filial, $status = '')
    {
        $where = [];

        if ($filial) {
            $where['filial'] = $filial;
        }

        if ($status) {
            $where['estado_registro'] = $status;
        }

        $result = $this->select('count(*) as total')->where($where)->groupBy('estado_registro')->first();

        if (isset($result->total)) {
            return $result->total;
        } else {
            return 0;
        }
    }


}