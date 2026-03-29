<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHeaderImagePathToForms extends Migration
{
    public function up()
    {
        $fields = [
            'header_image_path' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'default' => null,
                'after' => 'description' // Placing it after description for logical order
            ],
        ];

        $this->forge->addColumn('forms', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('forms', 'header_image_path');
    }
}
