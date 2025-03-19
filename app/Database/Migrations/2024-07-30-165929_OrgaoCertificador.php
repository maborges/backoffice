<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class OrgaoCertificador extends Migration
{
    public function up()
    {
        // fields definition
        $this->forge->addField([
            'codigo' => [
                'type' => 'INT',
                'contraint' => 3,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'sigla' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false
            ],
            'nome' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false
            ],
            'situacao' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => false,
                'default' => 0
            ],
            'imagem' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ],
            'criado_em' => [
                'type' => 'DATETIME',
                'null' => false
            ],
            'criado_por' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'null' => false
            ],
            'atualizado_em' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'atualizado_por' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'null' => true
            ],
            'excluido_em' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);

        // primary key
        $this->forge->addKey('codigo', true);

        // create table 
        $this->forge->createTable('orgao_certificador');
    }

    public function down()
    {
        // Drop table
        $this->forge->dropTable('orgao_certificador');
    }
}
