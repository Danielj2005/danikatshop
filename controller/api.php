<?php
// api.php
// conexión (Cámbialos por los que te da InfinityFree)
/*
$host = 'sql204.infinityfree.com'; 
$db_name = 'if0_41737603_danikat_bd';
$username = 'if0_41737603';
$password = '0iAMk3Kc0lb'; 
*/

$host = 'localhost'; 
$db_name = 'danikat_db';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // --- OBTENER CATEGORÍAS (GET) ---
    $stmt = $conn->prepare("SELECT * FROM categorias ORDER BY nombre ASC");
    $stmt->execute();
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Convertimos el string de imágenes de nuevo a Array para el JS
    
    ?>
        <thead>
            <tr>
                <th class="col text-center" scope="col">#</th>
                <th class="col text-center" scope="col">Nombre</th>
                <th class="col text-center" scope="col">Descripción</th>
                <th class="col text-center" scope="col">Estado</th>
            </tr>
        </thead>
        <tbody>
        <?php 
            foreach ($categorias as $cat) { ?>

                <tr>
                    <td class="col text-center"></td>
                    <td class="col text-start"><?= $cat["nombre"]; ?></td>
                    <td class="col text-start"><?= $cat["descripcion"]; ?></td>
                    <td scope="row" class="text-center">
                        <?php 
                            if ($cat["state"] === 1) { ?>
                                <button class="btn btn-outline-success bi-check-circle" title="state de la categoría"></button>
                            <?php } else { ?>
                                
                                <form action="../controller/categoria_controller.php" 
                                    method="post" class="SendFormAjax" data-type-form="update_estate" >
                                        <input type="hidden" name="modulo" value="inactivo">          
                                        <input type="hidden" name="UID" value="<?= $cat["id"]; ?>">
                                        <button class="btn btn-danger bi-x-circle" title="state de la categoría" type="submit"></button>
                                </form>
                            <?php }
                        ?>
                    </td>
                </tr>
            <?php } ?>
    </tbody>
<?php
} catch(PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>