<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Dokumens;
use App\Models\Qrcode as ModelsQrcode;
use App\Models\Signatures;
use App\Models\Surats;
use App\Models\Users;
use CodeIgniter\HTTP\ResponseInterface;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\TcpdfFpdi;
use TCPDF;

class SuratMasuk extends BaseController
{
    public function index()
    {
        $suratModel = new Surats();
        $suratModel = $suratModel->db->table('surats')
            ->where('surats.jenis_surat', 'Surat Masuk')
            ->join('users', 'surats.tujuan_surat = users.id', 'left')
            ->join('dokumens', 'surats.document = dokumens.id', 'left')
            ->select('surats.*, users.name as name, dokumens.document_path, dokumens.is_signed ')
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResultArray(); // Ambil data sebagai array
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
        if (!$validasi) {
            return redirect()->to('/suratmasuk/create')->withInput()->with('errors', $this->validator->getErrors());
        }
        $file = $this->request->getFile('file_surat');
        if ($file->isValid()) {
            $newNama = 'suratMasuk_' . time() . '.' . $file->getExtension();
            if ($file->isValid() && !$file->hasMoved()) {
                $filePath = 'writable/uploads/';
                $simpan = $file->move($filePath, $newNama);
                $Dokumen = new Dokumens();
                $Dokumen->save([
                    'name' => $newNama,
                    'document_path' => $filePath . $newNama,
                    'upload_by' => session()->get('user_id'),
                ]);
            } else {
                return redirect()->to('/suratmasuk/create')->withInput()->with('error', 'File Upload Gagal diupload');
            }
        }
        $suratModel = new Surats();
        if (isset($Dokumen)) {
            $suratModel->save([
                'nomor_surat' => $this->request->getPost('nomor_surat'),
                'tanggal_surat' => $this->request->getPost('tanggal_surat'),
                'tujuan_surat' => $this->request->getPost('tujuan_surat'),
                'perihal_surat' => $this->request->getPost('perihal_surat'),
                'isi_surat' => $this->request->getPost('isi_surat'),
                'document' => $Dokumen->getInsertID(),
                'jenis_surat' => 'Surat Masuk',
                'created_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            $suratModel->save([
                'nomor_surat' => $this->request->getPost('nomor_surat'),
                'tanggal_surat' => $this->request->getPost('tanggal_surat'),
                'tujuan_surat' => $this->request->getPost('tujuan_surat'),
                'perihal_surat' => $this->request->getPost('perihal_surat'),
                'isi_surat' => $this->request->getPost('isi_surat'),

                'jenis_surat' => 'Surat Masuk'
            ]);
        }
        return redirect()->to('/suratmasuk')->with('success', 'Berhasil tambah surat masuk');
    }

    public function uploadfile()
    {
        $id = $this->request->getPost('id_surat');
        $file = $this->request->getFile('file_surat');
        $newNama = 'suratMasuk_' . time() . '.' . $file->getExtension();
        if ($file->isValid() && !$file->hasMoved()) {
            $filePath = 'writable/uploads/';
            $simpan = $file->move($filePath, $newNama);
        } else {
            return redirect()->to('/suratmasuk/index')->withInput()->with('error', 'File Upload Gagal diupload');
        }

        $Dokumen = new Dokumens();
        $Dokumen->save([
            'name' => $newNama,
            'document_path' => $filePath . $newNama,
            'upload_by' => session()->get('user_id'),
        ]);
        $suratModel = new Surats();
        $suratModel->update($id, ['document' => $Dokumen->getInsertID()]);
        return redirect()->to('/suratmasuk')->with('success', 'Berhasil tambah file surat masuk');
    }

    public function edit($id)
    {
        $userModel = new Users();
        $users = $userModel->findAll();
        $suratModel = new Surats();
        $surat = $suratModel->find($id);
        return view('suratmasuk/edit', ['users' => $users, 'suratmasuk' => $surat]);
    }
    public function update($id)
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


        if (!$validasi) {
            return redirect()->to('/suratmasuk/edit/' . $id)->withInput()->with('errors', $this->validator->getErrors());
        }

