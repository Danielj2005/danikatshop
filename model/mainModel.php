<?php
error_reporting(E_PARSE);
date_default_timezone_set('America/Caracas');

// Definimos contantes podemos utilizar define("NOMBRE", "valor");
// tambien podemos utilizar const NOMBRE="valor";

const SERVER = "localhost"; // Servidor de mysql
const DB = "danikat_db"; // Nombre de la base de datos
const USER = "root";  // Nombre de usuario de mysql
const PASSWORD = ""; // Contraseña de myqsl
const PHONE = "584244189963"; // Contraseña de myqsl

/*----------  Datos de la encriptacion (No modificar) ----------*/
const METHOD="AES-256-CBC";
const SECRET_KEY = 'CTS_PRO_2026_SECURITY_99';
const SECRET_IV='102791';


class modeloPrincipal {

    
    /*----------- Funcion para conectar con la base de datos -----------*/
    public static function Conexion(){
        if(!$con = mysqli_connect(SERVER, USER, PASSWORD)){
            die("Los datos de conexión con la base de datos ingresados son incorrectos, por favor verifique");
        }
        if (!mysqli_select_db($con, DB)) {
            die("El nombre de la base de datos es incorrecto, por favor verifique");
        }
        mysqli_set_charset($con, "utf8");
        return $con;
    }

    /*--------------------------------- CRUD ---------------------------------*/
    // C - create - crear
    // R - read - leer 
    // U - update - actualizar
    // D - delete - eliminar


    /********** Funcion consulta simple BD**********/
    public static function consultar($query) {
        mysqli_query(Self::Conexion(),"SET AUTOCOMMIT=0;");
        mysqli_query(Self::Conexion(),"BEGIN;");
        if (!$consul = mysqli_query(Self::Conexion(),$query)) {
            mysqli_query(Self::Conexion(),"ROLLBACK;");
            die(mysqli_error($query).' Error en la consulta SQL ejecutada ');
        }else{
            mysqli_query(Self::Conexion(),"COMMIT;");
        }
        return $consul;
    } 

    /*----------- Funcion insertar datos de Base de Datos -----------*/

    public static function InsertSQL($tabla,$campos,$valores) {
        if (!$consulta = Self::consultar("INSERT INTO $tabla ($campos) VALUES($valores)")) {
            die("Ha ocurrido un error al guardar los datos");
        }
        return $consulta;
    }

    /*----------- Funcion eliminar datos de Base de Datos -----------*/

    public static function DeleteSQL($tabla, $condicion) {
        if (!$consulta = Self::consultar("DELETE FROM $tabla WHERE $condicion")) {
            die("Ha ocurrido un error al eliminar los datos");
        }
        return $consulta;
    }

    /*----------- Funcion Modificar datos de Base de Datos -----------*/
    public static function UpdateSQL($tabla, $campos, $condicion) {
        if (!$consulta = Self::consultar("UPDATE $tabla SET $campos WHERE $condicion")) {
            die("Ha ocurrido un error al actualizar los datos");
        }
        return $consulta;
    }

    public static function generar_uuid() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
    /****************************************************************************************************************/
    /*********************** funciones para encryptacion y desencryptacion de datos sencibles ***********************/
    /****************************************************************************************************************/
    /********** Funcion encriptar Cadena  **********/


    public static function hashear_contrasena($contrasena_plana) {
        // Usamos el algoritmo PASSWORD_BCRYPT.
        // PHP automáticamente añade un "salt" criptográficamente seguro al hash.
        $hash_seguro = password_hash($contrasena_plana, PASSWORD_BCRYPT);
        
        return $hash_seguro;
    }

    public static function encryption($string) {
        $key = SECRET_KEY;
        $result = '';
            for($i=0; $i<strlen($string); $i++) {
                $char = substr($string, $i, 1);
                $keychar = substr($key, ($i % strlen($key))-1, 1);
                $char = chr(ord($char)+ord($keychar));
                $result.=$char;
            }
        return base64_encode($result);
    }

