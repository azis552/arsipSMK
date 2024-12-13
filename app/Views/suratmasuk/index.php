<?= $this->extend('layout/master'); ?>

<?= $this->section('content'); ?>
<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Upload File</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="/suratmasuk/uploadfile" enctype="multipart/form-data" method="post">
          <div class="form-group">
            <input type="hidden" name="id_surat" id="id_surat" class="form-control">
            <label for="file_surat">File Surat</label>
            <input type="file" name="file_surat" class="form-control">
          </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Upload</button>
      </div>
      </form>
    </div>
  </div>
</div>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Surat Masuk</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Surat Masuk</a></li>
            <li class="breadcrumb-item active">Index</li>
          </ol>
        </div>
        <div class="col">
          <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h5><i class="icon fas fa-check"></i> Success! <?= session()->getFlashdata('success'); ?></h5>
            </div>
          <?php endif; ?>
          <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible"></div>
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check"></i> Error! <?= session()->getFlashdata('error'); ?></h5>
        </div>
      <?php endif; ?>
      </div>
    </div>
</div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Data Surat Masuk</h3>

      <div class="card-tools">
        <a href="<?= base_url('suratmasuk/create') ?>" class="btn btn-tool">
          <i class="fas fa-plus"></i>
        </a>
        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
          <i class="fas fa-minus"></i>
        </button>
        <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>
    <div class="card-body">
      <table id="example1" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Id</th>
            <th>No Surat</th>
            <th>Tanggal Surat</th>
            <th>Tujuan Surat</th>
            <th>Perihal</th>
            <th>File</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 1; ?>
          <?php foreach ($surats as $surat) : ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><?= $surat['nomor_surat'] ?></td>
              <td><?= $surat['tanggal_surat'] ?></td>
              <td><?= $surat['name'] ?></td>
              <td><?= $surat['perihal_surat'] ?></td>
              <td>
                <?php if ($surat['document_path']) : ?>
                  <?php if(($surat['is_signed']) == 1) { ?>
                    <a target="_blank" href="<?= $surat['document_path'] ?>">Lihat File</a>
                  <?php }else{ ?>
                    <a target="_blank" href="<?= base_url($surat['document_path']) ?>">Lihat File</a>
                  <?php } ?>
                <?php else: ?>
                  <!-- Button trigger modal -->
                  <button data-id="<?= $surat['id'] ?>" type="button" class="btn btn-primary id_surat" data-toggle="modal" data-target="#staticBackdrop">
                    Upload File
                  </button>


                <?php endif; ?>
              </td>
              <td>
                <a target="_blank" href="<?= base_url('suratmasuk/signature/' . $surat['id']) ?>" class="btn btn-info btn-sm">Signature</a>
                <a target="_blank" href="<?= base_url('suratmasuk/qrcode/' . $surat['id']) ?>" class="btn btn-primary btn-sm">QR Code</a>
                <a href="<?= base_url('suratmasuk/edit/' . $surat['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="<?= base_url('suratmasuk/delete/' . $surat['id']) ?>" class="btn btn-danger btn-sm">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- /.card-footer-->
  </div>
  <!-- /.card -->

</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?= $this->endSection(); ?>


<?php $this->section('js'); ?>
<script>
  $(document).ready(function() {
    $('.id_surat').click(function() {
      var id = $(this).data('id');
      $('#id_surat').val(id);
    });
  });
</script>
<?= $this->endSection(); ?>