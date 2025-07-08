<?php
// TEMA: MANEJO DE ERRORES Y EXCEPCIONES - Errores Básicos y Niveles de Error

// En PHP, los errores pueden ocurrir por diversas razones: sintaxis incorrecta,
// intentar usar una variable no definida, problemas con archivos, etc.
// PHP tiene diferentes niveles de error para clasificar la severidad.

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>Errores Básicos y Niveles de Error en PHP</title>
    <style>
        body { font-family: sans-serif; padding: 20px; line-height: 1.6; }
        .container { max-width: 800px; margin: auto; background: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        h1, h2, h3 { color: #333; }
        pre { background-color: #eee; padding: 10px; border: 1px solid #ccc; overflow-x:auto; white-space: pre-wrap; word-wrap: break-word;}
        .message { padding: 10px; margin-bottom:15px; border-radius:4px; }
        .message.error { background-color: #f8d7da; border-color: #f5c6cb; color: #721c24; }
        .message.warning { background-color: #fff3cd; border-color: #ffeeba; color: #856404; }
        .message.notice { background-color: #e2e3e5; border-color: #d6d8db; color: #383d41; }
        .message.info { background-color: #e7f3fe; border-left: 6px solid #2196F3;}
        .code-example { background-color: #272822; color: #f8f8f2; padding: 15px; border-radius: 4px; margin-bottom: 10px; font-family: monospace;}
    </style>
</head>
<body>
<div class='container'>";

echo "<h1>Errores Básicos y Niveles de Error en PHP</h1>";

// --- Configuración del Reporte de Errores ---
// error_reporting() define qué errores son reportados.
// display_errors (directiva de php.ini) define si los errores se muestran en pantalla.
// En desarrollo: es útil mostrar todos los errores.
// error_reporting(E_ALL);
// ini_set('display_errors', 1); // 1 para mostrar, 0 para ocultar

// En producción: se deben ocultar los errores al usuario y registrarlos en un log.
// error_reporting(E_ALL); // Reportar todos, pero...
// ini_set('display_errors', 0); // ...no mostrarlos.
// ini_set('log_errors', 1); // ...sí registrarlos en el log de errores del servidor.
// ini_set('error_log', '/ruta/a/tu/php-error.log'); // Especificar archivo de log

echo "<p class='message info'>La configuración de cómo se reportan y muestran los errores (<code>error_reporting</code>, <code>display_errors</code>) es crucial. En desarrollo, muestra todo. En producción, oculta los detalles al usuario y regístralos en logs.</p>";
echo "<p>Para esta demostración, <code>display_errors</code> está probablemente activado para que veas los errores.</p>";

// ========= NIVELES DE ERROR COMUNES =========

// --- 1. E_NOTICE (Notificación) ---
echo "<h2>1. E_NOTICE (Notificación)</h2>";
echo "<p>Errores no críticos que indican algo que podría ser un problema, pero el script generalmente continúa su ejecución. Ejemplo: usar una variable no definida.</p>";
echo "<div class='code-example'>
\$variableNoDefinida = \$contadorNoDefinido + 5; // \$contadorNoDefinido no existe<br>
echo \$variableNoDefinida;
</div>";
echo "<p><strong>Resultado (si display_errors está on y E_NOTICE reportado):</strong></p>";
echo "<div class='message notice'>";
// Para simular el error sin romper el script si las notificaciones están desactivadas, usamos @ y luego mostramos un mensaje.
$valorOriginalErrorReporting = error_reporting(); // Guardar config actual
error_reporting(E_ALL); // Asegurar que E_NOTICE se reporta para este bloque
ini_set('display_errors', 1); // Asegurar que se muestra para este bloque

// @ suprime el error de esta línea específica, pero podemos capturarlo si quisiéramos con un manejador de errores.
// Aquí, solo lo usamos para evitar que rompa la página si las notificaciones están globalmente desactivadas.
@$variableNoDefinida = $contadorNoDefinido + 5; // $contadorNoDefinido no está definida
// echo $variableNoDefinida; // Esto también daría notice por $variableNoDefinida si $contadorNoDefinido no existe

echo "PHP Notice: Undefined variable: contadorNoDefinido in ... on line ...<br>";
echo "PHP Notice: Undefined variable: variableNoDefinida in ... on line ... (si se intenta usar después)<br>";
echo "<em>(Este es un mensaje simulado del error que aparecería)</em>";

error_reporting($valorOriginalErrorReporting); // Restaurar config original
ini_set('display_errors', (bool)$valorOriginalErrorReporting); // Restaurar display_errors
echo "</div>";
echo "<p>Es buena práctica inicializar todas las variables antes de usarlas para evitar E_NOTICE.</p>";
echo "<hr/>";


// --- 2. E_WARNING (Advertencia) ---
echo "<h2>2. E_WARNING (Advertencia)</h2>";
echo "<p>Errores más serios que E_NOTICE, pero que generalmente no detienen la ejecución del script. Ejemplo: incluir un archivo inexistente con <code>include</code>, o usar mal una función.</p>";
echo "<div class='code-example'>
@include 'archivo_que_no_existe.php'; // @ suprime la salida del error aquí<br>
\$resultado = 10 / 0; // División por cero
</div>";
echo "<p><strong>Resultado (si display_errors está on y E_WARNING reportado):</strong></p>";
echo "<div class='message warning'>";
@include 'archivo_que_no_existe.php'; // Esto generaría un E_WARNING
echo "PHP Warning: include(archivo_que_no_existe.php): Failed to open stream: No such file or directory in ... on line ...<br>";
echo "PHP Warning: include(): Failed opening 'archivo_que_no_existe.php' for inclusion (include_path='...') in ... on line ...<br>";

// División por cero (genera E_WARNING en PHP < 8, DivisionByZeroError en PHP 8+)
error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT); // Para asegurar que E_WARNING se muestre si es PHP < 8
ini_set('display_errors', 1);
if (PHP_VERSION_ID < 80000) {
    @$resultadoDivision = 10 / 0;
    echo "PHP Warning: Division by zero in ... on line ... (en PHP < 8.0)<br>";
} else {
    try {
        $resultadoDivision = 10 / 0;
    } catch (DivisionByZeroError $e) {
        echo "PHP Fatal error (DivisionByZeroError): " . htmlspecialchars($e->getMessage()) . " in ... on line ... (en PHP >= 8.0)<br>";
    }
}
error_reporting($valorOriginalErrorReporting);
ini_set('display_errors', (bool)$valorOriginalErrorReporting);
echo "<em>(Este es un mensaje simulado del error que aparecería)</em>";
echo "</div>";
echo "<p>Aunque el script pueda continuar, los E_WARNING suelen indicar problemas que deben ser corregidos.</p>";
echo "<hr/>";


// --- 3. E_PARSE (Error de Análisis Sintáctico) ---
echo "<h2>3. E_PARSE (Error de Análisis Sintáctico)</h2>";
echo "<p>Ocurre cuando hay un error en la sintaxis del código PHP. Estos errores son fatales y detienen la ejecución del script antes de que comience.</p>";
echo "<div class='code-example'>
// echo 'Hola Mundo' // Falta el punto y coma, esto causaría un E_PARSE<br>
// if (true) { echo 'Dentro del if'; // Falta la llave de cierre }
</div>";
echo "<p><strong>Resultado:</strong></p>";
echo "<div class='message error'>";
echo "PHP Parse error: syntax error, unexpected end of file (o similar) in ... on line ...<br>";
echo "<em>(No podemos simular un E_PARSE real aquí porque detendría este script. Este es un mensaje de ejemplo.)</em>";
echo "</div>";
echo "<p>Los errores de parse deben corregirse para que el script pueda ejecutarse.</p>";
echo "<hr/>";


// --- 4. E_ERROR / E_RECOVERABLE_ERROR (Error Fatal) ---
echo "<h2>4. E_ERROR / E_RECOVERABLE_ERROR (Error Fatal)</h2>";
echo "<p>Errores críticos que detienen la ejecución del script. <code>E_ERROR</code> no puede ser capturado por un manejador de errores personalizado tradicional (<code>set_error_handler</code>), pero <code>E_RECOVERABLE_ERROR</code> sí (y si no se maneja, se convierte en un E_ERROR).</p>";
echo "<p>Ejemplos: llamar a una función no definida, instanciar una clase no definida, errores de tipo en PHP 7+ (TypeError).</p>";
echo "<div class='code-example'>
// funcion_que_no_existe(); // Esto causaría un E_ERROR<br>
// \$objeto = new ClaseInexistente(); // E_ERROR<br>
// function sumar(int \$a, int \$b): int { return \$a + \$b; }<br>
// sumar(\"texto\", 5); // TypeError (subclase de Error) en PHP 7+
</div>";
echo "<p><strong>Resultado (si ocurre un E_ERROR):</strong></p>";
echo "<div class='message error'>";
echo "PHP Fatal error: Uncaught Error: Call to undefined function funcion_que_no_existe() in ... on line ...<br>";
// O para TypeError:
// PHP Fatal error: Uncaught TypeError: Argument 1 passed to sumar() must be of the type int, string given ...
echo "<em>(No podemos simular un E_ERROR real aquí porque detendría este script. Este es un mensaje de ejemplo.)</em>";
echo "</div>";
echo "<p>En PHP 7+, muchos errores fatales antiguos ahora lanzan Excepciones de tipo <code>Error</code> (que es diferente de <code>Exception</code> pero ambas pueden ser capturadas con <code>catch (Throwable \$t)</code>).</p>";
echo "<hr/>";


// --- Operador de Control de Errores @ ---
echo "<h2>Operador de Control de Errores @</h2>";
echo "<p>El operador <code>@</code>, cuando se antepone a una expresión en PHP, suprime cualquier mensaje de error que esa expresión pueda generar.</p>";
echo "<div class='code-example'>
\$valor = @file_get_contents('archivo_muy_inexistente.txt'); // Suprime el warning si el archivo no existe<br>
if (\$valor === false) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;echo \"No se pudo leer el archivo (manejado manualmente).\";<br>
}
</div>";
$valor = @file_get_contents('archivo_muy_inexistente.txt');
if ($valor === false) {
    echo "<p class='message info'>Resultado: No se pudo leer el archivo 'archivo_muy_inexistente.txt' (manejado manualmente después de usar @).</p>";
}
echo "<p><strong>Precaución:</strong> Usar <code>@</code> puede dificultar la depuración, ya que oculta problemas. Es mejor usarlo con moderación y solo cuando se tiene un plan claro para manejar el error inmediatamente después (como en el ejemplo de <code>file_get_contents</code>).</p>";


echo "<hr>";
echo "<p><a href='index.php'>Volver al índice de Manejo de Errores</a> (si existe)</p>";
echo "<p><a href='set_error_handler.php'>Ir a Manejadores de Errores Personalizados (set_error_handler)</a></p>";

echo "</div></body></html>";
?>
