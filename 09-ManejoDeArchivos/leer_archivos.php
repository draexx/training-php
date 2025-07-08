<?php
// TEMA: MANEJO DE ARCHIVOS - LECTURA DE ARCHIVOS

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>Lectura de Archivos en PHP</title>
    <style>
        body { font-family: sans-serif; padding: 20px; line-height: 1.6; }
        .container { max-width: 800px; margin: auto; background: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        h1, h2 { color: #333; }
        pre { background-color: #eee; padding: 10px; border: 1px solid #ccc; overflow-x: auto; white-space: pre-wrap; word-wrap: break-word; }
        .error { color: red; font-weight: bold; padding:10px; border: 1px solid red; background: #ffebeb; }
        .success { color: green; font-weight: bold; }
        .file-content { border: 1px solid #007bff; padding: 15px; margin-top:10px; background-color: #fff; }
        .info { padding: 10px; background-color: #e7f3fe; border-left: 6px solid #2196F3; margin-bottom:15px;}
    </style>
</head>
<body>
<div class='container'>";

echo "<h1>Lectura de Archivos en PHP</h1>";

// Nombre del archivo de ejemplo que vamos a crear y leer
$nombreArchivo = "ejemplo.txt";
$nombreArchivoCSV = "datos.csv";

// Crear un archivo de ejemplo si no existe para las demostraciones
if (!file_exists($nombreArchivo)) {
    $contenidoInicial = "Hola, este es un archivo de ejemplo.\nSegunda línea de texto.\nTercera línea con números: 12345.\nPHP es divertido.\nÚltima línea.";
    file_put_contents($nombreArchivo, $contenidoInicial);
    echo "<p class='info'>Archivo '{$nombreArchivo}' creado para la demostración.</p>";
}
if (!file_exists($nombreArchivoCSV)) {
    $contenidoCSV = "ID,Nombre,Email\n1,Juan Perez,juan@example.com\n2,Ana Gomez,ana@example.com\n3,Luis Torres,luis@example.com";
    file_put_contents($nombreArchivoCSV, $contenidoCSV);
    echo "<p class='info'>Archivo '{$nombreArchivoCSV}' creado para la demostración.</p>";
}


// ========= 1. file_get_contents() - Leer todo el archivo en un string =========
// La forma más sencilla de leer el contenido completo de un archivo en una cadena.
// No es eficiente para archivos muy grandes, ya que carga todo en memoria.
echo "<h2>1. Leer con <code>file_get_contents()</code></h2>";
if (file_exists($nombreArchivo)) {
    $contenido = file_get_contents($nombreArchivo);
    if ($contenido !== false) {
        echo "<p class='success'>Contenido de '{$nombreArchivo}' leído con file_get_contents():</p>";
        echo "<div class='file-content'><pre>" . htmlspecialchars($contenido, ENT_QUOTES, 'UTF-8') . "</pre></div>";
    } else {
        echo "<p class='error'>Error al leer el archivo '{$nombreArchivo}' con file_get_contents().</p>";
    }
} else {
    echo "<p class='error'>El archivo '{$nombreArchivo}' no existe.</p>";
}
echo "<hr/>";


// ========= 2. file() - Leer el archivo en un array de líneas =========
// Lee todo el archivo y devuelve cada línea como un elemento de un array.
// Cada elemento del array incluirá el carácter de nueva línea al final.
// Tampoco es eficiente para archivos muy grandes.
echo "<h2>2. Leer con <code>file()</code></h2>";
if (file_exists($nombreArchivo)) {
    $lineas = file($nombreArchivo); // Por defecto, incluye los saltos de línea
    // $lineas = file($nombreArchivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); // Opciones útiles

    if ($lineas !== false) {
        echo "<p class='success'>Contenido de '{$nombreArchivo}' leído con file() (cada línea es un elemento de array):</p>";
        echo "<div class='file-content'>";
        echo "<pre>";
        print_r($lineas); // Muestra el array
        echo "</pre>";
        echo "<strong>Mostrando línea por línea:</strong><br/>";
        foreach ($lineas as $numeroLinea => $linea) {
            echo "Línea " . ($numeroLinea + 1) . ": " . htmlspecialchars($linea, ENT_QUOTES, 'UTF-8') . "<br/>";
        }
        echo "</div>";
    } else {
        echo "<p class='error'>Error al leer el archivo '{$nombreArchivo}' con file().</p>";
    }
} else {
    echo "<p class='error'>El archivo '{$nombreArchivo}' no existe.</p>";
}
echo "<hr/>";


// ========= 3. Usando fopen(), fgets() y fclose() - Lectura línea por línea (más control y eficiente para archivos grandes) =========
// Esta es la forma tradicional y más flexible.
// fopen() - Abre un archivo o URL.
// fgets() - Lee una línea del puntero del archivo.
// feof()  - Comprueba si se ha llegado al final del archivo (End-Of-File).
// fclose()- Cierra un puntero de archivo abierto.
echo "<h2>3. Leer línea por línea con <code>fopen()</code>, <code>fgets()</code>, <code>feof()</code>, <code>fclose()</code></h2>";
if (file_exists($nombreArchivo)) {
    $manejadorArchivo = fopen($nombreArchivo, "r"); // "r" = modo lectura (read)

    if ($manejadorArchivo) {
        echo "<p class='success'>Contenido de '{$nombreArchivo}' leído línea por línea:</p>";
        echo "<div class='file-content'>";
        $numLinea = 1;
        while (!feof($manejadorArchivo)) { // Mientras no sea el final del archivo
            $lineaActual = fgets($manejadorArchivo); // Lee la línea actual
            if ($lineaActual !== false) { // fgets devuelve false al final o en error
                 echo "Línea {$numLinea}: " . htmlspecialchars(trim($lineaActual), ENT_QUOTES, 'UTF-8') . "<br/>"; // trim() para quitar saltos de línea
            }
            $numLinea++;
        }
        fclose($manejadorArchivo); // ¡Importante cerrar el archivo!
        echo "</div>";
    } else {
        echo "<p class='error'>No se pudo abrir el archivo '{$nombreArchivo}' para lectura.</p>";
    }
} else {
    echo "<p class='error'>El archivo '{$nombreArchivo}' no existe.</p>";
}
echo "<hr/>";

// ========= 4. Usando fopen(), fread() y fclose() - Leer un número específico de bytes =========
// fread() - Lee hasta un número específico de bytes del puntero del archivo.
echo "<h2>4. Leer una cantidad específica de bytes con <code>fopen()</code>, <code>fread()</code></h2>";
if (file_exists($nombreArchivo)) {
    $manejadorArchivoBytes = fopen($nombreArchivo, "r");
    if ($manejadorArchivoBytes) {
        $bytesParaLeer = 50; // Leer los primeros 50 bytes
        $datosLeidos = fread($manejadorArchivoBytes, $bytesParaLeer);

        echo "<p class='success'>Primeros {$bytesParaLeer} bytes de '{$nombreArchivo}':</p>";
        echo "<div class='file-content'><pre>" . htmlspecialchars($datosLeidos, ENT_QUOTES, 'UTF-8') . "</pre></div>";

        fclose($manejadorArchivoBytes);
    } else {
        echo "<p class='error'>No se pudo abrir el archivo '{$nombreArchivo}' para lectura de bytes.</p>";
    }
} else {
    echo "<p class='error'>El archivo '{$nombreArchivo}' no existe.</p>";
}
echo "<hr/>";

// ========= 5. Leer archivos CSV con fgetcsv() =========
// fgetcsv() analiza una línea de un archivo CSV y devuelve un array con los campos.
echo "<h2>5. Leer un archivo CSV con <code>fgetcsv()</code></h2>";
if (file_exists($nombreArchivoCSV)) {
    $manejadorCSV = fopen($nombreArchivoCSV, "r");
    if ($manejadorCSV) {
        echo "<p class='success'>Contenido del archivo CSV '{$nombreArchivoCSV}':</p>";
        echo "<div class='file-content'>";
        echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>";

        $encabezados = fgetcsv($manejadorCSV); // Leer la primera línea (encabezados)
        if ($encabezados) {
            echo "<thead><tr>";
            foreach ($encabezados as $encabezado) {
                echo "<th>" . htmlspecialchars($encabezado, ENT_QUOTES, 'UTF-8') . "</th>";
            }
            echo "</tr></thead>";
        }

        echo "<tbody>";
        while (($fila = fgetcsv($manejadorCSV)) !== false) { // Lee cada fila hasta el final
            // $fila es un array con los campos de la línea actual del CSV
            echo "<tr>";
            foreach ($fila as $celda) {
                echo "<td>" . htmlspecialchars($celda, ENT_QUOTES, 'UTF-8') . "</td>";
            }
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
        fclose($manejadorCSV);
    } else {
        echo "<p class='error'>No se pudo abrir el archivo CSV '{$nombreArchivoCSV}'.</p>";
    }
} else {
    echo "<p class='error'>El archivo CSV '{$nombreArchivoCSV}' no existe.</p>";
}
echo "<hr/>";


echo "<h2>Funciones Útiles Adicionales:</h2>";
echo "<ul>";
echo "<li><code>file_exists(\$nombreArchivo)</code>: Verifica si un archivo o directorio existe.</li>";
echo "<li><code>is_readable(\$nombreArchivo)</code>: Verifica si un archivo existe y es legible.</li>";
echo "<li><code>filesize(\$nombreArchivo)</code>: Devuelve el tamaño del archivo en bytes.</li>";
echo "<li><code>pathinfo(\$nombreArchivo)</code>: Devuelve información sobre la ruta de un archivo (directorio, nombre base, extensión).</li>";
echo "</ul>";

if(file_exists($nombreArchivo)){
    echo "<div class='info'>";
    echo "<strong>Información sobre '{$nombreArchivo}':</strong><br/>";
    echo "Es legible: " . (is_readable($nombreArchivo) ? 'Sí' : 'No') . "<br/>";
    echo "Tamaño: " . filesize($nombreArchivo) . " bytes<br/>";
    echo "Pathinfo: <pre>";
    print_r(pathinfo($nombreArchivo));
    echo "</pre>";
    echo "</div>";
}

echo "<p><a href='index.php'>Volver al índice de manejo de archivos</a> (si existe)</p>";
echo "</div></body></html>";

?>
