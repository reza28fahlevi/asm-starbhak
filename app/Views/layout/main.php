<?= $this->include('layout/header') ?>

<?= $this->include('layout/sidebar') ?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1><?= $page_title ?? 'Dashboard' ?></h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Home</a></li>
        <?php if (isset($breadcrumbs) && is_array($breadcrumbs)): ?>
          <?php foreach ($breadcrumbs as $index => $breadcrumb): ?>
            <?php if ($index == count($breadcrumbs) - 1): ?>
              <li class="breadcrumb-item active"><?= $breadcrumb ?></li>
            <?php else: ?>
              <li class="breadcrumb-item"><?= $breadcrumb ?></li>
            <?php endif; ?>
          <?php endforeach; ?>
        <?php else: ?>
          <li class="breadcrumb-item active"><?= $page_title ?? 'Dashboard' ?></li>
        <?php endif; ?>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section">
    <?= $this->renderSection('content') ?>
  </section>

</main><!-- End #main -->

<?= $this->include('layout/footer') ?>
