<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNamaSertifikat extends Migration
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
            'nama_sertifikat' => [
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
        $this->forge->createTable('nama_sertifikat');

        // Seed data nama sertifikat
        $data = [
            ['nama_sertifikat' => 'Peserta'],
            ['nama_sertifikat' => 'Juara 1'],
            ['nama_sertifikat' => 'Juara 2'],
            ['nama_sertifikat' => 'Juara 3'],
            ['nama_sertifikat' => 'Pembicara'],
            ['nama_sertifikat' => 'Panitia'],
            ['nama_sertifikat' => 'Narasumber'],
        ];
        $this->db->table('nama_sertifikat')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('nama_sertifikat');
    }
}
