<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class LimiteCredito extends Migration
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
            'id_produtor' => [
                'type' => 'INT',
                'constraint' => 7,
                'null' => false
            ],
            'valor_limite' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
                'default' => 0.00
            ],
            'valor_utilizado' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
                'default' => 0.00
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
        $this->forge->addForeignKey('id_produtor', 'cadastro_pessoa',  'codigo', 'CASCADE', 'CASCADE', 'fklimite_credito_01');


        // create table 
        $this->forge->createTable('limite_credito');
    }

    public function down()
    {
        // Drop table
        $this->forge->dropTable('limite_credito');
    }
}

