<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Grancafé Backoffice</title>

  <!-- FavIcon -->
  <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.png') ?>" type="image/png">

  <!-- Google Font: Source Sans Pro -->
  <link rel=" stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome/css/all.min.css') ?>">
  <!-- Bootstrap -->
  <link rel="stylesheet" href="<?= base_url('assets/plugins/bootstrap/css/bootstrap.min.css') ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url('assets/plugins/adminlte/css/adminlte.min.css') ?>">
  <!-- CSS Aplication -->
  <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>

<body class="responsive-background hold-transition login-page img-fluid" style="background-image: url(<?= base_url('assets/images/background.jpg') ?>);">

    <?= $this->renderSection('content') ?>

  <!-- jQuery -->
  <script src=" <?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
  <!-- Bootstrap 5 -->
  <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <!-- AdminLTE App -->
  <script src="<?= base_url('assets/plugins/adminlte/js/adminlte.min.js') ?>"></script>
</body>

</html>