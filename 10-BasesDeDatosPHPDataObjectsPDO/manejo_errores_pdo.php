<?php
// TEMA: BASES DE DATOS CON PHP (PDO) - Manejo de Errores

require_once 'config_db.php'; // Contiene $dsn_mysql, DB_USER, DB_PASS, $opciones_pdo

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>Manejo de Errores con PDO</title>
    <style>
        body { font-family: sans-serif; padding: 20px; line-height: 1.6; }
        .container { max-width: 800px; margin: auto; background: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        h1, h2 { color: #333; }
        pre { background-color: #eee; padding: 10px; border: 1px solid #ccc; overflow-x:auto; white-space: pre-wrap; word-wrap: break-word;}
        .message { padding: 10px; margin-bottom:15px; border-radius:4px; }
        .message.error { background-color: #f8d7da; border-color: #f5c6cb; color: #721c24; }
        .message.success { background-color: #d4edda; border-color: #c3e6cb; color: #155724; }
        .message.info { background-color: #e7f3fe; border-left: 6px solid #2196F3;}
        .code-example { background-color: #272822; color: #f8f8f2; padding: 15px; border-radius: 4px; margin-bottom: 10px; font-family: monospace;}
    </style>
</head>
<body>
<div class='container'>";

echo "<h1>Manejo de Errores con PDO</h1>";

// PDO puede operar en tres modos de manejo de errores, configurables con PDO::ATTR_ERRMODE:
// 1. PDO::ERRMODE_SILENT (por defecto): Solo establece códigos de error. Hay que comprobarlos manualmente.
// 2. PDO::ERRMODE_WARNING: Emite un E_WARNING.
// 3. PDO::ERRMODE_EXCEPTION: Lanza una PDOException. (RECOMENDADO)

// En config_db.php, ya hemos configurado:
// $opciones_pdo = [
//     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//     ...
// ];
// Por lo tanto, los ejemplos aquí asumirán que se lanzarán excepciones.

echo "<p class='message info'>En este ejemplo, PDO está configurado para <code>PDO::ERRMODE_EXCEPTION</code>. Esto significa que los errores de base de datos lanzarán una <code>PDOException</code> que puede ser capturada con bloques <code>try...catch</code>.</p>";


// --- EJEMPLO 1: Error en la Conexión (ya cubierto en conexion_pdo.php, pero lo repasamos) ---
echo "<h2>1. Error Durante la Conexión</h2>";
echo "<p>Si los datos de conexión (DSN, usuario, contraseña) son incorrectos, <code>new PDO(...)</code> lanzará una PDOException.</p>";
echo "<div class='code-example'>
try {<br>
&nbsp;&nbsp;&nbsp;&nbsp;\$pdo = new PDO(\$dsn_incorrecto, DB_USER, DB_PASS, \$opciones_pdo);<br>
} catch (PDOException \$e) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;echo \"Error de conexión: \" . \$e->getMessage();<br>
&nbsp;&nbsp;&nbsp;&nbsp;// En producción: loggear \$e y mostrar mensaje genérico.<br>
}
</div>";
// No ejecutamos esto aquí para no romper el script, pero es la idea.


// --- EJEMPLO 2: Error en una Consulta (ej. tabla o columna inexistente) ---
echo "<h2>2. Error en la Ejecución de una Consulta</h2>";
$pdo = null;
try {
    $pdo = new PDO($dsn_mysql, DB_USER, DB_PASS, $opciones_pdo);
    echo "<p class='message success'>Conexión exitosa para probar errores de consulta.</p>";

    // --- A. Consulta con error de sintaxis o tabla/columna inexistente ---
    echo "<h3>2.1. Intentando consultar una tabla inexistente:</h3>";
    $sqlTablaInexistente = "SELECT * FROM tabla_que_no_existe_seguro";

    try {
        $stmt = $pdo->query($sqlTablaInexistente);
        // Si llegamos aquí, algo raro pasó, porque debería haber lanzado excepción.
        echo "<p class='message error'>La consulta a tabla_que_no_existe_seguro NO lanzó excepción (inesperado).</p>";
        if ($stmt) $stmt->fetchAll(); // Intentar usar el statement
    } catch (PDOException $e) {
        echo "<p class='message error'><strong>Error capturado (Tabla Inexistente):</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<pre>Detalles de la excepción:\n";
        echo "Archivo: " . htmlspecialchars($e->getFile()) . "\n";
        echo "Línea: " . htmlspecialchars($e->getLine()) . "\n";
        echo "Código de error SQLSTATE: " . htmlspecialchars($e->getCode()) . "\n"; // SQLSTATE error code
        // $e->errorInfo es un array con [SQLSTATE, driver-specific error code, driver-specific error message]
        // A menudo $e->getCode() es el SQLSTATE y $e->getMessage() contiene el mensaje del driver.
        if (is_array($e->errorInfo) && count($e->errorInfo) >= 3) {
            echo "Código de error del driver: " . htmlspecialchars($e->errorInfo[1]) . "\n";
            echo "Mensaje del driver: " . htmlspecialchars($e->errorInfo[2]) . "\n";
        }
        echo "Trace: \n" . htmlspecialchars($e->getTraceAsString()) . "\n";
        echo "</pre>";
    }
    echo "<hr/>";

    // --- B. Error en una sentencia preparada (ej. número incorrecto de parámetros) ---
    echo "<h3>2.2. Error en una sentencia preparada (parámetros incorrectos):</h3>";
    $sqlPreparadaError = "SELECT * FROM productos WHERE precio < :precio_max AND stock > :stock_min";
    try {
        $stmtPrep = $pdo->prepare($sqlPreparadaError);
        // Olvidamos pasar :stock_min o pasamos un array con claves incorrectas
        $stmtPrep->execute([':precio_max' => 100]); // Falta :stock_min, o ['precio_max' => 100]

        echo "<p class='message error'>La sentencia preparada con parámetros incorrectos NO lanzó excepción (inesperado).</p>";
        $stmtPrep->fetchAll();
    } catch (PDOException $e) {
        echo "<p class='message error'><strong>Error capturado (Sentencia Preparada):</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<pre>SQLSTATE: " . htmlspecialchars($e->getCode()) . (is_array($e->errorInfo) && isset($e->errorInfo[1]) ? " | Driver Code: " . $e->errorInfo[1] : "") . "</pre>";
    }
    echo "<hr/>";

    // --- C. Error por violación de restricción (ej. UNIQUE constraint) ---
    echo "<h3>2.3. Error por violación de restricción (UNIQUE):</h3>";
    // Asumimos que la tabla 'categorias' tiene un UNIQUE constraint en la columna 'nombre'.
    // Primero, insertamos una categoría.
    $nombreCategoriaUnica = "Electrónica Única Test " . time(); // Nombre único para la primera inserción
    try {
        $pdo->exec("INSERT INTO categorias (nombre, descripcion) VALUES (".$pdo->quote($nombreCategoriaUnica).", 'Descripción de prueba')");
        echo "<p class='message success'>Categoría '{$nombreCategoriaUnica}' insertada para prueba.</p>";

        // Ahora intentamos insertarla de nuevo, lo que debería causar un error UNIQUE
        $pdo->exec("INSERT INTO categorias (nombre, descripcion) VALUES (".$pdo->quote($nombreCategoriaUnica).", 'Intento duplicado')");

        echo "<p class='message error'>La inserción duplicada NO lanzó excepción (inesperado, ¿no hay constraint UNIQUE en 'categorias.nombre'?).</p>";

    } catch (PDOException $e) {
        echo "<p class='message error'><strong>Error capturado (Violación de UNIQUE):</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<pre>SQLSTATE: " . htmlspecialchars($e->getCode()) . "</pre>";
        // SQLSTATE 23000 generalmente indica una violación de integridad (como UNIQUE, FOREIGN KEY, NOT NULL).
        if ($e->getCode() == '23000') { // Código SQLSTATE para Integrity constraint violation
            echo "<p class='message info'>Este error (SQLSTATE 23000) es común cuando se intenta insertar un valor duplicado en una columna con restricción UNIQUE.</p>";
        }
        // Limpiar la categoría de prueba para futuras ejecuciones
        $pdo->exec("DELETE FROM categorias WHERE nombre = ".$pdo->quote($nombreCategoriaUnica));
    }


} catch (PDOException $e) {
    // Este catch es para la conexión principal.
    echo "<p class='message error'>Error de conexión general a la BD: " . htmlspecialchars($e->getMessage()) . "</p>";
} finally {
    $pdo = null; // Cerrar la conexión
}


echo "<hr><h2>Buenas Prácticas para el Manejo de Errores PDO:</h2>";
echo "<ul>
        <li><strong>Siempre usa <code>PDO::ERRMODE_EXCEPTION</code>.</strong> Es la forma más limpia y robusta de manejar errores.</li>
        <li><strong>Usa bloques <code>try...catch (PDOException \$e)</code></strong> alrededor de tu código de base de datos.</li>
        <li><strong>En producción:</strong>
            <ul>
                <li><strong>No muestres detalles sensibles del error</strong> (como mensajes de <code>\$e->getMessage()</code> o trazas) al usuario final. Podrían revelar información sobre tu sistema o base de datos.</li>
                <li><strong>Registra los errores detallados</strong> en un archivo de log en el servidor para que los desarrolladores puedan analizarlos (ej. usando <code>error_log()</code> o una librería de logging como Monolog).</li>
                <li>Muestra un <strong>mensaje de error genérico</strong> y amigable al usuario (ej. \"Ocurrió un error al procesar tu solicitud. Por favor, inténtalo más tarde.\").</li>
            </ul>
        </li>
        <li><strong>Comprende los códigos de error SQLSTATE:</strong> Te dan una idea estandarizada del tipo de error, independientemente del SGBD.</li>
        <li><strong>Manejo de Transacciones:</strong> Si una excepción ocurre dentro de una transacción, asegúrate de hacer <code>rollBack()</code> en el bloque <code>catch</code>.</li>
      </ul>";

echo "<p>El manejo adecuado de errores es crucial para la seguridad y la estabilidad de cualquier aplicación que interactúe con bases de datos.</p>";

echo "<hr>";
echo "<p><a href='index.php'>Volver al índice de PDO</a> (si existe)</p>";
echo "<p><a href='transacciones_pdo.php'>Ir a Transacciones PDO</a></p>";

echo "</div></body></html>";
?>
