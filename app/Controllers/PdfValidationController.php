<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use setasign\Fpdi\Fpdi;


class PdfValidationController extends BaseController
{


    // Halaman untuk upload PDF
    public function uploadValidationPage()
    {
        return view('upload_pdf_validation');
    }

    // Validasi PDF berdasarkan metadata
    public function validatePdf()
    {
        // Validasi file input
        $file = $this->request->getFile('pdf_file');
        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid atau tidak ada file yang diunggah.');
        }

        $filePath = FCPATH . 'writable/uploads/temp/';
        $simpan = $file->move($filePath, $file->getName());
        var_dump($simpan);die();
        // Menggunakan realpath() untuk mendapatkan jalur absolut
        $absolutePath = realpath($filePath);
        // Baca metadata PDF menggunakan PDFParser
        $parser = new Parser();
        $pdf = $parser->parseFile($absolutePath);

        // Ambil metadata dari dokumen PDF
        $details = $pdf->getDetails();
        unlink($filePath); // Hapus file sementara

        // Periksa apakah metadata Keywords ada
        $documentHash = $details['Keywords'] ?? null;
        if (!$documentHash) {
            return redirect()->back()->with('error', 'Metadata hash tidak ditemukan.');
        }

        // Hash ulang dokumen
        $calculatedHash = hash_file('sha256', $filePath);

        // Cocokkan hash metadata dengan hash dokumen
        if ($calculatedHash === $documentHash) {
            return view('validate_document', [
                'result' => 'Dokumen valid',
            ]);
        } else {
            return view('validate_document', [
                'result' => 'Dokumen tidak valid atau telah dimodifikasi',
            ]);
        }
    }
}
