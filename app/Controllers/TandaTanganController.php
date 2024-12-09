<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TandaTangan;
use CodeIgniter\HTTP\ResponseInterface;

class TandaTanganController extends BaseController
{
    public function index()
    {
        $TandatanganModel = new TandaTangan();
        $ttd = $TandatanganModel->db->table('tandatangans')->where('id_pegawai', session()->get('user_id'))->orderBy('id', 'DESC')->get()->getFirstRow();
        return view('tandatangan/index', ['ttd' => $ttd]);
    }
    public function store()
    {
    $idPegawai = $this->request->getPost('id_pegawai');
    $signatureImage = $this->request->getPost('signature_image');

    if (!$signatureImage) {
        return redirect()->back()->with('error', 'Tanda tangan tidak boleh kosong.');
    }

    // Pisahkan metadata Base64
    list($type, $data) = explode(';', $signatureImage);
    list(, $data) = explode(',', $data);

    // Decode Base64 ke binary
    $decodedImage = base64_decode($data);

    // Simpan sebagai file
    $fileName = uniqid() . '.png';
    file_put_contents(FCPATH . 'writable/ttd/' . $fileName, $decodedImage);

    // Simpan informasi ke database
    $data = [
        'id_pegawai' => session()->get('user_id'),
        'ttd' => $fileName, // Simpan nama file ke database
    ];

    $TandatanganModel = new TandaTangan();
    $TandatanganModel->save($data);

    return redirect()->back()->with('success', 'Tanda tangan berhasil disimpan.');
    }
}
