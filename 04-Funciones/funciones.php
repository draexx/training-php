<?php
// TEMA: FUNCIONES EN PHP

echo "<h1>Funciones en PHP</h1>";

// ========= DEFINICIÓN Y LLAMADA DE FUNCIONES =========
echo "<h2>Definición y Llamada de Funciones</h2>";

// Función simple sin parámetros ni valor de retorno
function saludar() {
    echo "¡Hola desde la función saludar!<br/>";
}
saludar(); // Llamada a la función

// Función con parámetros
function saludarPersona($nombre) {
    echo "¡Hola, " . htmlspecialchars($nombre) . "!<br/>";
}
saludarPersona("Carlos");
saludarPersona("Ana");

// ========= ARGUMENTOS DE FUNCIONES =========
echo "<h2>Argumentos de Funciones</h2>";

// --- Argumentos por Valor (por defecto) ---
echo "<h3>Argumentos por Valor</h3>";
function incrementarPorValor($numero) {
    $numero++; // Modifica la copia local de $numero
    echo "Dentro de la función (por valor): " . $numero . "<br/>";
}
$miNumero = 5;
echo "Antes de llamar a la función: " . $miNumero . "<br/>";
incrementarPorValor($miNumero);
echo "Después de llamar a la función: " . $miNumero . " (sigue siendo el mismo, se pasó por valor)<br/>";

// --- Argumentos por Referencia ---
// Se antepone un ampersand (&) al parámetro en la definición de la función.
echo "<h3>Argumentos por Referencia</h3>";
function incrementarPorReferencia(&$numero) {
    $numero++; // Modifica la variable original
    echo "Dentro de la función (por referencia): " . $numero . "<br/>";
}
$otroNumero = 10;
echo "Antes de llamar a la función: " . $otroNumero . "<br/>";
incrementarPorReferencia($otroNumero);
echo "Después de llamar a la función: " . $otroNumero . " (se modificó la original)<br/>";

// --- Valores Predeterminados para Argumentos ---
echo "<h3>Valores Predeterminados para Argumentos</h3>";
function describirProducto($nombre, $categoria = "General") {
    echo "Producto: " . htmlspecialchars($nombre) . ", Categoría: " . htmlspecialchars($categoria) . "<br/>";
}
describirProducto("Laptop HP"); // Usa el valor predeterminado para categoría
describirProducto("Mouse Logitech", "Periféricos"); // Proporciona un valor para categoría

// --- Tipado de Argumentos (Type Hinting) - Desde PHP 5 ---
// Ayuda a asegurar que los argumentos sean del tipo esperado.
echo "<h3>Tipado de Argumentos (Type Hinting)</h3>";
function procesarNumero(int $numero) {
    echo "Número procesado (int): " . ($numero * 2) . "<br/>";
}
procesarNumero(10);
// procesarNumero("texto"); // Esto generaría un TypeError en PHP 7+ (o un error fatal recuperable antes)

function procesarArray(array $datos) {
    echo "Primer elemento del array: " . htmlspecialchars($datos[0] ?? 'N/A') . "<br/>";
}
procesarArray(["manzana", "banana"]);
// procesarArray("no es un array"); // TypeError

// También se puede tipar con clases, interfaces, callable, iterable, object, self, parent.
class MiClase { public $prop = "valor"; }
function procesarObjeto(MiClase $obj) {
    echo "Propiedad del objeto: " . htmlspecialchars($obj->prop) . "<br/>";
}
$miObj = new MiClase();
procesarObjeto($miObj);


// ========= VALORES DE RETORNO =========
echo "<h2>Valores de Retorno</h2>";
function sumar($a, $b) {
    $resultado = $a + $b;
    return $resultado; // Devuelve el valor de $resultado
    // echo "Esto no se ejecutará"; // Código después de return no se ejecuta
}
$sumaTotal = sumar(5, 3);
echo "La suma de 5 y 3 es: " . $sumaTotal . "<br/>";
echo "Directamente: " . sumar(10, 20) . "<br/>";

// --- Tipado de Retorno (Return Type Declarations) - Desde PHP 7 ---
echo "<h3>Tipado de Retorno</h3>";
function multiplicar(int $a, int $b): int { // La función DEBE retornar un entero
    return $a * $b;
}
$producto = multiplicar(6, 7);
echo "El producto de 6 y 7 es: " . $producto . " (Tipo: " . gettype($producto) . ")<br/>";