    /********** Funcion desencriptar Cadena  **********/
    public static function decryption($string) {
        $key = SECRET_KEY;
        $result = '';
        $string = base64_decode($string);
        for($i=0; $i<strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr($key, ($i % strlen($key))-1, 1);
            $char = chr(ord($char)-ord($keychar));
            $result.=$char;
        }
        return $result;
    }

    /********** Funcion ocultar datos de una Cadena con asteriscos (***...) **********/
    public static function ocultar_info($info) {
        $cantidad_caracteres = strlen($info); // obtiene la cantidad de caracteres de la cadena

        $mitad = intval($cantidad_caracteres / 1.1); // obtiene la mitad de la cadena

        $asteriscos = str_repeat("*", $mitad); // obtiene la cantidad de asteriscos a mostrar

        $inicio = substr($info, 0, $cantidad_caracteres - $mitad); // obtiene la parte inicial de la cadena

        $info_oculta = $inicio . $asteriscos; // concatena la parte inicial de la cadena con los asteriscos

        return $info_oculta; // retorna la cadena oculta
    }

    // funcion para encriptar id de bases de datos para evitar su rastreo
    public static function encryptionId($id) {
        $idModificada = $id * 10;
        $idModificada = self::encryption($idModificada);
        return $idModificada;
    }
    
    // funcion para encriptar id de bases de datos para evitar su rastreo
    public static function alterarId($id) {
        $idModificada = $id * 10;
        return $idModificada;
    }
    
    // funcion para desencriptar id de bases de datos para evitar su rastreo
    public static function decryptionId($id) {
        $id_recibida = self::decryption($id);
        $id = $id_recibida / 10;
        return $id;
    }

    /**********************************************************************************/
    /*********************** funciones para validar datos       ***********************/
    /**********************************************************************************/

    /*---------- Funcion Verificar Datos ----------*/
    public static function verificar_datos($filtro,$cadena){
        if (preg_match("/^".$filtro."$/", $cadena)) {
            return false;
        } else {
            return true;
        }
    }

    public static function validacion_registro_existente($campos,$tabla,$condicion){
        // se comprueba que no exista un registro con los mismos datos
        if(mysqli_num_rows(Self::consultar("SELECT $campos FROM $tabla WHERE $condicion")) > 0){
            /********** No se puede registrar un usuario si ya existe **********/
            alert_model::alert_register_exist();
            exit(); 
        }
        return true;
    }

    public static function validar_campos_vacios($campos){
        
        foreach ($campos as $key){
            if($key == "") {
                alert_model::alert_fields_empty();
                exit();
            }
        }
    }

    /********** Funcion verificar las consultas a la BD**********/
    public static function verificar_consulta($query,$tabla) {
        if (!$query) { // se muestra un mensaje en caso de que no se pueda realizar la consulta
            alert_model::alerta_simple('¡Error!', "No se pudo realizar la consulta de $tabla.", 'error');
            exit();
        }
    } 

    
    // funcion para validar si se esta recibiendo datos por post
    public static function validar_post($post) {

        if (!isset($_POST["$post"]) || $_POST["$post"] == ""){
            $post = '';
        } else{
            $post = modeloprincipal::limpiar_cadena($_POST["$post"]);
        }
        return $post;
    }

    /**********************************************************************************/
    /******************* funciones para sanear cadenas de texto ***********************/
    /**********************************************************************************/
    /********** Funcion limpiar Cadena  **********/

