<?php
// TEMA: JSON (JavaScript Object Notation) - Codificar datos PHP a JSON con json_encode()

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>Codificar a JSON con json_encode()</title>
    <style>
        body { font-family: sans-serif; padding: 20px; line-height: 1.6; }
        .container { max-width: 800px; margin: auto; background: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        h1, h2 { color: #333; }
        pre {
            background-color: #272822; /* Color de fondo oscuro similar a editores de código */
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
        .php-code { margin-bottom: 10px; }
        .php-code strong { color: #e6db74; /* Amarillo para strings en temas oscuros */ }
        .php-code em { color: #66d9ef; font-style: normal; /* Cian para números/booleanos */ }
        .message.info { padding: 10px; background-color: #e7f3fe; border-left: 6px solid #2196F3; margin-bottom:15px;}
    </style>
</head>
<body>
<div class='container'>";

echo "<h1>Codificar Datos PHP a Formato JSON con <code>json_encode()</code></h1>";

echo "<p class='message info'><code>json_encode()</code> toma una variable PHP (array, objeto, string, número, booleano, null) y la convierte en una representación de cadena en formato JSON.</p>";

// --- 1. Codificar un Array Asociativo (se convierte en un objeto JSON) ---
echo "<h2>1. Array Asociativo a Objeto JSON</h2>";
$datosUsuario = [
    "id" => 101,
    "nombre" => "Ana García",
    "email" => "ana.garcia@example.com",
    "activo" => true,
    "roles" => ["editor", "usuario"],
    "direccion" => null
];

echo "<div class='php-code'><strong>Variable PHP (Array Asociativo):</strong><pre>" . htmlspecialchars(print_r($datosUsuario, true)) . "</pre></div>";

$jsonUsuario = json_encode($datosUsuario);
echo "<div class='php-code'><strong>Resultado JSON (<code>json_encode(\$datosUsuario)</code>):</strong><pre>" . htmlspecialchars($jsonUsuario, ENT_NOQUOTES) . "</pre></div>";
// ENT_NOQUOTES es importante aquí para que las comillas del JSON se muestren correctamente en el HTML.

// --- 2. Codificar un Array Indexado (se convierte en un array JSON) ---
echo "<h2>2. Array Indexado a Array JSON</h2>";
$frutas = ["Manzana", "Banana", "Naranja", "Pera"];

echo "<div class='php-code'><strong>Variable PHP (Array Indexado):</strong><pre>" . htmlspecialchars(print_r($frutas, true)) . "</pre></div>";

$jsonFrutas = json_encode($frutas);
echo "<div class='php-code'><strong>Resultado JSON (<code>json_encode(\$frutas)</code>):</strong><pre>" . htmlspecialchars($jsonFrutas, ENT_NOQUOTES) . "</pre></div>";


// --- 3. Codificar un Objeto PHP (se convierte en un objeto JSON) ---
// Las propiedades públicas del objeto se codifican.
echo "<h2>3. Objeto PHP a Objeto JSON</h2>";
class Producto {
    public $idProducto;
    public $nombreProducto;
    private $precioSecreto; // Las propiedades privadas no se incluyen por defecto
    protected $stockInterno; // Las propiedades protegidas tampoco

    public function __construct($id, $nombre, $precio, $stock) {
        $this->idProducto = $id;
        $this->nombreProducto = $nombre;
        $this->precioSecreto = $precio; // Privada
        $this->stockInterno = $stock;   // Protegida
    }
    // Para incluir propiedades privadas/protegidas, la clase podría implementar la interfaz JsonSerializable
    // o podrías tener un método toArray() o similar.
}
$productoObj = new Producto(205, "Teclado Gamer", 79.99, 50);

echo "<div class='php-code'><strong>Variable PHP (Objeto Producto):</strong><pre>" . htmlspecialchars(print_r($productoObj, true)) . "</pre></div>";

$jsonProducto = json_encode($productoObj);
echo "<div class='php-code'><strong>Resultado JSON (<code>json_encode(\$productoObj)</code> - solo propiedades públicas):</strong><pre>" . htmlspecialchars($jsonProducto, ENT_NOQUOTES) . "</pre></div>";


// --- 4. Opciones de `json_encode()` ---
echo "<h2>4. Opciones (Flags) de <code>json_encode()</code></h2>";
echo "<p><code>json_encode()</code> acepta un segundo parámetro opcional (flags) para controlar la codificación.</p>";

// Ejemplo de datos más complejo
$datosComplejos = [
    "titulo" => "Artículo <Importante> & 'Útil'",
    "autor" => "Juan O'Connor",
    "tags" => ["php", "json", "tutorial"],
    "url" => "https://example.com/articulo?id=123&lang=es",
    "publicado" => true,
    "vistas" => 1024,
    "comentarios" => [
        ["usuario" => "Pedro", "texto" => "¡Gran artículo!"],
        ["usuario" => "Luisa", "texto" => "Muy útil, gracias."]
    ],
    "metadata" => (object)["version" => 1.2, "revisado" => true] // Objeto dentro del array
];

echo "<div class='php-code'><strong>Datos PHP complejos:</strong><pre>" . htmlspecialchars(print_r($datosComplejos, true)) . "</pre></div>";

// --- 4.1. JSON_PRETTY_PRINT (PHP 5.4+) ---
echo "<h3>4.1. <code>JSON_PRETTY_PRINT</code></h3>";
echo "<p>Formatea el JSON resultante para que sea legible por humanos (con indentación y saltos de línea).</p>";
$jsonBonito = json_encode($datosComplejos, JSON_PRETTY_PRINT);
echo "<div class='php-code'><strong>Con <code>JSON_PRETTY_PRINT</code>:</strong><pre>" . htmlspecialchars($jsonBonito, ENT_NOQUOTES) . "</pre></div>";

// --- 4.2. JSON_UNESCAPED_SLASHES ---
echo "<h3>4.2. <code>JSON_UNESCAPED_SLASHES</code></h3>";
echo "<p>Por defecto, las barras inclinadas (/) se escapan (ej. \<code>/url\</code>). Esta opción las deja sin escapar.</p>";
$jsonSinEscaparBarras = json_encode($datosComplejos, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
echo "<div class='php-code'><strong>Con <code>JSON_UNESCAPED_SLASHES</code> (y PRETTY_PRINT):</strong><pre>" . htmlspecialchars($jsonSinEscaparBarras, ENT_NOQUOTES) . "</pre></div>";

// --- 4.3. JSON_UNESCAPED_UNICODE ---
echo "<h3>4.3. <code>JSON_UNESCAPED_UNICODE</code></h3>";
echo "<p>Por defecto, los caracteres multibyte (como ñ, á, é) se escapan (ej. \<code>\\u00f1\</code>). Esta opción los deja como están (útil si el destino soporta UTF-8).</p>";
$datosConUnicode = ["nombre" => "Ñandú Programación", "país" => "España"];
echo "<div class='php-code'><strong>Sin <code>JSON_UNESCAPED_UNICODE</code> (por defecto):</strong><pre>" . htmlspecialchars(json_encode($datosConUnicode, JSON_PRETTY_PRINT), ENT_NOQUOTES) . "</pre></div>";
$jsonSinEscaparUnicode = json_encode($datosConUnicode, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
echo "<div class='php-code'><strong>Con <code>JSON_UNESCAPED_UNICODE</code> (y PRETTY_PRINT):</strong><pre>" . htmlspecialchars($jsonSinEscaparUnicode, ENT_NOQUOTES) . "</pre></div>";

// --- 4.4. JSON_HEX_TAG, JSON_HEX_AMP, JSON_HEX_APOS, JSON_HEX_QUOT ---
echo "<h3>4.4. Opciones de escape para HTML</h3>";
echo "<p>Estas opciones convierten caracteres especiales de HTML (<, >, &, ', \") en sus equivalentes hexadecimales (ej. \<code>\\u003C\</code>). Útil si el JSON se va a incrustar directamente en HTML/JavaScript.</p>";
$datosHtmlSensibles = ["html_content" => "<script>alert('XSS & \"ataque\"!');</script>", "apostrofe" => "O'Malley"];
$jsonEscapadoHtml = json_encode(
    $datosHtmlSensibles,
    JSON_PRETTY_PRINT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
);
echo "<div class='php-code'><strong>Con <code>JSON_HEX_*</code> (y PRETTY_PRINT):</strong><pre>" . htmlspecialchars($jsonEscapadoHtml, ENT_NOQUOTES) . "</pre></div>";

// --- 4.5. JSON_FORCE_OBJECT ---
echo "<h3>4.5. <code>JSON_FORCE_OBJECT</code></h3>";
echo "<p>Fuerza que un array indexado vacío o no vacío se codifique como un objeto JSON ({}) en lugar de un array JSON ([]).</p>";
$arrayVacio = [];
$arrayIndexadoSimple = [10, 20, 30];
echo "<div class='php-code'><strong>Array vacío (por defecto):</strong><pre>" . htmlspecialchars(json_encode($arrayVacio), ENT_NOQUOTES) . "</pre></div>";
echo "<div class='php-code'><strong>Array vacío (con <code>JSON_FORCE_OBJECT</code>):</strong><pre>" . htmlspecialchars(json_encode($arrayVacio, JSON_FORCE_OBJECT), ENT_NOQUOTES) . "</pre></div>";
echo "<div class='php-code'><strong>Array indexado simple (por defecto):</strong><pre>" . htmlspecialchars(json_encode($arrayIndexadoSimple), ENT_NOQUOTES) . "</pre></div>";
echo "<div class='php-code'><strong>Array indexado simple (con <code>JSON_FORCE_OBJECT</code>):</strong><pre>" . htmlspecialchars(json_encode($arrayIndexadoSimple, JSON_FORCE_OBJECT), ENT_NOQUOTES) . "</pre></div>";


// --- 5. Manejo de Errores en json_encode() ---
echo "<h2>5. Manejo de Errores en <code>json_encode()</code></h2>";
echo "<p>Si <code>json_encode()</code> falla (ej. por datos no codificables en UTF-8, o recursión excesiva), devuelve <code>false</code>.</p>";
echo "<p>Se puede usar <code>json_last_error()</code> para obtener el código del error y <code>json_last_error_msg()</code> (PHP 5.5+) para el mensaje.</p>";

// Simular un error: datos no UTF-8 (esto es difícil de simular directamente sin extensiones como mbstring o iconv)
// Un ejemplo más común es la recursión infinita o profundidad máxima excedida.
// $datosRecursivos = [];
// $datosRecursivos['yoMismo'] = &$datosRecursivos;
// $jsonErrorRecursion = json_encode($datosRecursivos); // Esto daría error

// Simular datos que podrían no ser UTF-8 válidos (ej. desde una BD con codificación incorrecta)
$stringLatin1ConError = "\xE1\xE9\xED\xF3\xFA"; // áéíóú en ISO-8859-1 (Latin-1)
// Si el script es UTF-8, json_encode podría fallar o convertirlo a \uFFFD (carácter de reemplazo)
// Para que falle consistentemente, necesitaríamos iconv o mb_convert_encoding para crear un string malformado.
// Por simplicidad, vamos a asumir que tenemos un string que no es UTF-8 válido.
// $jsonConErrorUtf8 = json_encode(["nombre" => $stringLatin1ConError]); // Podría devolver false

// Forzar un error de profundidad máxima para demostración (requiere una estructura muy anidada)
$profundidad = [];
$actual = &$profundidad;
for ($i = 0; $i < 520; $i++) { // El límite por defecto suele ser 512
    $actual['anidado'] = [];
    $actual = &$actual['anidado'];
}
$jsonErrorProfundidad = json_encode($profundidad);

if ($jsonErrorProfundidad === false) {
    $codigoError = json_last_error();
    $mensajeError = json_last_error_msg(); // PHP 5.5+
    echo "<div class='message error'>";
    echo "<strong>Error al codificar a JSON (simulado por profundidad):</strong><br>";
    echo "Código de Error: " . htmlspecialchars($codigoError) . "<br>";
    echo "Mensaje de Error: " . htmlspecialchars($mensajeError) . "<br>";
    // Comparar con constantes de error JSON: JSON_ERROR_NONE, JSON_ERROR_DEPTH, JSON_ERROR_UTF8, etc.
    if ($codigoError === JSON_ERROR_DEPTH) {
        echo "<em>El error fue debido a exceder la profundidad máxima de anidamiento.</em>";
    }
    echo "</div>";
} else {
    echo "<p class='message info'>La simulación de error de profundidad no funcionó como se esperaba o <code>json_encode</code> manejó la estructura profunda.</p>";
    // echo "<pre>" . htmlspecialchars($jsonErrorProfundidad) . "</pre>"; // Sería muy largo
}


echo "<hr>";
echo "<p><a href='index.php'>Volver al índice de JSON</a> (si existe)</p>";
echo "<p><a href='json_decode.php'>Ir a Decodificar JSON (json_decode)</a></p>";

echo "</div></body></html>";
?>