function obtenerNombre(): string {
    return "Juan Pérez";
}
$nombreCompleto = obtenerNombre();
echo "Nombre obtenido: " . htmlspecialchars($nombreCompleto) . "<br/>";

// Puede retornar tipos "nullable" (que pueden ser null o el tipo especificado) usando ?Tipo
function encontrarUsuario(int $id): ?string { // Puede retornar string o null
    if ($id === 1) {
        return "Usuario Ejemplo";
    }
    return null;
}
$usuario1 = encontrarUsuario(1);
$usuario2 = encontrarUsuario(2);
echo "Usuario 1: " . ($usuario1 ?? 'No encontrado') . "<br/>";
echo "Usuario 2: " . var_export($usuario2, true) . " (Tipo: " . gettype($usuario2) . ")<br/>";


// ========= FUNCIONES ANÓNIMAS (CLOSURES) =========
// Funciones sin nombre, a menudo asignadas a variables o pasadas como callbacks.
echo "<h2>Funciones Anónimas (Closures)</h2>";
$miClosure = function($mensaje) {
    echo "Mensaje desde closure: " . htmlspecialchars($mensaje) . "<br/>";
}; // El punto y coma es necesario aquí porque es una asignación de expresión.

$miClosure("Hola Closure");

// Uso común: callbacks
$numeros = [1, 2, 3, 4, 5];
$numerosDuplicados = array_map(function($n) {
    return $n * 2;
}, $numeros);

echo "Números originales: " . implode(", ", $numeros) . "<br/>";
echo "Números duplicados (usando closure con array_map): " . implode(", ", $numerosDuplicados) . "<br/>";

// --- `use` para heredar variables del ámbito padre ---
$factor = 10;
$multiplicarPorFactor = function($numero) use ($factor) {
    return $numero * $factor;
};
echo "Multiplicar 5 por el factor " . $factor . ": " . $multiplicarPorFactor(5) . "<br/>";
// $factor = 100; // Cambiar $factor aquí no afecta el $factor capturado por la closure (captura por valor)
// Para capturar por referencia: use (&$factor)


// ========= FUNCIONES DE FLECHA (ARROW FUNCTIONS) - Desde PHP 7.4 =========
// Sintaxis más concisa para funciones anónimas simples.
// Capturan variables del ámbito padre automáticamente (por valor).
echo "<h2>Funciones de Flecha (Arrow Functions)</h2>";

$numerosParaFlecha = [1, 2, 3, 4, 5];
$numerosTriplicados = array_map(fn($n) => $n * 3, $numerosParaFlecha);
echo "Números triplicados (usando función de flecha): " . implode(", ", $numerosTriplicados) . "<br/>";

$otroFactor = 5;
$sumarFactor = fn($n) => $n + $otroFactor; // $otroFactor se captura automáticamente por valor
echo "Sumar " . $otroFactor . " a 10: " . $sumarFactor(10) . "<br/>";
// $otroFactor = 50; // No afecta a la función de flecha ya definida.


// ========= FUNCIONES VARIABLES =========
echo "<h2>Funciones Variables</h2>";
// Si una variable tiene el nombre de una función, se puede llamar a la función usando la variable seguida de paréntesis.
function miFuncionVariable() {
    echo "¡Llamada a través de una función variable!<br/>";
}
$nombreDeFuncion = "miFuncionVariable";
$nombreDeFuncion(); // Llama a miFuncionVariable()

if (function_exists($nombreDeFuncion)) {
    echo "La función '$nombreDeFuncion' existe.<br/>";
} else {
    echo "La función '$nombreDeFuncion' NO existe.<br/>";
}

// ========= FUNCIONES INTERNAS DE PHP (Built-in) =========
echo "<h2>Funciones Internas de PHP</h2>";
echo "PHP tiene miles de funciones internas para diversas tareas.<br/>";
// Ejemplos ya usados: echo, print_r, gettype, isset, empty, count, implode, array_map, htmlspecialchars, etc.
$texto = "hola mundo";
echo "Texto original: " . $texto . "<br/>";
echo "En mayúsculas: " . strtoupper($texto) . "<br/>";
echo "Longitud: " . strlen($texto) . "<br/>";
echo "Fecha actual: " . date("Y-m-d H:i:s") . "<br/>";


echo "<br/><hr/>Fin del script de funciones.<br/>";
?>
