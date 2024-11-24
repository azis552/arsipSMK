<?php

namespace App\Models;

use CodeIgniter\Model;

class Surats extends Model
{
    protected $table            = 'surats';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['jenis_surat', 'nomor_surat', 'tanggal_surat', 'tujuan_surat', 'perihal_surat', 'isi_surat','document','created_at', 'updated_at'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['logAction'];
    protected $afterInsert    = ['logAction'];
    protected $beforeUpdate   = ['logAction'];
    protected $afterUpdate    = ['logAction'];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function logAction(array $data)
    {
        $action = '';

        if (isset($data['id']) && $this->db->affectedRows() > 0) {
            $action = isset($data['primaryKey']) ? 'update' : 'insert';
        } elseif (isset($data['result']) && $data['result'] === true) {
            $action = 'delete';
        }

        $logMessage = sprintf(
            '%s: Tabel "%s", ID Record: %s',
            ucfirst($action),
            $this->table,
            isset($data['id']) ? (is_array($data['id']) ? json_encode($data['id']) : $data['id']) : 'No ID'
        );

        // Simpan log
        $db = \Config\Database::connect();
        $db->table('logs')->insert([
            'user_id' => session()->get('user_id'), // Ganti sesuai session user
            'log'     => $logMessage,
        ]);

        return $data;
    }
}
