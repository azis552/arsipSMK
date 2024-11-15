<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateQrcodeTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'document_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'qrcode_data' => [
                'type' => 'TEXT',
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ]
            ]);
            $this->forge->addKey('id', true);
            $this->forge->createTable('qrcodes', true);
    }

    public function down()
    {
        $this->forge->dropTable('qrcodes');
    }
}
