<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use setasign\Fpdi\Fpdi;
use Smalot\PdfParser\Parser;

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
        // var_dump($simpan);die;
        // Menggunakan realpath() untuk mendapatkan jalur absolut
        $absolutePath = realpath($filePath.$file->getName());
        // Baca metadata PDF menggunakan PDFParser
        $parser = new Parser();
        $pdf = $parser->parseFile($absolutePath);

        // Ambil metadata dari dokumen PDF
        $details = $pdf->getDetails();
        // Path Private Key
        $privateKeyPath = FCPATH . 'writable/keys/private.key';
        $privateKeyPathcontent = file_get_contents($privateKeyPath);
        // Periksa apakah metadata Keywords ada
        $documentHash = $details['Keywords'] ?? null;
        if (!$documentHash) {
            return redirect()->back()->with('error', 'Metadata hash tidak ditemukan.');
        }else{
            // Cocokkan hash metadata dengan hash dokumen
            if ( $documentHash == $privateKeyPathcontent) {
                return view('validate_document', [
                    'result' => 'Dokumen valid',
                ]);
            } else {
                return view('validate_document', [
                    'result' => 'Dokumen tidak valid atau telah dimodifikasi',
                ]);
            }
        }
        

        // validasi
        

        
        unlink($absolutePath); // Hapus file sementara

        
    }
}
