<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class AddPemilihanCalon extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'pemilihan_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'anggota_id_1' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'anggota_id_2' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'gambar_1' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'gambar_2' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'number' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'description' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('pemilihan_id', 'pemilihans', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('anggota_id_1', 'anggotas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('anggota_id_2', 'anggotas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pemilihan_calons');
    }

    public function down()
    {
        $this->forge->dropForeignKey('pemilihan_calons', 'pemilihan_id');
        $this->forge->dropForeignKey('pemilihan_calons', 'anggota_id_1');
        $this->forge->dropForeignKey('pemilihan_calons', 'anggota_id_2');
        $this->forge->dropTable('pemilihan_calons');
    }
}
