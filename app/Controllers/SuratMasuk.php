<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Surats;
use CodeIgniter\HTTP\ResponseInterface;

class SuratMasuk extends BaseController
{
    public function index()
    {
        $suratModel = new Surats();
        $suratModel = $suratModel->where('jenis_surat', 'Surat Masuk')->findAll();
        return view('suratmasuk/index',['surats' => $suratModel]);
    }
}
