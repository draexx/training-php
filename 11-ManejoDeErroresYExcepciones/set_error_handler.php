<?php
// TEMA: MANEJO DE ERRORES Y EXCEPCIONES - Manejador de Errores Personalizado (set_error_handler)

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>Manejador de Errores Personalizado en PHP</title>
    <style>
        body { font-family: sans-serif; padding: 20px; line-height: 1.6; }
        .container { max-width: 800px; margin: auto; background: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        h1, h2 { color: #333; }
        pre { background-color: #eee; padding: 10px; border: 1px solid #ccc; overflow-x:auto; white-space: pre-wrap; word-wrap: break-word;}
        .custom-error-handler-output {
            border: 2px dashed red;
            padding: 15px;
            margin: 15px 0;
            background-color: #ffebeb;
            color: #721c24;
        }
        .custom-error-handler-output strong { color: #c00; }
        .message.info { padding: 10px; background-color: #e7f3fe; border-left: 6px solid #2196F3; margin-bottom:15px;}
        .code-example { background-color: #272822; color: #f8f8f2; padding: 15px; border-radius: 4px; margin-bottom: 10px; font-family: monospace;}
    </style>
</head>
<body>
<div class='container'>";

echo "<h1>Manejador de Errores Personalizado con <code>set_error_handler()</code></h1>";

echo "<p class='message info'><code>set_error_handler()</code> permite definir una función callback que será llamada cada vez que ocurra un error reportable en el script (excepto algunos errores fatales como E_ERROR, E_PARSE, E_CORE_ERROR, etc., aunque puede convertir warnings/notices en excepciones).</p>";

// --- Definición de la función manejadora de errores personalizada ---
// Esta función debe aceptar al menos dos parámetros: el nivel del error y el mensaje del error.
// Opcionalmente, puede aceptar el archivo, la línea y el contexto del error.
function miManejadorDeErrores($nivelError, $mensajeError, $archivoError, $lineaError, $contextoError = null) {
    // $nivelError: El nivel del error (ej. E_WARNING, E_NOTICE).
    // $mensajeError: El mensaje del error.
    // $archivoError: El nombre del archivo donde ocurrió el error.
    // $lineaError: El número de línea donde ocurrió el error.
    // $contextoError: Un array que apunta a la tabla de símbolos activa en el punto donde ocurrió el error (opcional, puede consumir mucha memoria).

    // No queremos que el manejador de errores de PHP normal se ejecute después del nuestro
    // si el error está cubierto por error_reporting().
    // Si esta función devuelve FALSE, el manejador de errores normal de PHP continuará.
    // Si devuelve TRUE (o no devuelve nada, que es NULL y se evalúa como FALSE), el manejador normal NO se ejecutará.

    // Solo manejaremos errores que estén actualmente siendo reportados por error_reporting()
    if (!(error_reporting() & $nivelError)) {
        // Este error no está incluido en error_reporting, así que dejamos que PHP lo maneje como siempre.
        return false;
    }

    echo "<div class='custom-error-handler-output'>";
    echo "<strong>¡ERROR CAPTURADO POR MANEJADOR PERSONALIZADO!</strong><br>";

    $tipoErrorStr = "Desconocido";
    switch ($nivelError) {
        case E_USER_ERROR:
        case E_ERROR: // Aunque set_error_handler no puede manejar E_ERROR directamente, es bueno tenerlo.
            $tipoErrorStr = "Error Fatal (E_ERROR / E_USER_ERROR)";
            break;
        case E_USER_WARNING:
        case E_WARNING:
            $tipoErrorStr = "Advertencia (E_WARNING / E_USER_WARNING)";
            break;
        case E_USER_NOTICE:
        case E_NOTICE:
            $tipoErrorStr = "Notificación (E_NOTICE / E_USER_NOTICE)";
            break;
        case E_STRICT:
            $tipoErrorStr = "Strict Standards (E_STRICT)";
            break;
        case E_RECOVERABLE_ERROR:
            $tipoErrorStr = "Error Recuperable (E_RECOVERABLE_ERROR)";
            break;
        case E_DEPRECATED:
        case E_USER_DEPRECATED:
            $tipoErrorStr = "Obsoleto (E_DEPRECATED / E_USER_DEPRECATED)";
            break;
        default:
            $tipoErrorStr = "Error de tipo desconocido ({$nivelError})";
            break;
    }

    echo "<strong>Tipo:</strong> " . htmlspecialchars($tipoErrorStr, ENT_QUOTES, 'UTF-8') . "<br>";
    echo "<strong>Mensaje:</strong> " . htmlspecialchars($mensajeError, ENT_QUOTES, 'UTF-8') . "<br>";
    echo "<strong>Archivo:</strong> " . htmlspecialchars($archivoError, ENT_QUOTES, 'UTF-8') . "<br>";
    echo "<strong>Línea:</strong> " . htmlspecialchars($lineaError, ENT_QUOTES, 'UTF-8') . "<br>";

    // Opcional: Mostrar contexto (puede ser muy verboso)
    /*
    if ($contextoError !== null && is_array($contextoError)) {
        echo "<strong>Contexto:</strong><pre>";
        print_r($contextoError); // Cuidado: puede exponer información sensible.
        echo "</pre>";
    }
    */

    // En un entorno de producción, aquí podrías:
    // 1. Registrar el error en un archivo de log o base de datos.
    //    error_log("Error PHP: [$tipoErrorStr] $mensajeError en $archivoError:$lineaError");
    // 2. Enviar una notificación a los administradores.
    // 3. Si es un error crítico, mostrar una página de error amigable y detener el script.
    //    if ($nivelError == E_USER_ERROR || $nivelError == E_ERROR) {
    //        include 'pagina_error_amigable.html';
    //        exit;
    //    }

    // Convertir ciertos errores (como Warnings o Notices) en excepciones
    // Esto permite un manejo de errores más estructurado usando try-catch.
    if ($nivelError == E_WARNING || $nivelError == E_NOTICE || $nivelError == E_USER_WARNING || $nivelError == E_USER_NOTICE || $nivelError == E_RECOVERABLE_ERROR) {
        echo "<p><em>Este manejador podría convertir este error en una ErrorException.</em></p>";
        // throw new ErrorException($mensajeError, 0, $nivelError, $archivoError, $lineaError);
        // Si se lanza una excepción aquí, la ejecución del script normal se detiene y busca un bloque catch.
    }

    echo "</div>";

    // Devuelve true para indicar que hemos manejado el error y PHP no necesita procesarlo más.
    // Si devolvemos false, el manejador de errores interno de PHP también se ejecutará.
    return true;
}

// --- Establecer el manejador de errores personalizado ---
// set_error_handler() devuelve el manejador anterior, o null si no había.
$manejadorAnterior = set_error_handler("miManejadorDeErrores");

echo "<p class='message info'>Manejador de errores personalizado 'miManejadorDeErrores' ha sido establecido.</p>";
// Si quisiéramos restaurar el manejador anterior en algún punto:
// restore_error_handler();


// --- Generar algunos errores para probar el manejador ---
echo "<h2>Pruebas del Manejador de Errores:</h2>";

// 1. Generar un E_NOTICE (variable no definida)
echo "<h3>Prueba 1: Generando un E_NOTICE</h3>";
echo "<div class='code-example'>\$testNotice = \$variableQueNoExiste;</div>";
// Este error será capturado por miManejadorDeErrores si error_reporting() incluye E_NOTICE.
// Como el manejador devuelve true, el error no se mostrará de la forma habitual de PHP (si display_errors está on).
$testNotice = @$variableQueNoExiste; // Usamos @ aquí para que el error NO se muestre por el manejador interno de PHP
                                    // si nuestro manejador por alguna razón no lo captura o devuelve false.
                                    // Sin el @, si el manejador devuelve false, PHP aún mostraría el notice.
                                    // Con el manejador devolviendo true, el @ no es estrictamente necesario para suprimirlo,
                                    // pero es una doble seguridad.

// 2. Generar un E_WARNING (división por cero en PHP < 8, o incluir archivo inexistente)
echo "<h3>Prueba 2: Generando un E_WARNING</h3>";
echo "<div class='code-example'>@include 'archivo_ficticio_para_warning.php';</div>";
@include 'archivo_ficticio_para_warning.php'; // Genera E_WARNING, capturado por nuestro manejador.


// 3. Generar un error de usuario con trigger_error()
// trigger_error() permite generar errores de nivel E_USER_NOTICE, E_USER_WARNING, o E_USER_ERROR.
echo "<h3>Prueba 3: Generando un E_USER_WARNING con <code>trigger_error()</code></h3>";
echo "<div class='code-example'>trigger_error(\"Este es un mensaje de advertencia personalizado.\", E_USER_WARNING);</div>";
trigger_error("Este es un mensaje de advertencia personalizado.", E_USER_WARNING);


// --- Convertir errores en excepciones ---
echo "<h2>Convertir Errores en Excepciones dentro del Manejador</h2>";
echo "<p>Una práctica común es que el manejador de errores personalizado lance una <code>ErrorException</code>. Esto permite capturar errores tradicionales de PHP (notices, warnings) usando bloques <code>try...catch</code>.</p>";
echo "<p>Para demostrar esto, modificaremos temporalmente el manejador para que lance excepciones y luego lo restauraremos.</p>";

function manejadorQueLanzaExcepciones($nivel, $mensaje, $archivo, $linea) {
    if (!(error_reporting() & $nivel)) {
        return false;
    }
    // Lanzar una ErrorException. El constructor de ErrorException es:
    // ErrorException($message, $code, $severity, $filename, $lineno, $previous)
    echo "<div class='custom-error-handler-output'>";
    echo "<strong>¡ERROR CAPTURADO POR MANEJADOR (QUE LANZARÁ EXCEPCIÓN)!</strong><br>";
    echo "<strong>Mensaje:</strong> " . htmlspecialchars($mensaje) . "<br>";
    echo "<em>Lanzando ErrorException...</em>";
    echo "</div>";
    throw new ErrorException($mensaje, 0, $nivel, $archivo, $linea);
}

// Establecer el nuevo manejador temporalmente
restore_error_handler(); // Restaurar al manejador original de PHP (o el que estaba antes de 'miManejadorDeErrores')
set_error_handler("manejadorQueLanzaExcepciones");
echo "<p class='message info'>Manejador 'manejadorQueLanzaExcepciones' establecido temporalmente.</p>";

echo "<h3>Prueba de conversión de Warning a Excepción:</h3>";
echo "<div class='code-example'>
try {<br>
&nbsp;&nbsp;&nbsp;&nbsp;\$resultado = 10 / 0; // Generará un Warning (PHP &lt; 8) o DivisionByZeroError (PHP 8+)<br>
&nbsp;&nbsp;&nbsp;&nbsp;echo \"Resultado: \$resultado\";<br>
} catch (ErrorException \$e) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;echo \"ErrorException capturada: \" . \$e->getMessage();<br>
} catch (DivisionByZeroError \$e) { // Específico para PHP 8+<br>
&nbsp;&nbsp;&nbsp;&nbsp;echo \"DivisionByZeroError capturada: \" . \$e->getMessage();<br>
}
</div>";

try {
    // En PHP < 8, esto genera un E_WARNING, que nuestro manejador convertirá en ErrorException.
    // En PHP >= 8, esto genera una excepción DivisionByZeroError directamente.
    if (PHP_VERSION_ID < 80000) {
        $resultado = 10 / 0; // Esto activará 'manejadorQueLanzaExcepciones'
        echo "Resultado de 10/0: " . $resultado . "<br>"; // No se alcanzará si se lanza excepción
    } else {
        echo "<p><em>En PHP 8+, la división por cero lanza una <code>DivisionByZeroError</code> directamente, que no pasa por <code>set_error_handler</code> para warnings. Probemos con un E_NOTICE.</em></p>";
        echo $variableInexistenteParaExcepcion; // Esto generará un E_NOTICE
    }
} catch (ErrorException $e) {
    // Esta se capturará si 'manejadorQueLanzaExcepciones' convirtió un error (como E_NOTICE) en ErrorException.
    echo "<div class='message success' style='border-color:purple; background-color:#f3e5f5; color:#6a1b9a;'>";
    echo "<strong>ErrorException Capturada:</strong><br>";
    echo "Mensaje: " . htmlspecialchars($e->getMessage()) . "<br>";
    echo "Severidad: " . htmlspecialchars($e->getSeverity()) . " (Nivel de error original)<br>";
    echo "Archivo: " . htmlspecialchars($e->getFile()) . "<br>";
    echo "Línea: " . htmlspecialchars($e->getLine()) . "<br>";
    echo "</div>";
} catch (DivisionByZeroError $e) { // Específico para PHP 8+ para el caso de 10/0
     echo "<div class='message success' style='border-color:orange; background-color:#fff8e1; color:#e65100;'>";
    echo "<strong>DivisionByZeroError Capturada (PHP 8+):</strong><br>";
    echo "Mensaje: " . htmlspecialchars($e->getMessage()) . "<br>";
    echo "Archivo: " . htmlspecialchars($e->getFile()) . "<br>";
    echo "Línea: " . htmlspecialchars($e->getLine()) . "<br>";
    echo "</div>";
}


// --- Restaurar el manejador de errores original de PHP ---
// Es buena práctica restaurarlo si ya no necesitas el personalizado,
// especialmente en partes de una aplicación grande o bibliotecas.
restore_error_handler(); // Restaura el manejador que estaba antes de 'manejadorQueLanzaExcepciones'
restore_error_handler(); // Restaura el manejador que estaba antes de 'miManejadorDeErrores' (vuelve al de PHP por defecto)
echo "<p class='message info'>Manejador de errores original de PHP restaurado.</p>";

// Prueba final para ver si el manejador por defecto de PHP está activo
// echo $variableFinalInexistente; // Si display_errors está on, esto mostraría un Notice de PHP estándar.


echo "<hr>";
echo "<p><code>set_error_handler</code> es una herramienta poderosa para tomar control sobre cómo se manejan los errores no fatales en PHP, permitiendo logging personalizado, conversión a excepciones, o mostrar páginas de error amigables.</p>";

echo "<p><a href='index.php'>Volver al índice de Manejo de Errores</a> (si existe)</p>";
echo "<p><a href='errores_basicos.php'>Ir a Errores Básicos</a> | <a href='excepciones_try_catch.php'>Ir a Excepciones (try-catch)</a></p>";

echo "</div></body></html>";
?>
