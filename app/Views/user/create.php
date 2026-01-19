<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row">
  <div class="col-lg-8">

    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Form Tambah User</h5>

        <?php if (session()->getFlashdata('errors')): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-octagon me-1"></i>
            <ul class="mb-0">
              <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
              <?php endforeach; ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>

        <!-- General Form Elements -->
        <form method="post" action="<?= base_url('user/store') ?>">
          <?= csrf_field() ?>

          <div class="row mb-3">
            <label for="username" class="col-sm-3 col-form-label">Username <span class="text-danger">*</span></label>
            <div class="col-sm-9">
              <input type="text" name="username" class="form-control" id="username" required value="<?= old('username') ?>">
            </div>
          </div>

          <div class="row mb-3">
            <label for="email" class="col-sm-3 col-form-label">Email <span class="text-danger">*</span></label>
            <div class="col-sm-9">
              <input type="email" name="email" class="form-control" id="email" required value="<?= old('email') ?>">
            </div>
          </div>

          <div class="row mb-3">
            <label for="nama" class="col-sm-3 col-form-label">Nama Lengkap <span class="text-danger">*</span></label>
            <div class="col-sm-9">
              <input type="text" name="nama" class="form-control" id="nama" required value="<?= old('nama') ?>">
            </div>
          </div>

          <div class="row mb-3">
            <label for="nohp" class="col-sm-3 col-form-label">No. HP</label>
            <div class="col-sm-9">
              <input type="text" name="nohp" class="form-control" id="nohp" value="<?= old('nohp') ?>">
            </div>
          </div>

          <div class="row mb-3">
            <label for="nomor_registrasi" class="col-sm-3 col-form-label">Nomor Registrasi</label>
            <div class="col-sm-9">
              <input type="text" name="nomor_registrasi" class="form-control" id="nomor_registrasi" value="<?= old('nomor_registrasi') ?>">
            </div>
          </div>

          <div class="row mb-3">
            <label for="departemen_id" class="col-sm-3 col-form-label">Departemen</label>
            <div class="col-sm-9">
              <select name="departemen_id" class="form-select" id="departemen_id">
                <option value="">Pilih Departemen</option>
                <option value="1" <?= old('departemen_id') == '1' ? 'selected' : '' ?>>IT</option>
                <option value="2" <?= old('departemen_id') == '2' ? 'selected' : '' ?>>Finance</option>
                <option value="3" <?= old('departemen_id') == '3' ? 'selected' : '' ?>>HR</option>
                <option value="4" <?= old('departemen_id') == '4' ? 'selected' : '' ?>>Operations</option>
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <label for="password" class="col-sm-3 col-form-label">Password <span class="text-danger">*</span></label>
            <div class="col-sm-9">
              <input type="password" name="password" class="form-control" id="password" required>
              <small class="text-muted">Minimal 6 karakter</small>
            </div>
          </div>

          <div class="row mb-3">
            <label for="confirm_password" class="col-sm-3 col-form-label">Konfirmasi Password <span class="text-danger">*</span></label>
            <div class="col-sm-9">
              <input type="password" name="confirm_password" class="form-control" id="confirm_password" required>
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Status</label>
            <div class="col-sm-9">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="active" id="active" value="1" <?= old('active') ? 'checked' : '' ?>>
                <label class="form-check-label" for="active">
                  Aktif
                </label>
              </div>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-sm-9 offset-sm-3">
              <button type="submit" class="btn btn-primary">Simpan</button>
              <a href="<?= base_url('user') ?>" class="btn btn-secondary">Batal</a>
            </div>
          </div>

        </form><!-- End General Form Elements -->

      </div>
    </div>

  </div>
</div>

<?= $this->endSection() ?>
