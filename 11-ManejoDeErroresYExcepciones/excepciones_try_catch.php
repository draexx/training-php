<?php
// TEMA: MANEJO DE ERRORES Y EXCEPCIONES - Excepciones (try, catch, finally, throw)

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>Manejo de Excepciones en PHP</title>
    <style>
        body { font-family: sans-serif; padding: 20px; line-height: 1.6; }
        .container { max-width: 800px; margin: auto; background: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        h1, h2, h3 { color: #333; }
        pre { background-color: #eee; padding: 10px; border: 1px solid #ccc; overflow-x:auto; white-space: pre-wrap; word-wrap: break-word;}
        .message { padding: 10px; margin-bottom:15px; border-radius:4px; }
        .message.error { background-color: #f8d7da; border-color: #f5c6cb; color: #721c24; }
        .message.success { background-color: #d4edda; border-color: #c3e6cb; color: #155724; }
        .message.info { background-color: #e7f3fe; border-left: 6px solid #2196F3;}
        .message.finally { background-color: #d1ecf1; border-color: #bee5eb; color: #0c5460; }
        .code-example { background-color: #272822; color: #f8f8f2; padding: 15px; border-radius: 4px; margin-bottom: 10px; font-family: monospace;}
    </style>
</head>
<body>
<div class='container'>";

echo "<h1>Manejo de Excepciones en PHP (<code>try</code>, <code>catch</code>, <code>finally</code>, <code>throw</code>)</h1>";

echo "<p class='message info'>Las excepciones son un mecanismo para manejar errores y situaciones inesperadas de una manera más estructurada y orientada a objetos. Permiten separar la lógica de manejo de errores del código principal.</p>";

// --- Clase de Excepción Personalizada (Opcional) ---
// Puedes crear tus propias clases de excepción extendiendo la clase base `Exception` (o sus subclases como `RuntimeException`, `LogicException`, etc.)
// Esto permite un manejo de errores más granular.
class MiExcepcionPersonalizada extends Exception {
    public function __construct($message, $code = 0, Throwable $previous = null) {
        // Puedes añadir lógica personalizada aquí
        parent::__construct($message, $code, $previous);
    }

    public function obtenerMensajeAmigable() {
        return "Ha ocurrido un error específico de mi aplicación: " . $this->getMessage();
    }
}

// --- Función que podría lanzar una excepción ---
function dividir($numerador, $denominador) {
    if ($denominador == 0) {
        // 1. Lanzar una excepción usando `throw`
        // Se puede lanzar cualquier objeto que sea instancia de `Throwable` (Exception o Error).
        throw new InvalidArgumentException("El denominador no puede ser cero.");
    }
    if (!is_numeric($numerador) || !is_numeric($denominador)) {
        throw new MiExcepcionPersonalizada("Ambos operandos deben ser numéricos.");
    }
    return $numerador / $denominador;
}


// ========= Bloque try...catch =========
echo "<h2>1. Bloque <code>try...catch</code> Básico</h2>";
echo "<p>El código que podría lanzar una excepción se coloca dentro de un bloque <code>try</code>. Si una excepción es lanzada, la ejecución del bloque <code>try</code> se detiene y PHP busca un bloque <code>catch</code> compatible.</p>";
echo "<div class='code-example'>
try {<br>
&nbsp;&nbsp;&nbsp;&nbsp;\$resultado = dividir(10, 0); // Esto lanzará una InvalidArgumentException<br>
&nbsp;&nbsp;&nbsp;&nbsp;echo \"Resultado (no se mostrará si hay excepción): \$resultado\";<br>
} catch (InvalidArgumentException \$e) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;echo \"Excepción capturada: \" . \$e->getMessage();<br>
}
</div>";

try {
    echo "<p>Intentando dividir 10 / 2:</p>";
    $resultado1 = dividir(10, 2);
    echo "<p class='message success'>Resultado de 10 / 2: " . htmlspecialchars($resultado1) . "</p>";

    echo "<p>Intentando dividir 10 / 0:</p>";
    $resultado2 = dividir(10, 0); // Esto lanzará la excepción
    echo "<p class='message success'>Resultado de 10 / 0 (no se ejecutará): " . htmlspecialchars($resultado2) . "</p>";

} catch (InvalidArgumentException $iae) { // Captura específicamente InvalidArgumentException
    echo "<p class='message error'><strong>InvalidArgumentException Capturada:</strong> " . htmlspecialchars($iae->getMessage()) . "</p>";
    // Métodos útiles de la clase Exception:
    echo "<pre>";
    echo "Archivo: " . htmlspecialchars($iae->getFile()) . "\n";
    echo "Línea: " . htmlspecialchars($iae->getLine()) . "\n";
    echo "Código: " . htmlspecialchars($iae->getCode()) . "\n"; // Código de error (generalmente 0 si no se especifica)
    // echo "Traza: \n" . htmlspecialchars($iae->getTraceAsString()) . "\n"; // Puede ser muy largo
    echo "</pre>";
} catch (Exception $e) { // Bloque catch genérico para cualquier otra excepción
    echo "<p class='message error'><strong>Excepción Genérica Capturada:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
}
echo "<hr/>";


// ========= Múltiples bloques catch =========
echo "<h2>2. Múltiples Bloques <code>catch</code></h2>";
echo "<p>Puedes tener varios bloques <code>catch</code> para manejar diferentes tipos de excepciones. Se ejecutan en orden, y solo el primer bloque compatible capturará la excepción.</p>";
echo "<div class='code-example'>
try {<br>
&nbsp;&nbsp;&nbsp;&nbsp;// ... código que puede lanzar diferentes excepciones ...<br>
&nbsp;&nbsp;&nbsp;&nbsp;\$resultado = dividir('texto', 5); // Lanzará MiExcepcionPersonalizada<br>
} catch (MiExcepcionPersonalizada \$mep) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;echo \"Capturada MiExcepcionPersonalizada: \" . \$mep->obtenerMensajeAmigable();<br>
} catch (InvalidArgumentException \$iae) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;echo \"Capturada InvalidArgumentException: \" . \$iae->getMessage();<br>
} catch (Exception \$e) { // Catch genérico al final<br>
&nbsp;&nbsp;&nbsp;&nbsp;echo \"Capturada Excepción genérica: \" . \$e->getMessage();<br>
}
</div>";

try {
    echo "<p>Intentando dividir 'abc' / 5:</p>";
    $resultado3 = dividir("abc", 5); // Esto lanzará MiExcepcionPersonalizada
    echo "<p class='message success'>Resultado de 'abc' / 5 (no se ejecutará): " . htmlspecialchars($resultado3) . "</p>";

} catch (MiExcepcionPersonalizada $mep) {
    echo "<p class='message error'><strong>MiExcepcionPersonalizada Capturada:</strong> " . htmlspecialchars($mep->obtenerMensajeAmigable()) . "</p>";
} catch (InvalidArgumentException $iae) {
    echo "<p class='message error'><strong>InvalidArgumentException Capturada (no debería llegar aquí para este caso):</strong> " . htmlspecialchars($iae->getMessage()) . "</p>";
} catch (Exception $e) { // Si no es ninguna de las anteriores, esta la captura.
    echo "<p class='message error'><strong>Excepción Genérica Capturada (inesperado para este caso):</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
}
echo "<hr/>";

// ========= Bloque finally (PHP 5.5+) =========
echo "<h2>3. Bloque <code>finally</code> (PHP 5.5+)</h2>";
echo "<p>El bloque <code>finally</code> siempre se ejecuta después de los bloques <code>try</code> y <code>catch</code>, independientemente de si se lanzó una excepción o no, o si la excepción fue capturada o no.</p>";
echo "<p>Es útil para tareas de limpieza, como cerrar conexiones de base de datos, liberar recursos, cerrar archivos, etc.</p>";
echo "<div class='code-example'>
try {<br>
&nbsp;&nbsp;&nbsp;&nbsp;// ... código ...<br>
} catch (Exception \$e) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;// ... manejar excepción ...<br>
} finally {<br>
&nbsp;&nbsp;&nbsp;&nbsp;echo \"Este bloque finally SIEMPRE se ejecuta.\";<br>
}
</div>";

$recurso = null; // Simular un recurso
try {
    echo "<p>Abriendo un 'recurso' (simulado)...</p>";
    $recurso = "RECURSO ABIERTO";
    echo "<p class='message info'>Recurso: " . htmlspecialchars($recurso) . "</p>";

    // Comenta/descomenta la siguiente línea para probar con y sin excepción:
    // throw new Exception("Una excepción de prueba para finally.");

    $resultadoDiv = dividir(20, 4);
    echo "<p class='message success'>Resultado de 20 / 4 en try-finally: " . htmlspecialchars($resultadoDiv) . "</p>";

} catch (Exception $e) {
    echo "<p class='message error'><strong>Excepción capturada en prueba de finally:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
} finally {
    // Este código se ejecuta siempre.
    echo "<div class='message message.finally'>";
    echo "<strong>BLOQUE FINALLY EJECUTADO.</strong><br>";
    if ($recurso !== null) {
        echo "Limpiando el 'recurso' (simulado): " . htmlspecialchars($recurso) . "<br>";
        $recurso = null; // Simular liberación del recurso
        echo "Recurso ahora: " . var_export($recurso, true);
    } else {
        echo "No había ningún recurso que limpiar o ya fue limpiado.";
    }
    echo "</div>";
}
echo "<hr/>";

// ========= Jerarquía de Excepciones y `Throwable` (PHP 7+) =========
echo "<h2>4. Jerarquía de Excepciones y <code>Throwable</code> (PHP 7+)</h2>";
echo "<p>En PHP 7+, tanto <code>Error</code> (para errores internos de PHP como TypeError, ParseError) como <code>Exception</code> (para excepciones de aplicación) implementan la interfaz <code>Throwable</code>.</p>";
echo "<p>Puedes usar <code>catch (Throwable \$t)</code> para capturar tanto Errores como Excepciones.</p>";
echo "<div class='code-example'>
try {<br>
&nbsp;&nbsp;&nbsp;&nbsp;// Código que podría lanzar Error o Exception<br>
&nbsp;&nbsp;&nbsp;&nbsp;// ejemplo_de_error_interno(); // Podría ser un ParseError o TypeError<br>
} catch (Throwable \$t) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;echo \"Throwable capturado: \" . \$t->getMessage();<br>
}
</div>";

try {
    // Simular un error que lanza un 'Error' (subclase de Throwable)
    // Por ejemplo, un error de tipo si las declaraciones de tipo estricto están activas
    // o llamar a una función con un tipo incorrecto.
    // Aquí, para simplificar, lanzaremos una Exception y un Error genérico para ver cómo se capturan.

    $caso = rand(1,2); // Para variar qué se lanza
    if ($caso === 1) {
        throw new Exception("Esto es una Exception de prueba para Throwable.");
    } else {
        // Para simular un Error, necesitaríamos una situación real como un TypeError.
        // Ejemplo (requeriría declare(strict_types=1); al inicio del archivo y una función con tipado):
        // function miFuncionEstricta(int $num) { return $num; }
        // miFuncionEstricta("no es un int");
        // Como no podemos declarar strict_types aquí sin afectar todo el script,
        // vamos a simularlo con un mensaje.
        echo "<p class='message info'>Simulando un 'Error' (ej. TypeError). En un caso real, esto sería lanzado por PHP.</p>";
        // throw new TypeError("Simulación de TypeError"); // No podemos instanciar TypeError directamente así.
        // En su lugar, vamos a usar una ErrorException que es hija de Exception pero puede simular un error.
        throw new ErrorException("Simulando un error que sería capturado por Throwable", 0, E_USER_ERROR);
    }

} catch (MiExcepcionPersonalizada $mep) { // Más específico primero
    echo "<p class='message error'><strong>Throwable Test - MiExcepcionPersonalizada Capturada:</strong> " . htmlspecialchars($mep->obtenerMensajeAmigable()) . "</p>";
} catch (Throwable $t) { // Captura cualquier cosa que sea Throwable (Error o Exception)
    echo "<p class='message error' style='border-color:darkmagenta; background-color:#f3e5f5; color:#6a1b9a;'>";
    echo "<strong>Throwable Capturado:</strong> " . htmlspecialchars($t->getMessage()) . "<br>";
    echo "Tipo de Throwable: " . htmlspecialchars(get_class($t)) . "<br>";
    echo "</p>";
}
echo "<hr/>";


// ========= Re-lanzar excepciones (Rethrowing) =========
echo "<h2>5. Re-lanzar Excepciones</h2>";
echo "<p>A veces, un bloque <code>catch</code> puede manejar parcialmente una excepción (ej. registrarla) y luego re-lanzarla (o lanzar una nueva excepción) para que sea manejada por un bloque <code>catch</code> de nivel superior o por el manejador de excepciones global.</p>";
echo "<div class='code-example'>
try {<br>
&nbsp;&nbsp;&nbsp;&nbsp;try {<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;dividir(10, 0);<br>
&nbsp;&nbsp;&nbsp;&nbsp;} catch (InvalidArgumentException \$iae) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;echo \"Error interno (división): \" . \$iae->getMessage() . \" - Registrando y re-lanzando...<br>\";<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;throw new Exception(\"Ocurrió un problema de cálculo.\", 0, \$iae); // Re-lanzar o lanzar nueva, opcionalmente con la anterior<br>
&nbsp;&nbsp;&nbsp;&nbsp;}<br>
} catch (Exception \$e) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;echo \"Excepción de nivel superior capturada: \" . \$e->getMessage();<br>
&nbsp;&nbsp;&nbsp;&nbsp;if (\$e->getPrevious()) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;echo \"<br>Excepción original: \" . \$e->getPrevious()->getMessage();<br>
&nbsp;&nbsp;&nbsp;&nbsp;}<br>
}
</div>";