    public static function limpiar_cadena($valor) {
        $valor = trim($valor);
        $valor = stripslashes($valor);
        $valor = str_ireplace("<script>", "", $valor);
        $valor = str_ireplace("</script>", "", $valor);
        $valor = str_ireplace("<script src>", "", $valor);
        $valor = str_ireplace("<script type=>", "", $valor);
        $valor = str_ireplace("SELECT * FROM", "", $valor);
        $valor = str_ireplace("DELETE FROM", "", $valor);
        $valor = str_ireplace("INSERT INTO", "", $valor);
        $valor = str_ireplace("DROP TABLE", "", $valor);
        $valor = str_ireplace("DROP DATABASE", "", $valor);
        $valor = str_ireplace("TRUNCATE TABLE", "", $valor);
        $valor = str_ireplace("SHOW TABLE", "", $valor);
        $valor = str_ireplace("OR 1 = 1", "", $valor);
        $valor = str_ireplace("SHOW DATABASES", "", $valor);
        $valor = str_ireplace("<?php>", "", $valor);
        $valor = str_ireplace("?>", "", $valor);
        $valor = str_ireplace("--", "", $valor);
        $valor = str_ireplace("^", "", $valor);
        $valor = str_ireplace("[", "", $valor);
        $valor = str_ireplace("]", "", $valor);
        $valor = str_ireplace("\\", "", $valor);
        $valor = str_ireplace("=", "", $valor);
        $valor = str_ireplace("==", "", $valor);
        $valor = str_ireplace("===", "", $valor);
        $valor = str_ireplace("'", "", $valor);
        $valor = str_ireplace("?", "", $valor);
        $valor = str_ireplace("%", "", $valor);
        $valor = str_ireplace(":", "", $valor);
        $valor = str_ireplace("::", "", $valor);
        $valor = str_ireplace(";", "", $valor);
        $valor = stripslashes($valor);
        $valor = trim($valor);
        return $valor;
    }

    public static function LimpiarCadenaTexto($val) {
        $data = addslashes($val);
        $datos = self::limpiar_cadena($data);
        return $datos;
    }


    /*---- funcion para convertir caracteres con acentos o caracteres especiales en mayúsculas  ----*/
    public static function convertir_mayusculas($variable){
        $mayuculas = mb_strtoupper(mb_convert_case($variable, MB_CASE_UPPER, "UTF-8"), "UTF-8");
        return $mayuculas;
    }

    /*----------- funcion para convertir en mayusculas y limpiar cadenas -----------*/
    public static function limpiar_mayusculas($variable){
        $cadena = Self::limpiar_cadena($variable);
        $mayuculas_limpias = Self::convertir_mayusculas($cadena);
        return $mayuculas_limpias;
    }

    /*-------- funcion para limpiar una cadena convertirla en mayusculas y encriptarla -------*/
    public static function limpiar_mayusculas_encriptar($cadena){
        $cadena_limpia = Self::limpiar_cadena($cadena);
        $cadena_mayuscula = Self::convertir_mayusculas($cadena_limpia);
        $cadena_encripted = Self::encryption($cadena_mayuscula);

        return $cadena_encripted;
    }


    public static function obtener_precio_dolar(){
        
        $ch = curl_init();

        // Configurar la URL y otras opciones de cURL
        curl_setopt($ch, CURLOPT_URL, SERVERURL."controller/dolarApi.php");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Desactivar verificación SSL (no recomendado en producción)

        // Ejecutar la solicitud
        $response = curl_exec($ch);

        // Verificar si hubo un error
        if (curl_errno($ch)) {
            echo 'Error en la URL: ' . curl_error($ch);
            exit;
        }

        $data = json_decode($response, true);

        $data = [
            "USD" => $data['USD'],
            "EURO" => $data['EURO']
        ];

        return $data;
    }
    
    public static function obtener_id_precio_dolar(){
        $precio_dolar_actual = modeloPrincipal::consultar("SELECT MAX(id_dolar) AS id FROM dolar");
        if(mysqli_num_rows($precio_dolar_actual) < 1) {
            return 0;
        }else{
            $precio_dolar_actual = mysqli_fetch_array($precio_dolar_actual)['id'];
            return $precio_dolar_actual;
        }
    }

    public static function number_format_prices($precio){
        return number_format($precio, 2, ",", ".");
    }

    
    /*******************************************************************/ 
    /*          Funciones dedicadas a sanear cadenas de texto          */
    /*******************************************************************/ 
    public static function primeraLetraMayus ($string):string { return ucwords(strtolower($string)); }



}
