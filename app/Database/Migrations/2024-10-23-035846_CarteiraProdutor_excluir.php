<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CarteiraProdutor extends Migration
{
    public function up()
    {
        // fields definition
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'contraint' => 4,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'nome_carteira_produtor' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'null' => false
            ],
            'id_comprador' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
                'null' => true
            ],'criado_em' => [
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
        $this->forge->addKey('id', true, true, 'pkcarteira_produtor');

        // Definir chaves estrangeiras
        // Não dá para fazer uma fk com campo varchar, dá erro
        // $this->forge->addForeignKey('id_comprador', 'usuarios',  'username', 'CASCADE', 'CASCADE', 'fkcarteira_produtor_01');


        // create table 
        $this->forge->createTable('carteira_produtor');
    }

    public function down()
    {
        // Drop table
        $this->forge->dropTable('carteira_produtor');
    }
}
