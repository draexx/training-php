<?php
// TEMA: VARIABLES Y CONSTANTES EN PHP

echo "<h1>Variables y Constantes en PHP</h1>";

// ========= VARIABLES =========
echo "<h2>Variables</h2>";

// --- Tipos de Datos ---
echo "<h3>Tipos de Datos</h3>";

// Integer (Entero)
$edad = 30;
echo "Edad (Integer): " . $edad . " (Tipo: " . gettype($edad) . ")<br/>";

// Float (Decimal)
$precio = 19.99;
echo "Precio (Float): " . $precio . " (Tipo: " . gettype($precio) . ")<br/>";

// String (Cadena de texto)
$nombre = "Ana García";
$mensaje = 'Bienvenida a PHP'; // Comillas simples también son válidas
echo "Nombre (String): " . $nombre . "<br/>";
echo "Mensaje (String): " . $mensaje . "<br/>";

// Boolean (Booleano)
$esValido = true;
$estaActivo = false;
echo "Es válido (Boolean): " . ($esValido ? 'true' : 'false') . " (Tipo: " . gettype($esValido) . ")<br/>"; // Se usa un ternario para mostrar 'true' o 'false'
echo "Está activo (Boolean): " . var_export($estaActivo, true) . " (Tipo: " . gettype($estaActivo) . ")<br/>"; // var_export también es útil

// Array (Arreglo)
$colores = ["rojo", "verde", "azul"];
$persona = [
    "nombre" => "Carlos",
    "edad" => 25,
    "ciudad" => "Madrid"
];
echo "Colores (Array Indexado): ";
print_r($colores);
echo "<br/>";
echo "Persona (Array Asociativo): ";
print_r($persona);
echo "<br/>";
echo "Nombre de la persona: " . $persona["nombre"] . "<br/>";

// Object (Objeto)
class Producto {
    public $nombre;
    public $precio;

    public function __construct($nombre, $precio) {
        $this->nombre = $nombre;
        $this->precio = $precio;
    }

    public function mostrarInfo() {
        return "Producto: " . $this->nombre . ", Precio: $" . $this->precio;
    }
}
$producto1 = new Producto("Laptop", 1200.50);
echo "Producto1 (Object): " . $producto1->mostrarInfo() . " (Tipo: " . gettype($producto1) . ")<br/>";

// NULL (Nulo)
$sinValor = null;
echo "Variable sin valor (NULL): " . var_export($sinValor, true) . " (Tipo: " . gettype($sinValor) . ")<br/>";
$variableNoDefinida; // No se le asigna valor, PHP le asignará NULL y podría generar un Notice.
// echo "Variable no definida: " . @$variableNoDefinida . "<br/>"; // Usar @ para suprimir el Notice (no recomendado en producción)


// --- Declaración y Asignación ---
echo "<h3>Declaración y Asignación</h3>";
$miVariable = "Valor inicial"; // Declaración y asignación
echo "Mi variable: " . $miVariable . "<br/>";
$miVariable = "Nuevo valor";   // Reasignación
echo "Mi variable (reasignada): " . $miVariable . "<br/>";


// --- Ámbito de las Variables (Scope) ---
echo "<h3>Ámbito de las Variables (Scope)</h3>";

$variableGlobal = "Soy global";

function miFuncion() {
    $variableLocal = "Soy local";
    echo "Dentro de la función: " . $variableLocal . "<br/>";
    // echo "Intentando acceder a variable global directamente: " . $variableGlobal; // Esto generaría un error/notice

    // Para acceder a una variable global dentro de una función, se usa la palabra clave 'global'
    global $variableGlobal;
    echo "Dentro de la función (accediendo a global): " . $variableGlobal . "<br/>";

    // Otra forma es usando el array superglobal $GLOBALS
    echo "Dentro de la función (accediendo a global con \$GLOBALS): " . $GLOBALS['variableGlobal'] . "<br/>";
}

