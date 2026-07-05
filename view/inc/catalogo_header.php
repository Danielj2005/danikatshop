<header id="header" class="gap-3 align-items-center d-flex fixed-top header justify-content-between px-3">

    <div class="d-flex align-items-center justify-content-between d-non d-lg-block">
        <a href="./" class="logo d-flex align-items-center">
            <img src="<?= COMPANY ? 'view/img/logo.jpeg' : 'view/img/danikat-logo.jpeg' ?>" alt="">
            <span class=""><?= COMPANY ?? 'DANIKAT SHOP' ?></span>
        </a>
    </div>


    <div class="search-br w-100">
        <form class="search-form d-flex align-items-center" method="POST" action="#">
            <input type="text" name="query" placeholder="Buscar tortas, arreglos, manualidades..." title="Enter search keyword">
            <button type="submit" title="Search"><i class="bi bi-search"></i></button>
        </form>
    </div>

    <div class="d-flex align-items-center">
        <a href="login-admin.php" class="nav-link nav-profile d-flex align-items-center pe-0" data-bs-toggle="dropdown">
            <i class="bi bi-person-circle fs-3"></i>
            <span class="ms-2">Login</span>
        </a>
    </div>
</header>
<div class="msjFormSend"></div>
