<!DOCTYPE html>
<html lang="<?= LANG ?>" class="dark">
<?php 

$peticionAjax = false;

include_once "./model/mainModel.php"; // se incluye el model principal

include_once "./model/productModel.php"; // se incluye el model producto

require_once "./controller/viewcontroller.php";


$ins_views = new viewController();

$view = $ins_views->obtener_vistas_controlador();


?>

<head>
    <?php
        include_once "view/inc/head.php";
    ?>
</head>

<body id="" class="font-sans antialiased brand-bg">

    <?php
        include_once 'view/inc/navBar.php';

        if ($view !== "404") : 
            
            $view = $view == "index" ? "./view/content/index-view.php" : $view;
            $view = $view == "login" ? "./view/content/login-view.php" : $view;
            $view = $view !== "login" && $view !== "index" ? $view : null;
            
            include $view;

        else:
            
            require_once "./view/content/404-view.php";

        endif; 
        
        if ($view == "404") :
            include_once "view/inc/js.php";
        else: 
            // <!-- Script -->
            include_once "view/inc/script.php";
        endif;
        ?>
</body>

</html>