miFuncion();
echo "Fuera de la función: " . $variableGlobal . "<br/>";
// echo "Intentando acceder a variable local fuera de la función: " . $variableLocal; // Esto generaría un error/notice


// ========= CONSTANTES =========
echo "<h2>Constantes</h2>";
echo "<h3>Definición y Uso</h3>";

// Definición con define() - sensible a mayúsculas/minúsculas por defecto
define("SALUDO", "Hola a todos");
echo SALUDO . "<br/>";

// Definición con const (preferida para clases y namespaces, y desde PHP 5.3 para constantes globales)
const PI = 3.14159;
echo "Valor de PI: " . PI . "<br/>";

// Intento de redefinir una constante (generará un error)
// define("SALUDO", "Nuevo saludo"); // Error: Constant SALUDO already defined
// const PI = 3.14; // Error: Cannot redeclare constant PI

// Sensibilidad de mayúsculas/minúsculas
define("MENSAJE_CASESENSITIVE", "Soy sensible", false); // El tercer parámetro 'true' la haría case-insensitive (no recomendado)
echo MENSAJE_CASESENSITIVE . "<br/>";
// echo mensaje_casesensitive; // Error si no se definió como case-insensitive

// Constantes en Clases (se verán más en POO)
class Matematicas {
    const VERSION = "1.0";
}
echo "Versión de la clase Matemáticas: " . Matematicas::VERSION . "<br/>";


// ========= VARIABLES PREDEFINIDAS DE PHP =========
echo "<h2>Variables Predefinidas (Superglobals)</h2>";
echo "<i>Nota: El contenido de estas variables depende del entorno de ejecución (servidor web, CLI, etc.)</i><br/>";

echo "<h3>\$_SERVER</h3>";
echo "Nombre del script: " . $_SERVER['PHP_SELF'] . "<br/>";
echo "Nombre del servidor: " . $_SERVER['SERVER_NAME'] . "<br/>";
// echo "Query String: " . $_SERVER['QUERY_STRING'] . "<br/>"; // Descomentar si se ejecuta con query string
// print_r($_SERVER); // Muestra todas las variables del servidor

echo "<h3>\$_GET (Ejemplo: ?nombre=Juan&edad=25)</h3>";
// Para probar, accede a este script con: variables.php?nombre=Juan&edad=25
if (isset($_GET['nombre'])) {
    echo "Nombre desde GET: " . htmlspecialchars($_GET['nombre']) . "<br/>";
} else {
    echo "El parámetro 'nombre' no se recibió por GET.<br/>";
}

echo "<h3>\$_POST (Generalmente desde formularios)</h3>";
// Se necesita un formulario HTML que envíe datos por POST a este script.
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['usuario'])) {
    echo "Usuario desde POST: " . htmlspecialchars($_POST['usuario']) . "<br/>";
} else {
    echo "No se recibieron datos por POST para 'usuario' o no es una petición POST.<br/>";
}

echo "<h3>\$_REQUEST (Contiene \$_GET, \$_POST y \$_COOKIE)</h3>";
if (isset($_REQUEST['nombre'])) { // Si vino por GET en el ejemplo anterior
    echo "Nombre desde REQUEST (proveniente de GET): " . htmlspecialchars($_REQUEST['nombre']) . "<br/>";
}

// Ejemplo de formulario para probar $_POST
echo <<<HTML
<hr>
<h4>Formulario de prueba para \$_POST</h4>
<form method="POST" action="">
    <label for="usuario">Usuario:</label>
    <input type="text" id="usuario" name="usuario" value="TestUsuario">
    <br>
    <label for="clave">Clave:</label>
    <input type="password" id="clave" name="clave" value="1234">
    <br>
    <input type="submit" value="Enviar por POST">
</form>
HTML;

echo "<br/><hr/>Fin del script de variables y constantes.<br/>";
?>
