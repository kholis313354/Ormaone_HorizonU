<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class AddPemilihan extends Migration
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
            'organisasi_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'periode' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'tanggal_mulai' => [
                'type'       => 'DATETIME',
            ],
            'tanggal_akhir' => [
                'type'       => 'DATETIME',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['draft', 'publish', 'selesai'],
                'default'    => 'draft',
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
        $this->forge->addForeignKey('organisasi_id', 'organisasis', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pemilihans');
    }

    public function down()
    {
        $this->forge->dropForeignKey('pemilihans', 'organisasi_id');
        $this->forge->dropTable('pemilihans');
    }
}
