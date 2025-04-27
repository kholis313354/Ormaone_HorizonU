<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class AddPemilihanCalonSuara extends Migration
{
    public function up()
    {
        // id, pemilihan_calon_id, nim, nama, email, anggota_id (boleh null), ip, user agent
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'pemilihan_calon_id' => [
                'type' => 'INT',
                'unsigned' => true,
            ],
            'nim' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'anggota_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
            ],
            'user_agent' => [
                'type' => 'TEXT',
            ],
            'kode_fakultas' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
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
        $this->forge->addForeignKey('pemilihan_calon_id', 'pemilihan_calons', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pemilihan_calon_suara');
    }

    public function down()
    {
        $this->forge->dropForeignKey('pemilihan_calon_suara', 'pemilihan_calon_suara_pemilihan_calon_id_foreign');
        $this->forge->dropTable('pemilihan_calon_suara');
    }
}
