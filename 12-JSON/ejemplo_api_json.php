<?php
// TEMA: JSON - Ejemplo Práctico: Simular Consumo y Exposición de una API JSON simple

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>Ejemplo Práctico API JSON</title>
    <style>
        body { font-family: sans-serif; padding: 20px; line-height: 1.6; }
        .container { max-width: 800px; margin: auto; background: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        h1, h2, h3 { color: #333; }
        pre {
            background-color: #272822; color: #f8f8f2; padding: 15px;
            border: 1px solid #ccc; overflow-x: auto; white-space: pre-wrap;
            word-wrap: break-word; border-radius: 5px;
            font-family: 'Courier New', Courier, monospace; font-size: 0.95em;
        }
        .api-section { margin-bottom: 20px; padding: 15px; border: 1px solid #007bff; background-color: #e7f3fe; border-radius: 4px;}
        .api-section strong { color: #0056b3; }
        .code-block { margin-top:10px; }
        .button-group button { margin-right: 10px; padding: 8px 12px; background-color: #28a745; color:white; border:none; border-radius:3px; cursor:pointer; }
        .button-group button:hover { background-color: #218838; }
        .output { margin-top:10px; padding:10px; background-color:#fff; border:1px dashed #ccc; }
    </style>
</head>
<body>
<div class='container'>";

echo "<h1>Ejemplo Práctico: Consumir y Exponer una API JSON Simple</h1>";

// --- PARTE 1: Simular un endpoint de API que EXPONE datos en JSON ---
// Este script también actuará como el servidor de la API si se accede con ciertos parámetros.

if (isset($_GET['action']) && $_GET['action'] == 'get_productos') {
    // Establecer la cabecera Content-Type a application/json
    // Esto le dice al cliente (navegador, otra aplicación) que la respuesta es JSON.
    header('Content-Type: application/json; charset=utf-8');

    // Datos de ejemplo que nuestra API podría devolver (simulando una consulta a BD)
    $productosApi = [
        ["id" => 1, "nombre" => "Laptop X1", "categoria" => "Electrónica", "precio" => 1200.00, "stock" => 15],
        ["id" => 2, "nombre" => "Smartphone Z", "categoria" => "Electrónica", "precio" => 799.50, "stock" => 30],
        ["id" => 3, "nombre" => "Libro de PHP Avanzado", "categoria" => "Libros", "precio" => 45.99, "stock" => 100],
        ["id" => 4, "nombre" => "Cafetera Premium", "categoria" => "Hogar", "precio" => 89.99, "stock" => 0]
    ];

    $filtroCategoria = isset($_GET['categoria']) ? strtolower(trim($_GET['categoria'])) : null;
    $idProducto = isset($_GET['id']) ? intval($_GET['id']) : null;

    $respuesta = [];

    if ($idProducto) {
        foreach ($productosApi as $producto) {
            if ($producto['id'] === $idProducto) {
                $respuesta = $producto;
                break;
            }
        }
        if (empty($respuesta)) {
            http_response_code(404); // Not Found
            $respuesta = ["error" => "Producto no encontrado con ID: {$idProducto}"];
        }
    } elseif ($filtroCategoria) {
        foreach ($productosApi as $producto) {
            if (strtolower($producto['categoria']) === $filtroCategoria) {
                $respuesta[] = $producto;
            }
        }
        if (empty($respuesta)) {
             $respuesta = ["mensaje" => "No hay productos en la categoría: " . htmlspecialchars($filtroCategoria)];
        }
    } else {
        $respuesta = $productosApi;
    }

    // Codificar los datos a JSON y enviarlos como respuesta
    // Usar JSON_PRETTY_PRINT y JSON_UNESCAPED_UNICODE para mejor legibilidad y manejo de caracteres
    echo json_encode($respuesta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit; // Terminar el script aquí, ya que hemos enviado la respuesta JSON.
}

// --- PARTE 2: Simular un cliente PHP que CONSUME datos de una API JSON ---
// Para este ejemplo, consumiremos el "endpoint" que acabamos de simular en este mismo archivo.
// En un caso real, la URL sería un endpoint externo o de otro servicio.

echo "<h2>Consumiendo una API JSON (Simulada)</h2>";

function consumirApiProductos($urlEndpoint) {
    echo "<div class='api-section'>";
    echo "<p><strong>Intentando consumir:</strong> <code>" . htmlspecialchars($urlEndpoint) . "</code></p>";

    // file_get_contents puede usarse para peticiones GET simples.
    // Para peticiones más complejas (POST, PUT, headers personalizados, etc.), se usaría cURL.
    // El @ suprime warnings si la URL no es accesible, lo manejaremos manualmente.
    $jsonRespuesta = @file_get_contents($urlEndpoint);

    if ($jsonRespuesta === false) {
        echo "<p class='message error'>Error: No se pudo obtener respuesta de la API en '<code>" . htmlspecialchars($urlEndpoint) . "</code>'. ¿Está el servidor/endpoint activo?</p>";
        // Podríamos verificar $http_response_header si file_get_contents lo popula.
        return null;
    }

    echo "<strong>Respuesta JSON cruda recibida de la API:</strong>";
    echo "<pre>" . htmlspecialchars($jsonRespuesta) . "</pre>";

    // Decodificar la respuesta JSON
    // Usar JSON_THROW_ON_ERROR para un mejor manejo de errores de decodificación (PHP 7.3+)
    try {
        $datosDecodificados = json_decode($jsonRespuesta, true, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException $e) {
        echo "<p class='message error'>Error al decodificar la respuesta JSON: " . htmlspecialchars($e->getMessage()) . "</p>";
        return null;
    }

    echo "<strong>Datos PHP decodificados (Array Asociativo):</strong>";
    echo "<pre>";
    print_r($datosDecodificados);
    echo "</pre>";

    // Procesar y mostrar los datos decodificados
    if (isset($datosDecodificados['error'])) {
        echo "<p class='message error'>Error de la API: " . htmlspecialchars($datosDecodificados['error']) . "</p>";
    } elseif (isset($datosDecodificados['mensaje'])) {
        echo "<p class='message info'>Mensaje de la API: " . htmlspecialchars($datosDecodificados['mensaje']) . "</p>";
    } elseif (!empty($datosDecodificados)) {
        echo "<h3>Lista de Productos Recibidos:</h3>";
        echo "<ul>";
        // Si la respuesta es un único producto (no un array de productos)
        if (isset($datosDecodificados['id']) && !is_array(current($datosDecodificados))) {
            $producto = $datosDecodificados; // La respuesta es un solo producto
             echo "<li><strong>ID:</strong> " . htmlspecialchars($producto['id']) .
                     ", <strong>Nombre:</strong> " . htmlspecialchars($producto['nombre']) .
                     ", <strong>Precio:</strong> $" . htmlspecialchars($producto['precio']) .
                     ($producto['stock'] == 0 ? " <em style='color:red;'>(Agotado)</em>" : " (Stock: ".htmlspecialchars($producto['stock']).")") .
                     "</li>";
        } else { // La respuesta es una lista de productos
            foreach ($datosDecodificados as $producto) {
                if(is_array($producto)) { // Asegurarse que es un array de producto
                    echo "<li><strong>ID:</strong> " . htmlspecialchars($producto['id']) .
                         ", <strong>Nombre:</strong> " . htmlspecialchars($producto['nombre']) .
                         ", <strong>Precio:</strong> $" . htmlspecialchars($producto['precio']) .
                         ($producto['stock'] == 0 ? " <em style='color:red;'>(Agotado)</em>" : " (Stock: ".htmlspecialchars($producto['stock']).")") .
                         "</li>";
                }
            }
        }
        echo "</ul>";
    } else {
        echo "<p class='message info'>La API no devolvió productos o la respuesta está vacía.</p>";
    }
    echo "</div>";
    return $datosDecodificados;
}

// --- URLs de prueba para el endpoint simulado ---
// Obtener la URL base del script actual
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$scriptName = $_SERVER['SCRIPT_NAME'];
$baseUrlApi = $protocol . $host . $scriptName;

$urlTodosProductos = $baseUrlApi . "?action=get_productos";
$urlProductosElectronica = $baseUrlApi . "?action=get_productos&categoria=electrónica";
$urlProductoId3 = $baseUrlApi . "?action=get_productos&id=3";
$urlProductoIdInexistente = $baseUrlApi . "?action=get_productos&id=999";
$urlCategoriaInexistente = $baseUrlApi . "?action=get_productos&categoria=juguetes";

?>
<div class="button-group">
    <h3>Probar Consumo de API:</h3>
    <form method="GET" action="ejemplo_api_json.php" style="display:inline-block;">
        <input type="hidden" name="consumir" value="todos">
        <button type="submit">Consumir Todos los Productos</button>
    </form>
    <form method="GET" action="ejemplo_api_json.php" style="display:inline-block;">
        <input type="hidden" name="consumir" value="electronica">
        <button type="submit">Consumir Categoría "Electrónica"</button>
    </form>
    <form method="GET" action="ejemplo_api_json.php" style="display:inline-block;">
        <input type="hidden" name="consumir" value="id3">
        <button type="submit">Consumir Producto ID 3</button>
    </form>
     <form method="GET" action="ejemplo_api_json.php" style="display:inline-block;">
        <input type="hidden" name="consumir" value="id999">
        <button type="submit">Consumir Producto ID 999 (No existe)</button>
    </form>
    <form method="GET" action="ejemplo_api_json.php" style="display:inline-block;">
        <input type="hidden" name="consumir" value="cat_inexistente">
        <button type="submit">Consumir Categoría "Juguetes" (No existe)</button>
    </form>
</div>

<div class="output">
<?php
if (isset($_GET['consumir'])) {
    switch ($_GET['consumir']) {
        case 'todos':
            consumirApiProductos($urlTodosProductos);
            break;
        case 'electronica':
            consumirApiProductos($urlProductosElectronica);
            break;
        case 'id3':
            consumirApiProductos($urlProductoId3);
            break;
        case 'id999':
            consumirApiProductos($urlProductoIdInexistente);
            break;
        case 'cat_inexistente':
            consumirApiProductos($urlCategoriaInexistente);
            break;
    }
}
?>
</div>

<?php
echo "<p class='message info' style='margin-top:20px;'>Para ver la <strong>salida JSON cruda</strong> de la API simulada, puedes acceder a este mismo script con los siguientes parámetros en tu navegador:<br>
- <code>" . htmlspecialchars($urlTodosProductos) . "</code><br>
- <code>" . htmlspecialchars($urlProductosElectronica) . "</code><br>
- <code>" . htmlspecialchars($urlProductoId3) . "</code><br>
Tu navegador probablemente te mostrará el JSON formateado o te permitirá guardarlo.</p>";

echo "<hr>";
echo "<p><a href='index.php'>Volver al índice de JSON</a> (si existe)</p>";
echo "<p><a href='json_decode.php'>Ir a Decodificar JSON (json_decode)</a></p>";

echo "</div></body></html>";
?>
