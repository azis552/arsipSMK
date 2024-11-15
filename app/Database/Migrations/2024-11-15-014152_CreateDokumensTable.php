<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDokumensTable extends Migration
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
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'document_path' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'upload_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'veryfied_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true
            ],
            'verified_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'is_signed' => [
                'type' => 'BOOLEAN',
                'default' => false
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('dokumens');
    }
}
