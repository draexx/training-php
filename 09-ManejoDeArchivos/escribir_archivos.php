<?php
// TEMA: MANEJO DE ARCHIVOS - ESCRITURA DE ARCHIVOS

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>Escritura de Archivos en PHP</title>
    <style>
        body { font-family: sans-serif; padding: 20px; line-height: 1.6; }
        .container { max-width: 800px; margin: auto; background: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        h1, h2 { color: #333; }
        pre { background-color: #eee; padding: 10px; border: 1px solid #ccc; overflow-x: auto; white-space: pre-wrap; word-wrap: break-word; }
        .error { color: red; font-weight: bold; padding:10px; border: 1px solid red; background: #ffebeb; }
        .success { color: green; font-weight: bold; padding:10px; border: 1px solid green; background: #e6ffed; }
        .info { padding: 10px; background-color: #e7f3fe; border-left: 6px solid #2196F3; margin-bottom:15px;}
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group textarea { width: calc(100% - 22px); padding: 8px; border: 1px solid #ccc; border-radius: 4px; min-height: 80px; }
        .form-group button { padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .form-group button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
<div class='container'>";

echo "<h1>Escritura de Archivos en PHP</h1>";

$nombreArchivoEscritura = "salida.txt";
$nombreArchivoCSVParaEscribir = "datos_salida.csv";
$mensajeResultado = "";

// Procesar formulario para escribir contenido
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['contenido_archivo'])) {
    $contenidoParaEscribir = $_POST['contenido_archivo'];
    $modoEscritura = isset($_POST['modo_escritura']) ? $_POST['modo_escritura'] : 'w'; // 'w' por defecto

    // ========= 1. file_put_contents() - Escribir un string en un archivo =========
    // La forma más sencilla de escribir datos en un archivo.
    // Si el archivo no existe, lo crea. Si existe, por defecto lo sobrescribe.
    // Se puede usar el flag FILE_APPEND para añadir al final en lugar de sobrescribir.

    if ($modoEscritura == 'a') { // Añadir (append)
        if (file_put_contents($nombreArchivoEscritura, $contenidoParaEscribir . PHP_EOL, FILE_APPEND | LOCK_EX) !== false) {
            // PHP_EOL es una constante para el carácter de nueva línea correcto según el SO.
            // LOCK_EX previene que otros escriban en el archivo al mismo tiempo (bloqueo exclusivo).
            $mensajeResultado = "<p class='success'>Contenido añadido a '{$nombreArchivoEscritura}' usando file_put_contents() con FILE_APPEND.</p>";
        } else {
            $mensajeResultado = "<p class='error'>Error al añadir contenido a '{$nombreArchivoEscritura}'. Verifica los permisos.</p>";
        }
    } else { // Sobrescribir (write)
        if (file_put_contents($nombreArchivoEscritura, $contenidoParaEscribir . PHP_EOL, LOCK_EX) !== false) {
            $mensajeResultado = "<p class='success'>Contenido escrito en '{$nombreArchivoEscritura}' usando file_put_contents() (sobrescribiendo).</p>";
        } else {
            $mensajeResultado = "<p class='error'>Error al escribir en '{$nombreArchivoEscritura}'. Verifica los permisos.</p>";
        }
    }
}

// Mostrar formulario
echo "<div class='form-group'>";
echo "<h2>1. Escribir/Añadir con <code>file_put_contents()</code></h2>";
echo $mensajeResultado; // Mostrar resultado de la operación anterior
echo "<form method='POST' action='escribir_archivos.php'>";
echo "<label for='contenido_archivo'>Contenido para '{$nombreArchivoEscritura}':</label>";
echo "<textarea id='contenido_archivo' name='contenido_archivo' required></textarea><br>";
echo "<input type='radio' name='modo_escritura' value='w' checked> Sobrescribir ";
echo "<input type='radio' name='modo_escritura' value='a'> Añadir al final<br><br>";
echo "<button type='submit'>Escribir en Archivo</button>";
echo "</form>";
echo "</div>";

// Mostrar contenido actual del archivo de salida
if (file_exists($nombreArchivoEscritura)) {
    echo "<p><strong>Contenido actual de '{$nombreArchivoEscritura}':</strong></p>";
    echo "<pre>" . htmlspecialchars(file_get_contents($nombreArchivoEscritura), ENT_QUOTES, 'UTF-8') . "</pre>";
} else {
    echo "<p class='info'>El archivo '{$nombreArchivoEscritura}' aún no ha sido creado.</p>";
}
echo "<hr/>";


// ========= 2. Usando fopen(), fwrite()/fputs() y fclose() - Escritura controlada =========
// Ofrece más control, especialmente útil para escribir gradualmente o en archivos grandes.
// Modos de apertura comunes para escritura:
// "w" : Escritura. Crea el archivo si no existe. Sobrescribe el contenido si existe. Puntero al inicio.
// "a" : Añadir (append). Crea el archivo si no existe. Puntero al final del archivo.
// "x" : Creación exclusiva. Crea el archivo solo si NO existe. Devuelve FALSE y error si existe. Puntero al inicio.
// "w+": Lectura y escritura. Sobrescribe.
// "a+": Lectura y añadir. Puntero al final.
// "x+": Lectura y escritura (creación exclusiva).

echo "<h2>2. Escribir con <code>fopen()</code>, <code>fwrite()</code>, <code>fclose()</code></h2>";
$manejadorEscritura = fopen("salida_fwrite.txt", "w"); // Abrir en modo escritura (sobrescribe)

if ($manejadorEscritura) {
    $bytesEscritos = fwrite($manejadorEscritura, "Primera línea escrita con fwrite.\n");
    fwrite($manejadorEscritura, "Segunda línea también con fwrite.\n");
    // fputs() es un alias de fwrite()
    fputs($manejadorEscritura, "Tercera línea con fputs.\n");

    fclose($manejadorEscritura); // ¡Importante cerrar el archivo!

    echo "<p class='success'>Se escribieron datos en 'salida_fwrite.txt'. Total de bytes en la primera escritura: {$bytesEscritos}.</p>";
    echo "<p><strong>Contenido de 'salida_fwrite.txt':</strong></p>";
    echo "<pre>" . htmlspecialchars(file_get_contents("salida_fwrite.txt"), ENT_QUOTES, 'UTF-8') . "</pre>";
} else {
    echo "<p class='error'>No se pudo abrir 'salida_fwrite.txt' para escritura. Verifica los permisos.</p>";
}
echo "<hr/>";


// ========= 3. Escribir en archivos CSV con fputcsv() =========
// fputcsv() formatea un array como una línea CSV y la escribe en un puntero de archivo.
echo "<h2>3. Escribir en un archivo CSV con <code>fputcsv()</code></h2>";

$datosParaCSV = [
    ['ID', 'Producto', 'Precio', 'Stock'], // Encabezados
    [1, 'Laptop Pro', 1200.50, 15],
    [2, 'Mouse Gamer', 45.99, 120],
    [3, 'Teclado Mecánico', 89.75, 75],
    [4, 'Monitor 27"', 299.00, 30]
];

$manejadorCSVEscritura = fopen($nombreArchivoCSVParaEscribir, "w"); // Abrir en modo escritura (sobrescribe)

if ($manejadorCSVEscritura) {
    $lineasEscritasCSV = 0;
    foreach ($datosParaCSV as $fila) {
        if (fputcsv($manejadorCSVEscritura, $fila) !== false) {
            $lineasEscritasCSV++;
        }
    }
    fclose($manejadorCSVEscritura);
    echo "<p class='success'>Se escribieron {$lineasEscritasCSV} líneas en '{$nombreArchivoCSVParaEscribir}'.</p>";
    echo "<p><strong>Contenido de '{$nombreArchivoCSVParaEscribir}':</strong></p>";
    echo "<pre>" . htmlspecialchars(file_get_contents($nombreArchivoCSVParaEscribir), ENT_QUOTES, 'UTF-8') . "</pre>";

    // Para verificar, lo leemos y mostramos como tabla:
    echo "<h4>Verificación (leyendo el CSV generado):</h4>";
    $manejadorLecturaCSV = fopen($nombreArchivoCSVParaEscribir, "r");
    if($manejadorLecturaCSV){
        echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>";
        while (($data_row = fgetcsv($manejadorLecturaCSV)) !== FALSE) {
            echo "<tr>";
            foreach($data_row as $cell_data){
                echo "<td>" . htmlspecialchars($cell_data) . "</td>";
            }
            echo "</tr>";
        }
        echo "</table>";
        fclose($manejadorLecturaCSV);
    }

} else {
    echo "<p class='error'>No se pudo abrir '{$nombreArchivoCSVParaEscribir}' para escritura. Verifica los permisos.</p>";
}
echo "<hr/>";


echo "<h2>Consideraciones Importantes al Escribir Archivos:</h2>";
echo "<ul>";
echo "<li><strong>Permisos de Archivo/Directorio:</strong> Asegúrate de que el script PHP tenga permisos de escritura en el directorio donde intentas crear o modificar archivos.</li>";
echo "<li><strong>Bloqueo de Archivos (File Locking):</strong> En aplicaciones concurrentes, usa <code>flock()</code> o <code>LOCK_EX</code> con <code>file_put_contents()</code> para prevenir condiciones de carrera donde múltiples procesos intentan escribir en el mismo archivo simultáneamente.</li>";
echo "<li><strong>Manejo de Errores:</strong> Siempre verifica el valor de retorno de las funciones de archivo (<code>fopen</code>, <code>fwrite</code>, <code>file_put_contents</code>, etc.) para manejar posibles errores.</li>";
echo "<li><strong>Cerrar Archivos:</strong> Siempre cierra los archivos abiertos con <code>fclose()</code> cuando termines de usarlos para liberar recursos y asegurar que todos los datos se escriban correctamente en el disco.</li>";
echo "<li><strong>Seguridad:</strong>
    <ul>
        <li>Evita escribir rutas de archivo o nombres de archivo directamente basados en la entrada del usuario sin una validación y sanitización exhaustiva para prevenir ataques de Path Traversal.</li>
        <li>Si escribes contenido generado por el usuario en archivos que podrían ser accedidos vía web (ej. .html, .php), sanitiza extremadamente bien para prevenir XSS o inyección de código. Es mejor no permitir esto o guardar en formatos no ejecutables.</li>
    </ul>
</li>";
echo "</ul>";

echo "<p><a href='index.php'>Volver al índice de manejo de archivos</a> (si existe)</p>";
echo "<p><a href='leer_archivos.php'>Ir a Leer Archivos</a></p>";

echo "</div></body></html>";

?>
