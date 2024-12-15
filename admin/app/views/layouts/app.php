<!--
=========================================================
* Soft UI Dashboard - v1.0.3
=========================================================

* Product Page: https://www.creative-tim.com/product/soft-ui-dashboard
* Copyright 2021 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)

* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="<?php echo SCRIPT_ROOT; ?>/assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="<?php echo SCRIPT_ROOT; ?>/assets/img/favicon.png">
  <title>Augentern Shop</title>


  <!-- Fonts and icons -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />

  <!-- Nucleo Icons -->
  <link href="<?php echo SCRIPT_ROOT; ?>/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="<?php echo SCRIPT_ROOT; ?>/assets/css/nucleo-svg.css" rel="stylesheet" />

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Ajax -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js" integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <!-- Data table -->
  <link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.3/af-2.7.0/b-3.0.1/cr-2.0.0/fh-4.0.1/r-3.0.1/sc-2.4.1/sb-1.7.0/sp-2.3.0/sl-2.0.0/sr-1.4.0/datatables.min.css" rel="stylesheet">
  <script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.3/af-2.7.0/b-3.0.1/cr-2.0.0/fh-4.0.1/r-3.0.1/sc-2.4.1/sb-1.7.0/sp-2.3.0/sl-2.0.0/sr-1.4.0/datatables.min.js"></script>

  <!-- Toast -->
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

  <!-- CSS Files -->
  <link id="pagestyle" href="<?php echo SCRIPT_ROOT; ?>/assets/css/soft-ui-dashboard.css?v=1.0.3" rel="stylesheet" />
  <!-- <link id="pagestyle" href="<?php echo SCRIPT_ROOT; ?>/assets/css/soft-ui-dashboard.css?v=1.0.3" rel="stylesheet" /> -->
</head>

<body class="g-sidenav-show bg-gray-100">
  <?php if (isset($_SESSION['authenticated_admin']) && $_SESSION['authenticated_admin']) : ?>
      <?php include(__DIR__ . '/user_types/auth.php'); ?>
  <?php else : ?>
      <?php include(__DIR__ . '/user_types/guest.php'); ?>
  <?php endif; ?>

  

  <!-- Core JS Files -->
  <script src="<?php echo SCRIPT_ROOT; ?>/assets/js/core/popper.min.js"></script>
  <script src="<?php echo SCRIPT_ROOT; ?>/assets/js/core/bootstrap.min.js"></script>
  <script src="<?php echo SCRIPT_ROOT; ?>/assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="<?php echo SCRIPT_ROOT; ?>/assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="<?php echo SCRIPT_ROOT; ?>/assets/js/plugins/fullcalendar.min.js"></script>
  <script src="<?php echo SCRIPT_ROOT; ?>/assets/js/plugins/chartjs.min.js"></script>
  
  <!-- Your custom scripts (optional) -->
  <script src="<?php echo SCRIPT_ROOT; ?>/assets/js/soft-ui-dashboard.min.js?v=1.0.3"></script>
  <script src="<?php echo SCRIPT_ROOT; ?>/assets/js/custom.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>

</body>

</html>