<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi PDF - Metadata</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Validasi PDF - Metadata</h2>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <form action="<?= base_url('validatePdf') ?>" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="pdf_file" class="form-label">Unggah PDF</label>
                <input type="file" name="pdf_file" id="pdf_file" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Validasi</button>
        </form>
    </div>
</body>
</html>
