<?php
include_once "./model/mainModel.php"; // se incluye el model principal

$pass = "Danielj20";

$a = modeloPrincipal::hashear_contrasena($pass);

echo $pass;
echo "<hr>";
echo $a;