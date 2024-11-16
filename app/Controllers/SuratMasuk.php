<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Dokumens;
use App\Models\Surats;
use App\Models\Users;
use CodeIgniter\HTTP\ResponseInterface;

class SuratMasuk extends BaseController
{
    public function index()
    {
        $suratModel = new Surats();
        $suratModel = $suratModel->where('jenis_surat', 'Surat Masuk')->findAll();
        return view('suratmasuk/index', ['surats' => $suratModel]);
    }

    public function create()
    {
        $userModel = new Users();
        $users = $userModel->findAll();
        return view('suratmasuk/create', ['users' => $users]);
    }

    public function store()
    {
        // var_dump($this->request->getPost());die();
        $validasi = $this->validate(
            [
                'nomor_surat' => 'required',
                'tanggal_surat' => 'required',
                'tujuan_surat' => 'required',
                'perihal_surat' => 'required',
                'isi_surat' => 'required',
            ]
        );
        

        if(!$validasi){
            return redirect()->to('/suratmasuk/create')->withInput()->with('errors', $this->validator->getErrors());
        }

        $file = $this->request->getFile('file_surat');
        $newNama = 'suratMasuk_' . time() . '.' . $file->getExtension();
        if ($file->isValid() && !$file->hasMoved()) {
            $filePath = 'writable/uploads/';
            $simpan = $file->move($filePath, $newNama);
        } else {
            return redirect()->to('/suratmasuk/create')->withInput()->with('error', 'File Upload Gagal diupload');
        }

        $Dokumen = new Dokumens();
        $Dokumen->save([
            'name' => $newNama,
            'document_path' => $filePath . $newNama,
            'upload_by' => session()->get('user_id'),
        ]);

        $suratModel = new Surats();
        $suratModel->save([
            'nomor_surat' => $this->request->getPost('nomor_surat'),
            'tanggal_surat' => $this->request->getPost('tanggal_surat'),
            'tujuan_surat' => $this->request->getPost('tujuan_surat'),
            'perihal_surat' => $this->request->getPost('perihal_surat'),
            'isi_surat' => $this->request->getPost('isi_surat'),
            'document' => $Dokumen->id,
            'jenis_surat' => 'Surat Masuk'
        ]);
        return redirect()->to('/suratmasuk')->with('success', 'Berhasil tambah surat masuk');
    }
}
