<?php
// TEMA: MANEJO DE ERRORES Y EXCEPCIONES - Manejador de Excepciones Global (set_exception_handler)

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>Manejador de Excepciones Global en PHP</title>
    <style>
        body { font-family: sans-serif; padding: 20px; line-height: 1.6; }
        .container { max-width: 800px; margin: auto; background: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        h1, h2 { color: #333; }
        pre { background-color: #eee; padding: 10px; border: 1px solid #ccc; overflow-x:auto; white-space: pre-wrap; word-wrap: break-word;}
        .custom-exception-handler-output {
            border: 2px solid #6a1b9a;
            padding: 15px;
            margin: 15px 0;
            background-color: #f3e5f5;
            color: #4a148c;
        }
        .custom-exception-handler-output strong { color: #6a1b9a; }
        .message.info { padding: 10px; background-color: #e7f3fe; border-left: 6px solid #2196F3; margin-bottom:15px;}
        .code-example { background-color: #272822; color: #f8f8f2; padding: 15px; border-radius: 4px; margin-bottom: 10px; font-family: monospace;}
    </style>
</head>
<body>
<div class='container'>";

echo "<h1>Manejador de Excepciones Global con <code>set_exception_handler()</code></h1>";

echo "<p class='message info'><code>set_exception_handler()</code> permite registrar una función que será llamada si ocurre una excepción que no es capturada por ningún bloque <code>catch</code>. Es un 'último recurso' para manejar excepciones no controladas.</p>";
echo "<p class='message info'>Esto es útil para asegurar que tu aplicación siempre termine de forma controlada, mostrando un error amigable al usuario y/o registrando el error detallado, en lugar de mostrar un error fatal de PHP.</p>";

// --- Definición de la función manejadora de excepciones global ---
// Esta función debe aceptar un parámetro: el objeto Throwable (Exception o Error) que no fue capturado.
function miManejadorDeExcepcionesGlobal(Throwable $excepcion) {
    // $excepcion: El objeto de la excepción no capturada.

    echo "<div class='custom-exception-handler-output'>";
    echo "<strong>¡EXCEPCIÓN GLOBAL NO CAPTURADA!</strong><br>";
    echo "<strong>Tipo de Excepción:</strong> " . htmlspecialchars(get_class($excepcion), ENT_QUOTES, 'UTF-8') . "<br>";
    echo "<strong>Mensaje:</strong> " . htmlspecialchars($excepcion->getMessage(), ENT_QUOTES, 'UTF-8') . "<br>";
    echo "<strong>Archivo:</strong> " . htmlspecialchars($excepcion->getFile(), ENT_QUOTES, 'UTF-8') . "<br>";
    echo "<strong>Línea:</strong> " . htmlspecialchars($excepcion->getLine(), ENT_QUOTES, 'UTF-8') . "<br>";
    echo "<strong>Código:</strong> " . htmlspecialchars($excepcion->getCode(), ENT_QUOTES, 'UTF-8') . "<br>";

    // En un entorno de producción:
    // 1. Registrar el error detallado:
    $errorLogMsg = sprintf(
        "Excepción no capturada: [%s] %s en %s:%d (Código: %s)\nTraza:\n%s",
        get_class($excepcion),
        $excepcion->getMessage(),
        $excepcion->getFile(),
        $excepcion->getLine(),
        $excepcion->getCode(),
        $excepcion->getTraceAsString()
    );
    error_log($errorLogMsg); // Envía al log de errores de PHP o al especificado en php.ini

    // 2. Mostrar una página de error amigable al usuario:
    // include 'pagina_error_fatal_amigable.html';
    echo "<p><em>En un entorno de producción, se mostraría una página de error genérica y este detalle se registraría en un log.</em></p>";

    // 3. Opcionalmente, enviar una notificación a los administradores.

    // Es importante que el script termine después de esto, ya que la excepción no fue manejada
    // y el estado de la aplicación podría ser inconsistente.
    // No es necesario un `exit` explícito aquí, ya que PHP terminará el script después
    // de que el manejador de excepciones global se complete.
    echo "</div>";
}

// --- Establecer el manejador de excepciones global ---
// set_exception_handler() devuelve el manejador anterior, o null si no había.
$manejadorExcepcionAnterior = set_exception_handler("miManejadorDeExcepcionesGlobal");

echo "<p class='message info'>Manejador de excepciones global 'miManejadorDeExcepcionesGlobal' ha sido establecido.</p>";
// Para restaurar el manejador anterior (raramente necesario para el global, a menos que estés en una biblioteca):
// restore_exception_handler();


// --- Función que podría lanzar una excepción (reutilizada) ---
class MiExcepcionNoCapturada extends Exception {}

function operacionPeligrosa($valor) {
    if ($valor < 0) {
        throw new InvalidArgumentException("El valor no puede ser negativo.");
    }
    if ($valor == 13) {
        throw new MiExcepcionNoCapturada("¡El número 13 es de mala suerte en esta operación!");
    }
    if ($valor > 100) {
        // Simulando un error interno de PHP que podría no ser una Exception estándar
        // Esto normalmente sería un Error, no una Exception, pero para el ejemplo:
        throw new Error("Valor demasiado grande, simulando un Error interno.");
    }
    return "Operación exitosa con valor: " . htmlspecialchars($valor);
}

// --- Generar una excepción que NO será capturada por un try...catch ---
echo "<h2>Prueba del Manejador de Excepciones Global:</h2>";

$casoPrueba = isset($_GET['caso']) ? intval($_GET['caso']) : 0;

echo "<p>Selecciona un caso para probar:</p>
<ul>
    <li><a href='set_exception_handler.php?caso=1'>Caso 1: Lanzar InvalidArgumentException no capturada</a></li>
    <li><a href='set_exception_handler.php?caso=2'>Caso 2: Lanzar MiExcepcionNoCapturada no capturada</a></li>
    <li><a href='set_exception_handler.php?caso=3'>Caso 3: Lanzar Error no capturado</a></li>
    <li><a href='set_exception_handler.php?caso=0'>Caso 0: Operación exitosa (sin excepción)</a></li>
</ul>";


// El script terminará si se lanza una excepción y es manejada por el manejador global.
// Por lo tanto, el código después del switch podría no ejecutarse si una excepción es lanzada.
echo "<div class='code-example'>";
switch ($casoPrueba) {
    case 1:
        echo "Ejecutando operacionPeligrosa(-5)... (debería lanzar InvalidArgumentException)<br>";
        // Esta excepción será capturada por miManejadorDeExcepcionesGlobal
        // porque no hay un bloque try...catch alrededor de esta llamada específica.
        operacionPeligrosa(-5);
        echo "Este mensaje NO se mostrará si la excepción fue lanzada y manejada globalmente.";
        break;
    case 2:
        echo "Ejecutando operacionPeligrosa(13)... (debería lanzar MiExcepcionNoCapturada)<br>";
        operacionPeligrosa(13);
        echo "Este mensaje NO se mostrará.";
        break;
    case 3:
        echo "Ejecutando operacionPeligrosa(200)... (debería lanzar Error)<br>";
        // En PHP 7+, los Errores también son Throwable y pueden ser capturados por set_exception_handler.
        operacionPeligrosa(200);
        echo "Este mensaje NO se mostrará.";
        break;
    case 0:
    default:
        echo "Ejecutando operacionPeligrosa(10)... (operación exitosa)<br>";
        $resultado = operacionPeligrosa(10);
        echo "<p class='message success'>" . htmlspecialchars($resultado) . "</p>";
        echo "Como no hubo excepción no capturada, el manejador global no se activó.";
        break;
}
echo "</div>";


echo "<hr>";
echo "<p>Si ves un cuadro de color púrpura arriba con los detalles de una excepción, significa que el manejador global se activó porque una excepción fue lanzada y no había un bloque <code>try...catch</code> para manejarla en el flujo de ejecución principal.</p>";
echo "<p><code>set_exception_handler()</code> es una red de seguridad crucial para cualquier aplicación PHP robusta.</p>";

// Restaurar el manejador de excepciones por defecto de PHP (opcional, pero buena práctica si este script es parte de algo más grande)
// restore_exception_handler();
// echo "<p class='message info'>Manejador de excepciones global original de PHP restaurado (si es que había uno antes).</p>";


echo "<hr>";
echo "<p><a href='index.php'>Volver al índice de Manejo de Errores</a> (si existe)</p>";
echo "<p><a href='excepciones_try_catch.php'>Ir a Excepciones (try-catch)</a> | <a href='index.php'>Volver al Inicio de Errores/Excepciones</a></p>";

echo "</div></body></html>";

// Nota: Si una excepción es lanzada y manejada por el manejador global,
// el script normalmente termina su ejecución después de que el manejador se completa.
// Cualquier código HTML o PHP después del punto donde se lanzó la excepción (y no fue capturada localmente)
// no se ejecutará.
?>
