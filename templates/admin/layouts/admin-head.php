<?php
$sidebar_path = $data['sidebar_path'];
$navbar_path = $data['navbar_path'];
?>
<!DOCTYPE html>
<html>
  <head>

    <meta charset="utf-8">
    <meta lang="en">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?php echo PROJECT_NAME; ?></title>

    <link href="<?php echo BASE_URL; ?>templates/admin/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>templates/admin/css/admin.css" rel="stylesheet">
    <script src="<?php echo BASE_URL ?>templates/admin/vendor/jquery/jquery.min.js"></script>
    <?php $this->printHead(); ?>
  </head>

  <body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

      <?php $this->loadLayout($sidebar_path, $data); ?>

      <!-- Content Wrapper -->
      <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">
          <?php
          $this->loadLayout($navbar_path, $data);
          