<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTandaTangan extends Migration
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
            'ttd' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'id_pegawai' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tandatangans');
    }

    public function down()
    {
        $this->forge->dropTable('tandatangans');
    }
}
