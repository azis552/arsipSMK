<?php

namespace App\Controllers;

use App\Models\Surats;
use App\Models\Users;

class Home extends BaseController
{
    public function index(): string
    {
        $User = new Users();
        $surat = new Surats();
        $jumlahUser = $User->countAll();
        // Menghitung jumlah surat masuk dan keluar berdasarkan filter
        $jumlahsuratmasuk = $surat->where('jenis_surat', 'Surat Masuk')->countAllResults();
        $jumlahsuratkeluar = $surat->where('jenis_surat', 'Surat Keluar')->countAllResults();
        $jumlaharsip = $surat->countAll();
        return view('admin/dashboard', ['jumlahUser' => $jumlahUser, 'jumlahsuratmasuk' => $jumlahsuratmasuk, 'jumlahsuratkeluar' => $jumlahsuratkeluar, 'jumlaharsip' => $jumlaharsip]);
    }
}
