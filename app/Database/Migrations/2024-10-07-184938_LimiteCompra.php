<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class LimiteCompra extends Migration
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
            'id_produto' => [
                'type' => 'INT',
                'constraint' => 3,
                'null' => false
            ],
            'quantidade_limite' => [
                'type'       => 'INT',
                'constraint' => 7,
                'null' => false,
                'default' => 0
            ],
            'quantidade_utilizada' => [
                'type'       => 'INT',
                'constraint' => '7',
                'null' => false,
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
        $this->forge->addKey('id', true);

        // alternate key
        $this->forge->addKey(['id_produto', 'id_produtor'], false, true, 'ak_limite_compra_01');

        // Definir chaves estrangeiras
        $this->forge->addForeignKey('id_produto',  'cadastro_produto', 'codigo', 'CASCADE', 'CASCADE', 'fklimite_compra_01');
        $this->forge->addForeignKey('id_produtor', 'cadastro_pessoa',  'codigo', 'CASCADE', 'CASCADE', 'fklimite_compra_02');


        // create table 
        $this->forge->createTable('limite_compra');
    }

    public function down()
    {
        // Drop table
        $this->forge->dropTable('limite_compra');
    }
}
