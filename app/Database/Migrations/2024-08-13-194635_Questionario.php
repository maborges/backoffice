<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Questionario extends Migration
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
            'situacao' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => false,
                'default' => 0
            ],
            'nome' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false
            ],
            'descricao' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
                'null' => true
            ],
            'avaliativo' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => false,
                'default' => 0
            ],
            'pontuacao_maxima' => [
                'type' => 'INT',
                'contraint' => 3,
                'unsigned' => true,
                'default' => 100
            ],
            'pontuacao_minima' => [
                'type' => 'INT',
                'contraint' => 3,
                'unsigned' => true,
                'default' => 0
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
        $this->forge->createTable('questionario');
        
    }

    public function down()
    {
        // Drop table
        $this->forge->dropTable('questionario');
    }

}
