<?php $path = str_replace('-', ' ', $_SERVER['REQUEST_URI']); ?>

<?php
$originalPath = $_SERVER['REQUEST_URI'];

$parts = explode('/admin/', $originalPath);

$keyword = '';

if (count($parts) > 1) {
    $subParts = explode('/', $parts[1]);
    $keyword = $subParts[0];
}

$keyword = str_replace('-', ' ', $keyword);
?>

<!-- Navbar -->
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
    <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                <li class="breadcrumb-item text-sm text-dark active text-capitalize" aria-current="page"><?= $keyword ?></li>
            </ol>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4 d-flex justify-content-end" id="navbar">
            <div class="ms-md-3 pe-md-3 d-flex align-items-center">
                <div class="input-group">
                    <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="Tìm kiếm...">
                </div>
            </div>
            <ul class="navbar-nav justify-content-end">
                <li class="nav-item d-flex align-items-center">
                    <i class="fa fa-user me-sm-1"></i>
                    <span class="d-sm-inline d-none"><?php echo $_SESSION['auth_admin']['fullname'] ?></span> |
                    <a href="/phone-ecommerce-chat/admin/auth/logout" class="nav-link text-body font-weight-bold px-0">
                        <span class="d-sm-inline d-none">Đăng xuất</span>
                    </a>
                </li>
               
            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar -->