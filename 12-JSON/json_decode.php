<?php
// TEMA: JSON (JavaScript Object Notation) - Decodificar cadenas JSON a datos PHP con json_decode()

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>Decodificar JSON con json_decode()</title>
    <style>
        body { font-family: sans-serif; padding: 20px; line-height: 1.6; }
        .container { max-width: 800px; margin: auto; background: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        h1, h2 { color: #333; }
        pre {
            background-color: #272822; /* Color de fondo oscuro */
            color: #f8f8f2; /* Texto claro */
            padding: 15px;
            border: 1px solid #ccc;
            overflow-x: auto;
            white-space: pre-wrap;
            word-wrap: break-word;
            border-radius: 5px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 0.95em;
        }
        .json-string { margin-bottom: 10px; }
        .json-string code {
            display: block;
            background-color: #e9ecef; /* Fondo claro para el string JSON */
            color: #212529; /* Texto oscuro para el string JSON */
            padding: 10px;
            border-radius: 4px;
            font-family: 'Courier New', Courier, monospace;
        }
        .message.info { padding: 10px; background-color: #e7f3fe; border-left: 6px solid #2196F3; margin-bottom:15px;}
        .message.error { background-color: #f8d7da; border-color: #f5c6cb; color: #721c24; }
    </style>
</head>
<body>
<div class='container'>";

echo "<h1>Decodificar Cadenas JSON a Datos PHP con <code>json_decode()</code></h1>";

echo "<p class='message info'><code>json_decode()</code> toma una cadena en formato JSON y la convierte en una variable PHP (objeto stdClass por defecto, o array asociativo si se especifica).</p>";

// --- 1. Decodificar un Objeto JSON a un Objeto PHP (stdClass) ---
echo "<h2>1. Objeto JSON a Objeto PHP (<code>stdClass</code> por defecto)</h2>";
$jsonStringObjeto = '{"id":201,"nombre":"Tablet Pro","precio":299.99,"disponible":true,"tags":["android","10inch",null]}';

echo "<div class='json-string'><strong>Cadena JSON de entrada:</strong><code>" . htmlspecialchars($jsonStringObjeto) . "</code></div>";

$objetoPHP = json_decode($jsonStringObjeto); // Por defecto, el segundo parámetro (assoc) es false

echo "<strong>Resultado PHP (<code>json_decode(\$jsonStringObjeto)</code> - Objeto stdClass):</strong>";
echo "<pre>";
var_dump($objetoPHP); // var_dump muestra más detalles del objeto
echo "</pre>";

// Acceder a propiedades del objeto stdClass
if (is_object($objetoPHP)) {
    echo "<p>Accediendo a propiedades del objeto PHP:</p>";
    echo "<ul>";
    echo "<li>ID: " . htmlspecialchars($objetoPHP->id ?? 'N/A') . "</li>";
    echo "<li>Nombre: " . htmlspecialchars($objetoPHP->nombre ?? 'N/A') . "</li>";
    echo "<li>Precio: " . htmlspecialchars($objetoPHP->precio ?? 'N/A') . "</li>";
    echo "<li>Disponible: " . (isset($objetoPHP->disponible) ? var_export($objetoPHP->disponible, true) : 'N/A') . "</li>";
    if (isset($objetoPHP->tags) && is_array($objetoPHP->tags)) {
        echo "<li>Tags: " . htmlspecialchars(implode(", ", $objetoPHP->tags)) . " (El valor nulo en JSON se convierte a NULL en PHP)</li>";
    }
    echo "</ul>";
} else {
    echo "<p class='message error'>Error al decodificar el objeto JSON. Resultado no es un objeto.</p>";
}
echo "<hr/>";


// --- 2. Decodificar un Objeto JSON a un Array Asociativo PHP ---
echo "<h2>2. Objeto JSON a Array Asociativo PHP</h2>";
echo "<p>Pasando <code>true</code> como segundo argumento a <code>json_decode()</code>, los objetos JSON se convierten en arrays asociativos.</p>";
// Usaremos la misma cadena JSON $jsonStringObjeto

echo "<div class='json-string'><strong>Cadena JSON de entrada (misma que antes):</strong><code>" . htmlspecialchars($jsonStringObjeto) . "</code></div>";

$arrayAsociativoPHP = json_decode($jsonStringObjeto, true); // El segundo parámetro 'assoc' es true

echo "<strong>Resultado PHP (<code>json_decode(\$jsonStringObjeto, true)</code> - Array Asociativo):</strong>";
echo "<pre>";
var_dump($arrayAsociativoPHP);
echo "</pre>";

// Acceder a elementos del array asociativo
if (is_array($arrayAsociativoPHP)) {
    echo "<p>Accediendo a elementos del array asociativo PHP:</p>";
    echo "<ul>";
    echo "<li>ID: " . htmlspecialchars($arrayAsociativoPHP['id'] ?? 'N/A') . "</li>";
    echo "<li>Nombre: " . htmlspecialchars($arrayAsociativoPHP['nombre'] ?? 'N/A') . "</li>";
    echo "<li>Precio: " . htmlspecialchars($arrayAsociativoPHP['precio'] ?? 'N/A') . "</li>";
    echo "<li>Disponible: " . (isset($arrayAsociativoPHP['disponible']) ? var_export($arrayAsociativoPHP['disponible'], true) : 'N/A') . "</li>";
    if (isset($arrayAsociativoPHP['tags']) && is_array($arrayAsociativoPHP['tags'])) {
        echo "<li>Tags: " . htmlspecialchars(implode(", ", $arrayAsociativoPHP['tags'])) . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p class='message error'>Error al decodificar el objeto JSON a array. Resultado no es un array.</p>";
}
echo "<hr/>";


// --- 3. Decodificar un Array JSON ---
echo "<h2>3. Array JSON a Array PHP</h2>";
$jsonStringArray = '["rojo", "verde", "azul", 2024, false]';

echo "<div class='json-string'><strong>Cadena JSON de entrada (Array):</strong><code>" . htmlspecialchars($jsonStringArray) . "</code></div>";

// Decodificar a objeto (array de stdClass si los elementos fueran objetos)
$arrayComoObjeto = json_decode($jsonStringArray); // Los elementos simples se mantienen como tipos PHP
echo "<strong>Resultado PHP (<code>json_decode(\$jsonStringArray)</code> - Array de tipos PHP):</strong>";
echo "<pre>";
var_dump($arrayComoObjeto);
echo "</pre>";

// Decodificar a array (array de arrays si los elementos fueran objetos y assoc=true)
$arrayComoArray = json_decode($jsonStringArray, true);
echo "<strong>Resultado PHP (<code>json_decode(\$jsonStringArray, true)</code> - Array de tipos PHP):</strong>";
echo "<pre>";
var_dump($arrayComoArray);
echo "</pre>";
echo "<hr/>";


// --- 4. Opciones de `json_decode()` (tercer y cuarto parámetro) ---
echo "<h2>4. Opciones de <code>json_decode()</code></h2>";
$jsonConEnterosGrandes = '{"id_largo": 9876543210987654321, "descripcion": "Entero grande"}';

// --- 4.1. `depth` (tercer parámetro) ---
echo "<h3>4.1. <code>depth</code> (Profundidad máxima de decodificación)</h3>";
echo "<p>Controla la profundidad máxima de anidamiento. El valor por defecto es 512.</p>";
$jsonAnidado = '{"nivel1":{"nivel2":{"nivel3":{"valor":"profundo"}}}}';
echo "<div class='json-string'><strong>JSON anidado:</strong><code>" . htmlspecialchars($jsonAnidado) . "</code></div>";
$decodificadoProfundidadOk = json_decode($jsonAnidado, false, 2); // Profundidad 2 permitida
echo "<strong>Decodificado con profundidad 2 (debería fallar para nivel3):</strong>";
echo "<pre>";
var_dump($decodificadoProfundidadOk); // nivel3 podría ser null o el objeto parcial
echo "</pre>";
if (json_last_error() === JSON_ERROR_DEPTH) {
    echo "<p class='message error'>Error de decodificación: " . htmlspecialchars(json_last_error_msg()) . "</p>";
}

$decodificadoProfundidadSuficiente = json_decode($jsonAnidado, false, 10); // Profundidad 10 (suficiente)
echo "<strong>Decodificado con profundidad 10:</strong>";
echo "<pre>";
var_dump($decodificadoProfundidadSuficiente);
echo "</pre>";
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "<p class='message error'>Error inesperado: " . htmlspecialchars(json_last_error_msg()) . "</p>";
}
echo "<br/>";


// --- 4.2. `flags` (cuarto parámetro, PHP 5.4+) ---
echo "<h3>4.2. <code>flags</code> (Opciones de decodificación)</h3>";

// JSON_BIGINT_AS_STRING: Decodifica enteros grandes como cadenas para evitar pérdida de precisión en sistemas de 32 bits o si exceden PHP_INT_MAX.
echo "<h4><code>JSON_BIGINT_AS_STRING</code></h4>";
echo "<div class='json-string'><strong>JSON con entero grande:</strong><code>" . htmlspecialchars($jsonConEnterosGrandes) . "</code></div>";

$decodificadoBigIntNormal = json_decode($jsonConEnterosGrandes); // Podría convertir a float y perder precisión
echo "<strong>Decodificado normal (entero grande podría ser float):</strong>";
echo "<pre>";
var_dump($decodificadoBigIntNormal);
echo "</pre>";

$decodificadoBigIntAsString = json_decode($jsonConEnterosGrandes, false, 512, JSON_BIGINT_AS_STRING);
echo "<strong>Decodificado con <code>JSON_BIGINT_AS_STRING</code>:</strong>";
echo "<pre>";
var_dump($decodificadoBigIntAsString);
echo "</pre>";
echo "<br/>";

// JSON_OBJECT_AS_ARRAY: Similar al segundo parámetro `assoc = true`, pero usado con flags.
echo "<h4><code>JSON_OBJECT_AS_ARRAY</code> (flag)</h4>";
$decodificadoObjAsArrayFlag = json_decode($jsonStringObjeto, false, 512, JSON_OBJECT_AS_ARRAY);
echo "<strong>Decodificado con flag <code>JSON_OBJECT_AS_ARRAY</code>:</strong>";
echo "<pre>";
var_dump($decodificadoObjAsArrayFlag);
echo "</pre>";
echo "<br/>";

// JSON_THROW_ON_ERROR (PHP 7.3+): Lanza una JsonException en caso de error, en lugar de devolver null y requerir json_last_error().
echo "<h4><code>JSON_THROW_ON_ERROR</code> (PHP 7.3+)</h4>";
$jsonInvalido = '{"nombre": "Juan", "edad": 30, "ciudad": "Madrid"'; // Falta la llave de cierre }
echo "<div class='json-string'><strong>JSON inválido:</strong><code>" . htmlspecialchars($jsonInvalido) . "</code></div>";
try {
    $resultadoConThrow = json_decode($jsonInvalido, false, 512, JSON_THROW_ON_ERROR);
    echo "<strong>Resultado con JSON_THROW_ON_ERROR (no debería llegar aquí):</strong>";
    echo "<pre>"; var_dump($resultadoConThrow); echo "</pre>";
} catch (JsonException $e) {
    echo "<p class='message error'><strong>JsonException capturada:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>";
    echo "Archivo: " . htmlspecialchars($e->getFile()) . "\n";
    echo "Línea: " . htmlspecialchars($e->getLine()) . "\n";
    echo "Código: " . htmlspecialchars($e->getCode()) . "\n"; // Usualmente el mismo que json_last_error()
    echo "</pre>";
}
echo "<hr/>";


// --- 5. Manejo de Errores en json_decode() (si no se usa JSON_THROW_ON_ERROR) ---
echo "<h2>5. Manejo de Errores en <code>json_decode()</code> (sin <code>JSON_THROW_ON_ERROR</code>)</h2>";
echo "<p>Si <code>json_decode()</code> falla y no se usa <code>JSON_THROW_ON_ERROR</code>, devuelve <code>NULL</code>. Es importante distinguir este NULL de un JSON que contenga <code>null</code> explícitamente.</p>";
echo "<p>Se debe usar <code>json_last_error()</code> para obtener el código del error y <code>json_last_error_msg()</code> (PHP 5.5+) para el mensaje.</p>";

$jsonNuloValido = 'null';
$jsonSyntaxError = '{"nombre": "Ana" "apellido": "Perez"}'; // Error de sintaxis: falta coma

echo "<div class='json-string'><strong>JSON con valor null válido:</strong><code>" . htmlspecialchars($jsonNuloValido) . "</code></div>";
$resultadoNuloValido = json_decode($jsonNuloValido);
if ($resultadoNuloValido === null && json_last_error() === JSON_ERROR_NONE) {
    echo "<p>Decodificación de 'null' exitosa. Resultado PHP es NULL, sin errores.</p>";
} else {
    echo "<p class='message error'>Algo falló con la decodificación de 'null' o hubo un error: " . htmlspecialchars(json_last_error_msg()) . "</p>";
}
echo "<pre>"; var_dump($resultadoNuloValido); echo "</pre>";

echo "<div class='json-string'><strong>JSON con error de sintaxis:</strong><code>" . htmlspecialchars($jsonSyntaxError) . "</code></div>";
$resultadoSyntaxError = json_decode($jsonSyntaxError);

if ($resultadoSyntaxError === null && json_last_error() !== JSON_ERROR_NONE) {
    $codigoError = json_last_error();
    $mensajeError = json_last_error_msg(); // PHP 5.5+
    echo "<p class='message error'>";
    echo "<strong>Error al decodificar JSON (sintaxis incorrecta):</strong><br>";
    echo "Código de Error: " . htmlspecialchars($codigoError) . "<br>";
    echo "Mensaje de Error: " . htmlspecialchars($mensajeError) . "<br>";
    // Comparar con constantes de error JSON: JSON_ERROR_SYNTAX, etc.
    if ($codigoError === JSON_ERROR_SYNTAX) {
        echo "<em>El error fue debido a un problema de sintaxis en la cadena JSON.</em>";
    }
    echo "</p>";
} else {
    echo "<p>La decodificación del JSON con error de sintaxis no produjo el error esperado o el resultado no fue NULL.</p>";
    echo "<pre>"; var_dump($resultadoSyntaxError); echo "</pre>";
}


echo "<hr>";
echo "<p><a href='index.php'>Volver al índice de JSON</a> (si existe)</p>";
echo "<p><a href='json_encode.php'>Ir a Codificar JSON (json_encode)</a> | <a href='ejemplo_api_json.php'>Ir a Ejemplo Práctico API JSON</a></p>";

echo "</div></body></html>";
?>
