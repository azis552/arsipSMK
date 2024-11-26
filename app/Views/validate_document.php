<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Validasi Dokumen</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Hasil Validasi Dokumen</h2>

        <div class="alert <?= $result === 'Dokumen valid' ? 'alert-success' : 'alert-danger' ?>">
            <?= $result ?>
        </div>

        <a href="<?= base_url('uploadValidation') ?>" class="btn btn-secondary">Kembali</a>
    </div>
</body>
</html>
