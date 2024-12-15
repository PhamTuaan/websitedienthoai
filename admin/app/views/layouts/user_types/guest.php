<div class="container position-sticky z-index-sticky top-0">
    <div class="row">
        <div class="col-12">
            <?php include(__DIR__ . '/../navbars/guest/nav.php');
            ?>
        </div>
    </div>
</div>

<!-- Page -->
<?php require_once "./app/views/page/${page}.php" ?>

<!-- Footer -->
<?php include(__DIR__ . '/../footers/guest/footer.php');
?>