<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSuratsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'jenis_surat' => [
                'type' => 'ENUM',
                'constraint' => ['Surat Masuk', 'Surat Keluar'],
            ],
            'nomor_surat' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'tanggal_surat' => [
                'type' => 'DATE',
            ],
            'tujuan_surat' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'perihal_surat' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'isi_surat' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'document' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'null' => true,
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
        $this->forge->createTable('surats');
    }

    public function down()
    {
        $this->forge->dropTable('surats');
    }
}
