<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class AddKepengurusan extends Migration
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
            'anggota_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'jabatan' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'periode' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
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
        $this->forge->addForeignKey('anggota_id', 'anggotas', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('kepengurusans');
    }

    public function down()
    {
        $this->forge->dropForeignKey('kepengurusans', 'organisasi_id');
        $this->forge->dropForeignKey('kepengurusans', 'anggota_id');
        $this->forge->dropTable('kepengurusans');
    }
}
