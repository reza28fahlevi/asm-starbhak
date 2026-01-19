  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link <?= uri_string() == '' ? '' : 'collapsed' ?>" href="<?= base_url('/') ?>">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

      <li class="nav-heading">Asset</li>

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#asset-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-box-seam"></i><span>Asset Management</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="asset-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
          <li>
            <a href="<?= base_url('/permohonan-asset') ?>">
              <i class="bi bi-circle"></i><span>Permohonan Asset</span>
            </a>
          </li>
          <li>
            <a href="<?= base_url('/approval-permohonan') ?>">
              <i class="bi bi-circle"></i><span>Approval Permohonan</span>
            </a>
          </li>
          <li>
            <a href="<?= base_url('/pembelian-asset') ?>">
              <i class="bi bi-circle"></i><span>Pembelian Asset</span>
            </a>
          </li>
          <li>
            <a href="<?= base_url('/penerimaan-asset') ?>">
              <i class="bi bi-circle"></i><span>Penerimaan Barang</span>
            </a>
          </li>
          <li>
            <a href="<?= base_url('/distribusi-asset') ?>">
              <i class="bi bi-circle"></i><span>Distribusi Asset</span>
            </a>
          </li>
        </ul>
      </li><!-- End Asset Management Nav -->

      <li class="nav-heading">Configuration</li>

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#config-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-gear"></i><span>Configuration</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="config-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
          <li>
            <a href="<?= base_url('/user') ?>">
              <i class="bi bi-circle"></i><span>User Management</span>
            </a>
          </li>
          <li>
            <a href="<?= base_url('/role') ?>">
              <i class="bi bi-circle"></i><span>Role Management</span>
            </a>
          </li>
          <li>
            <a href="<?= base_url('/departemen') ?>">
              <i class="bi bi-circle"></i><span>Departemen</span>
            </a>
          </li>
          <li>
            <a href="<?= base_url('/supplier') ?>">
              <i class="bi bi-circle"></i><span>Supplier</span>
            </a>
          </li>
          <li>
            <a href="<?= base_url('/lokasi') ?>">
              <i class="bi bi-circle"></i><span>Lokasi</span>
            </a>
          </li>
          <li>
            <a href="<?= base_url('/kategori-asset') ?>">
              <i class="bi bi-circle"></i><span>Kategori Asset</span>
            </a>
          </li>
          <li>
            <a href="<?= base_url('/kelompok-asset') ?>">
              <i class="bi bi-circle"></i><span>Kelompok Asset</span>
            </a>
          </li>
          <li>
            <a href="<?= base_url('/menu') ?>">
              <i class="bi bi-circle"></i><span>Menu</span>
            </a>
          </li>
        </ul>
      </li><!-- End Configuration Nav -->

      <hr>

      <li class="nav-item">
        <a class="nav-link collapsed" href="#">
          <i class="bi bi-box-arrow-in-right"></i>
          <span>Login</span>
        </a>
      </li><!-- End Login Page Nav -->

    </ul>

  </aside><!-- End Sidebar-->