try {
    try {
        echo "<p>Intentando dividir 5 / 0 (para re-lanzar):</p>";
        dividir(5, 0);
    } catch (InvalidArgumentException $iaeInner) {
        echo "<p class='message error' style='background-color:#fff9c4; border-color:#ffecb3; color:#f57f17;'>";
        echo "<strong>Error Interno Capturado (InvalidArgumentException):</strong> " . htmlspecialchars($iaeInner->getMessage()) . "<br>";
        echo "Registrando este error (simulado)...<br>";
        // Re-lanzar como una excepción más genérica o una personalizada,
        // y pasar la excepción original como el tercer parámetro ($previous) de Exception.
        throw new MiExcepcionPersonalizada("Problema de cálculo detectado en la operación interna.", 1001, $iaeInner);
        echo "Este mensaje no se mostrará porque se re-lanzó la excepción.";
        echo "</p>";
    }
} catch (MiExcepcionPersonalizada $mepOuter) {
    echo "<p class='message error'>";
    echo "<strong>Excepción de Nivel Superior Capturada (MiExcepcionPersonalizada):</strong> " . htmlspecialchars($mepOuter->obtenerMensajeAmigable()) . "<br>";
    echo "Código: " . htmlspecialchars($mepOuter->getCode()) . "<br>";
    // Acceder a la excepción previa si existe
    $previousException = $mepOuter->getPrevious();
    if ($previousException) {
        echo "<strong>Causa Original (Excepción Previa):</strong> " . htmlspecialchars($previousException->getMessage()) . " (Tipo: " . htmlspecialchars(get_class($previousException)) . ")";
    }
    echo "</p>";
}


echo "<p>El manejo de excepciones es fundamental para escribir código PHP robusto y mantenible, permitiendo gestionar errores de forma elegante y controlada.</p>";

echo "<hr>";
echo "<p><a href='index.php'>Volver al índice de Manejo de Errores</a> (si existe)</p>";
echo "<p><a href='set_error_handler.php'>Ir a set_error_handler</a> | <a href='set_exception_handler.php'>Ir a set_exception_handler</a></p>";

echo "</div></body></html>";
?>
