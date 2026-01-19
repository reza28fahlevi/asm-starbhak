<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Register - PKM Asset Management</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="<?= base_url('assets/img/favicon.png') ?>" rel="icon">
  <link href="<?= base_url('assets/img/apple-touch-icon.png') ?>" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="<?= base_url('assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">
  <link href="<?= base_url('assets/vendor/bootstrap-icons/bootstrap-icons.css') ?>" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px 0;
    }
    .register-card {
      border-radius: 15px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }
    .register-logo {
      text-align: center;
      margin-bottom: 25px;
    }
    .register-logo h1 {
      font-size: 26px;
      font-weight: 700;
      color: #012970;
      margin: 0;
    }
    .register-logo p {
      font-size: 13px;
      color: #6c757d;
    }
  </style>
</head>

<body>

  <main>
    <div class="container">
      <section class="section register d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 d-flex flex-column align-items-center justify-content-center">

              <div class="card mb-3 register-card">

                <div class="card-body">

                  <div class="register-logo pt-4 pb-2">
                    <h1>Registrasi Akun Baru</h1>
                    <p>PKM Asset Management System</p>
                  </div>

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

                  <form class="row g-3 needs-validation" method="post" action="<?= base_url('auth/doRegister') ?>" novalidate>
                    <?= csrf_field() ?>

                    <div class="col-md-6">
                      <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                      <input type="text" name="username" class="form-control" id="username" required value="<?= old('username') ?>">
                      <div class="invalid-feedback">Username harus diisi.</div>
                    </div>

                    <div class="col-md-6">
                      <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                      <input type="email" name="email" class="form-control" id="email" required value="<?= old('email') ?>">
                      <div class="invalid-feedback">Email harus valid.</div>
                    </div>

                    <div class="col-12">
                      <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                      <input type="text" name="nama" class="form-control" id="nama" required value="<?= old('nama') ?>">
                      <div class="invalid-feedback">Nama lengkap harus diisi.</div>
                    </div>

                    <div class="col-md-6">
                      <label for="nohp" class="form-label">No. HP</label>
                      <input type="text" name="nohp" class="form-control" id="nohp" value="<?= old('nohp') ?>">
                    </div>

                    <div class="col-md-6">
                      <label for="nomor_registrasi" class="form-label">Nomor Registrasi</label>
                      <input type="text" name="nomor_registrasi" class="form-control" id="nomor_registrasi" value="<?= old('nomor_registrasi') ?>">
                    </div>

                    <div class="col-12">
                      <label for="departemen_id" class="form-label">Departemen</label>
                      <select name="departemen_id" class="form-select" id="departemen_id">
                        <option value="">Pilih Departemen</option>
                        <option value="1" <?= old('departemen_id') == '1' ? 'selected' : '' ?>>IT</option>
                        <option value="2" <?= old('departemen_id') == '2' ? 'selected' : '' ?>>Finance</option>
                        <option value="3" <?= old('departemen_id') == '3' ? 'selected' : '' ?>>HR</option>
                        <option value="4" <?= old('departemen_id') == '4' ? 'selected' : '' ?>>Operations</option>
                      </select>
                    </div>

                    <div class="col-md-6">
                      <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                      <input type="password" name="password" class="form-control" id="password" required>
                      <div class="invalid-feedback">Password minimal 6 karakter.</div>
                    </div>

                    <div class="col-md-6">
                      <label for="confirm_password" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                      <input type="password" name="confirm_password" class="form-control" id="confirm_password" required>
                      <div class="invalid-feedback">Password harus sama.</div>
                    </div>

                    <div class="col-12">
                      <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <small>Akun Anda akan diaktifkan setelah disetujui oleh administrator.</small>
                      </div>
                    </div>
                    
                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Daftar</button>
                    </div>
                    
                    <div class="col-12">
                      <p class="small mb-0 text-center">Sudah punya akun? <a href="<?= base_url('/') ?>">Login di sini</a></p>
                    </div>
                  </form>

                </div>
              </div>

              <div class="credits text-white text-center">
                <small>&copy; 2026 PKM Asset Management System</small>
              </div>

            </div>
          </div>
        </div>

      </section>

    </div>
  </main>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

  <!-- Template Main JS File -->
  <script src="<?= base_url('assets/js/main.js') ?>"></script>

</body>

</html>
