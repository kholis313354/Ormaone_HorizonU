<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFakultas extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_fakultas' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('fakultas');

        // Seed data fakultas
        $data = [
            ['nama_fakultas' => 'Fakultas Manajemen dan Bisnis'],
            ['nama_fakultas' => 'Fakultas Humaniora dan Sains'],
            ['nama_fakultas' => 'Fakultas Teknologi Informasi'],
            ['nama_fakultas' => 'Fakultas Kesehatan'],
        ];
        $this->db->table('fakultas')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('fakultas');
    }
}
