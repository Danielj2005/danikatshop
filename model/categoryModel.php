<?php

class category_model extends modeloPrincipal {

    public static function consultar_categoria($fields) {
        $consul = modeloPrincipal::consultar("SELECT $fields FROM categorias ORDER BY nombre");
        modeloPrincipal::verificar_consulta($consul,'categoria'); // se verifica si la consulta fue exitosa
        return $consul;
    }

    public static function consultar_condicional($fields, $condicion) {
        $consul = modeloPrincipal::consultar("SELECT $fields FROM categorias WHERE $condicion");
        modeloPrincipal::verificar_consulta($consul,'categoria'); // se verifica si la consulta fue exitosa
        return $consul;
    }

    public static function consultar_categoria_por_id($fields, $id) {
        $consul = modeloPrincipal::consultar("SELECT $fields FROM categorias WHERE id = $id");
        modeloPrincipal::verificar_consulta($consul,'categoria'); // se verifica si la consulta fue exitosa
        return $consul;
    }

    
    // funcion para obtener el id de un categoria
    public static function obtener_id_recien_registrada(){
        $id = mysqli_fetch_array(modeloPrincipal::consultar("SELECT MAX(id) AS id FROM categorias"));
        $id = $id['id'];
        return $id;
    }

    public static function registrar ($nombre, $descripcion) {

        $registrar = modeloPrincipal::InsertSQL("categorias", "nombre, descripcion, state, created_at" ,"'$nombre', '$descripcion', 1, NOW()");
    
        if (!$registrar) {
            alert_model::alerta_simple("¡Ocurrió un error inesperado!","No se pudo registrar la categoría debido a un error interno o alteracion de la información a registrar, por favor verifique e intente nuevamente","error");
        }
        return $registrar;
    }

    public static function createListModal() {
        $data = [
            "success" => false,
            "headerTable" => ["Nombre","Descripción","Estado"],
            "bodyTable" => "",
            "footerTable" => "",
        ];


        ?>
        <div class="table table-responsive">
            <table class="table table-striped mb-3 " id="tableList">
                <thead>
                    <tr id="headerTable">
                        <th class="col text-center" scope="col">#</th>
                    </tr>
                </thead>
                <tbody id="bodyTable"></tbody>
            </table>
        </div>
    <?php
    }

    public static function lista(){
        $consulta = self::consultar_categoria("*");
        
        // se guardan los datos en un array y se imprime
        $i = 1;
        while ( $mostrar = mysqli_fetch_array($consulta)) { ?>    
            <tr>
                <td class="col text-center"><?= $i++ ?></td>
                <td class="col text-start"><?= $mostrar["nombre"]; ?></td>
                <td class="col text-start"><?= $mostrar["descripcion"]; ?></td>
                
                <?php if (modeloPrincipal::verificar_permisos_requeridos(['m_categoria'])) { ?>
                    <td scope="row" class="text-center">
                        <?php 
                            if ($mostrar["state"] === "1") { ?>
                                <button class="btn btn-outline-success bi-check-circle" title="state de la categoría"></button>
                            <?php } else { ?>
                                
                                <form 
                                    action="../controlador/categoria_controller.php" 
                                    method="post" 
                                    class="SendFormAjax" 
                                    data-type-form="update_estate" >
                                        <input type="hidden" name="modulo" value="inactivo">          
                                        <input type="hidden" name="UID" value="<?= modeloPrincipal::encryptionId($mostrar["id"]); ?>">
                                        <button class="btn btn-outline-danger bi-x-circle" title="state de la categoría" type="submit"></button>
                                </form>
                            <?php }
                        ?>
                    </td>
                <?php } ?>
            </tr>
        <?php  } 
    }


    public static function options() {
        $consulta = self::consultar_condicional("nombre","state = 1");
        // se guardan los datos en un array y se imprime
        while ( $mostrar = mysqli_fetch_array($consulta)) { ?>    
            <option value="<?= $mostrar["nombre"];?>"> <?= $mostrar["nombre"]; ?></option>
        <?php  } 
    }

    public static function optionsId () {
        $consulta = self::consultar_condicional("id, nombre","state = 1 ORDER BY nombre");
        // se guardan los datos en un array y se imprime 
        while ( $mostrar = mysqli_fetch_array($consulta)) { ?>    
            <option value="<?= modeloPrincipal::encryptionId($mostrar["id"]) ?>"><?= $mostrar["nombre"]; ?></option>
        <?php }
    }

    public static function actualizar_state($state, $id){
        // se comprueba que no exista un registro con los mismos datos
        if (!modeloprincipal::UpdateSQL("categorias", "state = $state", "id = $id")) {
            return false;
        }
        return true;
    }



    public static function obtener_array_ids_recien_registradas($categorias) {
        $cant_ids = mysqli_fetch_array(modeloPrincipal::consultar("SELECT MAX(id) AS id FROM categorias"))['id'];

        $id_registradas = intval($cant_ids) - intval($categorias);

        $dataFind = [];
        $i = 0;

        for ( $id_registradas += 1;  $id_registradas <= $cant_ids; $id_registradas++ ) {
            $dataFind[$i++] .= $id_registradas;
        }
        $dataFind = array_values(array_unique($dataFind));

        return $dataFind;
    }


    
    public static function obtener_array_ids($NC):array {
        // $NC es un array con los Nombres de las Categorías = NM
        
        $dataFind = [];

        for ( $i = 0;  $i < count($NC); $i++ ) {

            $dataFind[$i] = mysqli_fetch_array(modeloPrincipal::consultar("SELECT id FROM categorias WHERE nombre = '".$NC[$i]."'"))['id'];
        }

        $dataFind = array_values($dataFind);

        return $dataFind;
    }



    public static function bitacora_modificar_state_categoria ($cambios) {
        
        bitacora::bitacora("Modificación exitosa del state de una categoría.",'<p class="mb-3 text-primary-emphasis text-center"><i class="bi bi-exclamation-circle-fill"></i>&nbsp; Se modificó el state de una categoría con la siguiente informacón.</p> 
            <h4 class="text-center card-title"><b> Información de la categoría </b></h4>
            <div class="d-flex justify-content-between border-bottom"> <p> Nombre</p> '.$cambios['nombre'].' </div>
            <div class="d-flex justify-content-between border-bottom"> <p> Descripción</p> '.$cambios['descripcion'].' </div>
            <div class="d-flex justify-content-between border-bottom"> <p> state</p> '.$cambios['state'].' </div>');
        
    }
}