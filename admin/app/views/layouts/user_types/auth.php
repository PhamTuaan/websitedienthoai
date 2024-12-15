<?php $currentPath = $_SERVER['REQUEST_URI']; ?>

<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
<link href="<?php echo SCRIPT_ROOT; ?>/assets/css/nucleo-icons.css" rel="stylesheet" />
<link href="<?php echo SCRIPT_ROOT; ?>/assets/css/nucleo-svg.css" rel="stylesheet" />
<script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
<link href="<?php echo SCRIPT_ROOT; ?>/assets/css/soft-ui-dashboard.css?v=1.0.3" rel="stylesheet" />

<!-- JS -->
<script src="<?php echo SCRIPT_ROOT; ?>/assets/js/core/popper.min.js"></script>
<script src="<?php echo SCRIPT_ROOT; ?>/assets/js/core/bootstrap.min.js"></script>
<?php
    function hasPermission($permissionCode) {
        $permission_codes = $_SESSION['permissions'] ;
        return in_array($permissionCode, $permission_codes);
    }
?>
<?php
include(__DIR__ . '/../navbars/auth/sidebar.php');
?>
<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
    <?php
    include(__DIR__ . '/../navbars/auth/nav.php');
    ?>
    <div class="container-fluid py-4">
        <!-- Page -->
        <?php require_once "./app/views/page/${page}.php" ?>

        <!-- Footer -->
        <?php include(__DIR__ . '/../footers/auth/footer.php');
        ?>
    </div>
</main>