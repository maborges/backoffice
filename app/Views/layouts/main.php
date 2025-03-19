<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Grancafé Back Office<?= !empty($title) ? '-' . $title : '' ?></title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome/css/all.min.css') ?>">

  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

  <!-- Toastr -->
  <link rel="stylesheet" href="<?= base_url('assets/plugins/toastr/toastr.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/plugins/bootstrap/css/bootstrap.min.css') ?>">

  <!-- AdminLte -->
  <link rel="stylesheet" href="<?= base_url('assets/plugins/adminlte/css/adminlte.min.css') ?>">

  <?php if (!empty($datatables)) : ?>
    <!-- dataTables -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/datatables.min.css') ?>">

    <!-- dataTables autoFill -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/AutoFill/autoFill.dataTables.min.css') ?>">

    <!-- dataTables Buttons -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/buttons/buttons.dataTables.min.css') ?>">

    <!-- dataTables colreorder -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/colReorder/colReorder.dataTables.min.css') ?>">

    <!-- dataTables fixedHeader -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/fixedheader/fixedHeader.dataTables.min.css') ?>">

    <!-- SearchBuilder  -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/searchBuilder/searchBuilder.dataTables.min.css') ?>">

    <!-- bootstrap  
    <link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/bootstrap/css/dataTables.bootstrap5.min.css') ?>">
    -->
  <?php endif; ?>

  <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">

  <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.png') ?>" type="image/png">

  <!-- jquery -->
  <script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
  <script src="<?= base_url('assets/plugins/jquery/jquery-ui.min.js') ?>"></script>
  <link rel="stylesheet" href="<?= base_url('assets/plugins/jquery/jquery-ui.min.css') ?>">

  <!-- Toastr -->
  <script src="<?= base_url('assets/plugins/toastr/toastr.min.js') ?>"></script>

  <!-- SweetAlert -->
  <script src="<?= base_url('assets/plugins/sweetalert/sweetalert.min.js') ?>"></script>

  <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

  <!-- ChartJS -->
   <!--
  <script src="<?= base_url('assets/plugins/chart.js/Chart.min.js') ?>"></script>
   <script src="<?= base_url('assets/plugins/chart.js/chartjs-pligin-autocolors') ?>"></script> --> 
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-autocolors"></script>  
  <?php if (!empty($datatables)) : ?>
    <!-- DataTables   -->
    <script src="<?= base_url('assets/plugins/datatables/datatables.min.js') ?>"></script>

    <!-- AutoFill   -->
    <script src="<?= base_url('assets/plugins/datatables/AutoFill/dataTables.autoFill.min.js') ?>"></script>

    <!-- Buttons   -->
    <script src="<?= base_url('assets/plugins/datatables/buttons/dataTables.buttons.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables/buttons/buttons.dataTables.min.js') ?>"></script>

    <!-- Buttons Visibilidade de Colunas  -->
    <script src="<?= base_url('assets/plugins/datatables/buttons/buttons.colVis.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables/buttons/buttons.html5.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables/buttons/buttons.print.min.js') ?>"></script>

    <!-- colReorder  -->
    <script src="<?= base_url('assets/plugins/datatables/colReorder/dataTables.colReorder.min.js') ?>"></script>

    <!-- fixedHeader  -->
    <script src="<?= base_url('assets/plugins/datatables/fixedheader/dataTables.fixedHeader.min.js') ?>"></script>

    <!-- SearchBuilder  -->
    <script src="<?= base_url('assets/plugins/datatables/searchBuilder/searchBuilder.min.js') ?>"></script>

    <!-- bootstrap  
    <script src="<?= base_url('assets/plugins/datatables/bootstrap/js/bootstrap5.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables/bootstrap/js/dataTables.min.js') ?>"></script>
    -->

  <?php endif; ?>

  <script src="<?= base_url('assets/plugins/adminlte/js/adminlte.min.js') ?>"></script>

  <script src="<?= base_url('assets/plugins/moment/moment.min.js') ?>"></script>

</head>

<body class="hold-transition sidebar-mini sidebar-collapse layout-navbar-fixed layout-fixed">
  <div class="wrapper">
    <!--
    <div class="preloader flex-column justify-content-center align-items-center">
      <img class="animation__wobble" src="<?= base_url('assets/images/logo.webp') ?>" alt="logo" height="60" width="60">
    </div>
    -->
    <?= $this->include('partials/nav_bar.php'); ?>
    <?= $this->include('partials/side_bar.php'); ?>

    <div class="container-fluid">
      <div class="content-wrapper">
        <?= $this->renderSection('content') ?>
      </div>
    </div>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->

    <?= $this->include('partials/footer.php'); ?>
  </div>

  <?= $this->renderSection('scripts') ?>
</body>

</html>