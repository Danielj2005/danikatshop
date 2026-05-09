<?php 
// api.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

function cleanData($nodeList) {
    if ($nodeList->length === 0) return 0.0;
    
    $precio = trim($nodeList->item(0)->nodeValue);
    $precio = str_replace(",", ".", $precio); 
    // En lugar de substr, usamos filter_var para asegurar que sea un número válido
    $precio_limpio = filter_var($precio, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    return (float)$precio_limpio;
}


try {

    $method = $_SERVER['REQUEST_METHOD'];

    // --- OBTENER PRODUCTOS (GET) ---
    if ($method === 'GET') {
        $moneda = $_GET['coin'] ?? null;
        $data = [];
        // Inicializar cURL
        $ch = curl_init();

        // Configurar la URL y otras opciones de cURL
        curl_setopt($ch, CURLOPT_URL, "https://www.bcv.org.ve/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Desactivar verificación SSL (no recomendado en producción)
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'); // Evita bloqueos

        // Ejecutar la solicitud
        $response = curl_exec($ch);

        // Verificar si hubo un error
        if (curl_errno($ch)) {
            throw new Exception('Error cURL: ' . curl_error($ch));
        }

        // Cerrar cURL
        // curl_close($ch);

        // Cargar el HTML en DOMDocument
        $dom = new DOMDocument();
        libxml_use_internal_errors(true); // Ignorar errores de HTML
        $dom->loadHTML($response);
        libxml_clear_errors();
        
        // Buscar el precio del dólar en el HTML
        $xpath = new DOMXPath($dom);
        $precioDolar = $xpath->query("//div[@id='dolar']//strong"); // Ajusta el XPath según la estructura del HTML
        $precioEuro = $xpath->query("//div[@id='euro']//strong"); // Ajusta el XPath según la estructura del HTML

        if ($precioDolar->length > 0 || $precioEuro->length > 0) {

            $precioDolar = cleanData ($precioDolar);
            $precioEuro = cleanData ($precioEuro);

            if ($moneda != null) {
                $price = ["USD" => $precioDolar,"EURO" => $precioEuro];
                $data = ["status" => "success", $moneda => $price[$moneda]];
            }else{

                $data = ["status" => "success", "USD" => $precioDolar, "EURO" => $precioEuro];
            }

            echo json_encode($data);
        } else {
            echo json_encode(["status" => "error", "message" => "No se detectaron las tasas en el BCV"]);
        }
    }

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>