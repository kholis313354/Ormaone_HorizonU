<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusToBerita extends Migration
{
    public function up()
    {
        $this->forge->addColumn('berita', [
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['draft', 'published'],
                'default' => 'published',
                'after' => 'tanggal'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('berita', 'status');
    }
}
