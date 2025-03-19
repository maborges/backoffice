<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Regiao extends Migration
{
    public function up()
    {
        // fields definition
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'contraint' => 3,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'nome_regiao' => [
                'type' => 'VARCHAR',
                'constraint' => 40,
                'null' => false
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
        $this->forge->addKey('id', true);

        // Definir chaves estrangeiras
        $this->forge->addKey('nome_regiao', false, false, 'idx_regiao_01');


        // create table 
        $this->forge->createTable('regiao');
    }

    public function down()
    {
        // Drop table
        $this->forge->dropTable('regiao');
    }
}