        $file = $this->request->getFile('file_surat');
        if ($file->isValid()) {
            $newNama = 'suratMasuk_' . time() . '.' . $file->getExtension();
            if ($file->isValid() && !$file->hasMoved()) {
                $filePath = 'writable/uploads/';
                $simpan = $file->move($filePath, $newNama);
                $Dokumen = new Dokumens();
                $Dokumen->save([
                    'name' => $newNama,
                    'document_path' => $filePath . $newNama,
                    'upload_by' => session()->get('user_id'),
                ]);
            } else {
                return redirect()->to('/suratmasuk/create')->withInput()->with('error', 'File Upload Gagal diupload');
            }
        }
        $suratModel = new Surats();

        if (isset($Dokumen)) {
            $suratModel->update($id, [
                'nomor_surat' => $this->request->getPost('nomor_surat'),
                'tanggal_surat' => $this->request->getPost('tanggal_surat'),
                'tujuan_surat' => $this->request->getPost('tujuan_surat'),
                'perihal_surat' => $this->request->getPost('perihal_surat'),
                'isi_surat' => $this->request->getPost('isi_surat'),
                'document' => $Dokumen->getInsertID(),
                'jenis_surat' => 'Surat Masuk'
            ]);
        } else {
            $suratModel->update($id, [
                'nomor_surat' => $this->request->getPost('nomor_surat'),
                'tanggal_surat' => $this->request->getPost('tanggal_surat'),
                'tujuan_surat' => $this->request->getPost('tujuan_surat'),
                'perihal_surat' => $this->request->getPost('perihal_surat'),
                'isi_surat' => $this->request->getPost('isi_surat'),
                'jenis_surat' => 'Surat Masuk'
            ]);
        }
        return redirect()->to('/suratmasuk')->with('success', 'Berhasil update surat masuk');
    }

    public function delete($id)
    {
        $suratModel = new Surats();
        $id_document = $suratModel->find($id)['document'];
        if ($id_document != null) {
            $suratModel->delete($id);
            $dokumenModel = new Dokumens();
            $dokumenModel->delete($id_document);
        } else {
            $suratModel->delete($id);
        }
        return redirect()->to('/suratmasuk')->with('success', 'Berhasil hapus surat masuk');
    }
    public function framesignature($id)
    {
        // Halaman utama yang memuat iframe untuk PDF Viewer
        return view('suratmasuk/framesignature', ['id' => $id]);
    }
    // Tampilkan halaman signature
    public function signature($id)
    {
        $suratModel = new Surats();
        $surat = $suratModel->join('dokumens', 'surats.document = dokumens.id', 'left')
            ->find($id);


        if (!$surat) {
            return redirect()->to('/surat')->with('error', 'Surat tidak ditemukan.');
        }

        // Path file PDF
        $pdfPath = FCPATH . 'writable/uploads/' . $surat['name'];
        $documentHash = hash_file('sha256', $pdfPath);

        // Generate QR Code
        $qrCodePath = FCPATH . 'writable/qrcodes/' . uniqid() . '.png';
        $this->generateQrCode("Document Hash: $documentHash", $qrCodePath);

        $id_dokumen = $suratModel
            ->join('dokumens', 'surats.document = dokumens.id')
            ->select('dokumens.id')
            ->where('surats.id', $id)
            ->get()
            ->getRowArray();

        $qrcode = new ModelsQrcode();
        $qrcode->save([
            'document_id' => $id_dokumen,
            'qrcode_path' => basename($qrCodePath)
        ]);
        

        return view('/suratmasuk/signature', [
            'id' => $id,
            'pdfPath' => base_url('writable/uploads/' . $surat['name']),
            'qrCodePath' => base_url('writable/qrcodes/' . basename($qrCodePath)),
            'pdfPathBack' => FCPATH . 'writable/uploads/' . $surat['name'],
            'qrCodePathBack' => FCPATH . 'writable/qrcodes/' . basename($qrCodePath),
        ]);
    }

    // Simpan hasil koordinat dan tanda tangan
    public function saveCoordinates()
    {
        $id = $this->request->getPost('id'); // ID dokumen
        $x = $this->request->getPost('x'); // Koordinat X
        $y = $this->request->getPost('y'); // Koordinat Y
        $page = $this->request->getPost('page'); // Halaman PDF yang dimaksud
        $pdfPath = $this->request->getPost('pdfPath'); // Path file PDF asli
        $qrCodePath = $this->request->getPost('qrCodePath'); // Path file QR Code

        // Path hasil PDF setelah QR Code
        $outputPathWithQr = FCPATH . 'writable/signed/' . uniqid() . '-with-qrcode.pdf';
        $this->embedQrCodeInPdf($pdfPath, $qrCodePath, $outputPathWithQr, $x, $y, $page);

        // Path Private Key
        $privateKeyPath = FCPATH . 'writable/keys/private.key';

        // Generate tanda tangan digital
        $signature = $this->signDocument($outputPathWithQr, $privateKeyPath);

        // Path hasil akhir
        $outputPathSigned = FCPATH . 'writable/signed/' . uniqid() . '-signed.pdf';
        $this->embedSignatureInPdf($outputPathWithQr, $signature, $outputPathSigned);

        // Update database
        $suratModel = new Surats();
        $id_dokumen = $suratModel
            ->join('dokumens', 'surats.document = dokumens.id')
            ->select('dokumens.id')
            ->where('surats.id', $id)
            ->get()
            ->getRowArray();
        $dokumenModel = new Dokumens();
        $dokumenModel->update($id_dokumen, [
            'document_path' => base_url('writable/signed/' . basename($outputPathSigned)),
            'verified_by' => session()->get('user_id'),
            'verified_at' => date('Y-m-d H:i:s'),
            'is_signed' => 1,
        ]);
        $signature = new Signatures();
        $data = [
            'document_id' => intval($id_dokumen['id']),
            'signature_data' => $signature,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        return redirect()->to('/suratmasuk')->with('success', 'Signature berhasil disimpan.');
    }

    // Generate QR Code
    private function generateQrCode($data, $outputPath)
    {
        $qrCode = new QrCode($data);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $result->saveToFile($outputPath);
    }

    // Embed QR Code into PDF
    private function embedQRCodeInPDF($pdfPath, $qrCodePath, $outputPath, $x_pixel, $y_pixel, $page)
    {
        // Konversi koordinat dari piksel ke milimeter (1 px = 25.4 mm / 72 px)
        $dpi = 72; // Default DPI
        $x_mm = ((float)$x_pixel * 25.4) / $dpi; // Konversi x koordinat
        $y_mm = ((float)$y_pixel * 25.4) / $dpi; // Konversi y koordinat
        // Proses FPDI untuk menambahkan QR Code ke PDF
        $fpdi = new Fpdi();
        $pageCount = $fpdi->setSourceFile($pdfPath);

        for ($i = 1; $i <= $pageCount; $i++) {
            $templateId = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($templateId);

            $fpdi->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $fpdi->useTemplate($templateId);

            // Tambahkan QR Code hanya pada halaman yang sesuai
            if ($i == $page) {
                if (file_exists($qrCodePath)) {
                    // Menambahkan QR Code pada posisi yang sudah dihitung (dalam milimeter)
                    $fpdi->Image($qrCodePath, $x_mm, $y_mm, 30, 30); // Sesuaikan ukuran QR Code sesuai kebutuhan
                } else {
                    throw new Exception("QR Code file not found: " . $qrCodePath);
                }
            }
        }

        // Simpan PDF hasil modifikasi
        $fpdi->Output($outputPath, 'F');
    }


    // Sign document using OpenSSL
    private function signDocument($documentPath, $privateKeyPath)
    {
        $documentContent = file_get_contents($documentPath);
        $documentHash = hash('sha256', $documentContent, true);
        $privateKey = openssl_pkey_get_private(file_get_contents($privateKeyPath));

        if (!$privateKey) {
            throw new \Exception('Failed to load Private Key.');
        }

        $signature = null;
        openssl_sign($documentHash, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        openssl_free_key($privateKey);

        return base64_encode($signature);
    }

    // Embed signature into PDF metadata
    private function embedSignatureInPdf($pdfPath, $signature, $outputPath)
    {
        $pdf = new Fpdi();
        $pdf->setSourceFile($pdfPath);
        $pageCount = $pdf->setSourceFile($pdfPath); // Hitung jumlah halaman di PDF asli

        // Loop untuk setiap halaman
        for ($page = 1; $page <= $pageCount; $page++) {
            $template = $pdf->importPage($page); // Impor halaman
            $pdf->AddPage(); // Tambahkan halaman baru
            $pdf->useTemplate($template); // Gunakan template halaman
        }

        // Add metadata for signature
        $pdf->SetTitle("Document with Digital Signature");
        $pdf->SetKeywords($signature);
        $pdf->Output($outputPath, 'F');
    }
}
