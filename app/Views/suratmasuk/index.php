
<?= $this->extend('layout/master'); ?>

<?= $this->section('content'); ?>
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
            <?php if(session()->getFlashdata('success')): ?>
              <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-check"></i> Success! <?= session()->getFlashdata('success'); ?></h5>
              </div>
            <?php endif; ?>
            <?php if(session()->getFlashdata('error')): ?>
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
          <a href="<?= base_url('users/create') ?>" class="btn btn-tool" >
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
                <td><?= $surat['tujuan_surat'] ?></td>
                <td><?= $surat['perihal'] ?></td>
                <td>
                  <a href="<?= base_url('users/edit/'.$surat['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                  <a href="<?= base_url('users/delete/'.$surat['id']) ?>" class="btn btn-danger btn-sm">Delete</a>
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