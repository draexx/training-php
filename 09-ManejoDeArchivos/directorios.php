<?php
// TEMA: MANEJO DE ARCHIVOS - OPERACIONES CON DIRECTORIOS

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>Operaciones con Directorios en PHP</title>
    <style>
        body { font-family: sans-serif; padding: 20px; line-height: 1.6; }
        .container { max-width: 800px; margin: auto; background: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        h1, h2, h3 { color: #333; }
        pre { background-color: #eee; padding: 10px; border: 1px solid #ccc; overflow-x: auto; white-space: pre-wrap; word-wrap: break-word; }
        .error { color: red; font-weight: bold; padding:10px; border: 1px solid red; background: #ffebeb; }
        .success { color: green; font-weight: bold; padding:10px; border: 1px solid green; background: #e6ffed; }
        .info { padding: 10px; background-color: #e7f3fe; border-left: 6px solid #2196F3; margin-bottom:15px;}
        ul { list-style-type: none; padding-left: 0; }
        li.dir { color: #007bff; font-weight: bold; }
        li.file { color: #28a745; }
        .action-form { margin-bottom: 20px; padding: 15px; border: 1px solid #eee; background-color: #fff; }
        .action-form label { display: inline-block; margin-right: 10px; }
        .action-form input[type='text'] { padding: 5px; border: 1px solid #ccc; border-radius: 3px; }
        .action-form button { padding: 6px 12px; background-color: #5cb85c; color: white; border: none; border-radius: 3px; cursor: pointer; }
        .action-form button.delete { background-color: #d9534f; }
        .action-form button:hover { opacity: 0.8; }
    </style>
</head>
<body>
<div class='container'>";

echo "<h1>Operaciones con Directorios en PHP</h1>";

// Directorio base para las demostraciones
$directorioBase = "mi_directorio_de_prueba";
$mensajeGlobal = "";

// --- Crear directorio base si no existe ---
if (!is_dir($directorioBase)) {
    if (mkdir($directorioBase, 0755)) { // 0755 son permisos comunes
        $mensajeGlobal .= "<p class='success'>Directorio base '{$directorioBase}' creado con éxito.</p>";
    } else {
        $mensajeGlobal .= "<p class='error'>Error al crear el directorio base '{$directorioBase}'. Verifica los permisos.</p>";
    }
}


// --- Procesar acciones del formulario ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['accion'])) {
        $accion = $_POST['accion'];
        $nombreElemento = isset($_POST['nombre_elemento']) ? trim($_POST['nombre_elemento']) : '';
        $rutaCompletaElemento = $directorioBase . DIRECTORY_SEPARATOR . $nombreElemento;

        // Validar que el nombre no contenga caracteres peligrosos (simplificado)
        if (!empty($nombreElemento) && !preg_match('/[\.\/\\\]/', $nombreElemento)) {
            switch ($accion) {
                case 'crear_dir':
                    if (!is_dir($rutaCompletaElemento)) {
                        if (mkdir($rutaCompletaElemento, 0755)) {
                            $mensajeGlobal .= "<p class='success'>Directorio '{$nombreElemento}' creado dentro de '{$directorioBase}'.</p>";
                        } else {
                            $mensajeGlobal .= "<p class='error'>Error al crear directorio '{$nombreElemento}'.</p>";
                        }
                    } else {
                        $mensajeGlobal .= "<p class='info'>El directorio '{$nombreElemento}' ya existe.</p>";
                    }
                    break;
                case 'crear_archivo':
                     if (!file_exists($rutaCompletaElemento)) {
                        if (file_put_contents($rutaCompletaElemento, "Archivo de prueba.") !== false) {
                            $mensajeGlobal .= "<p class='success'>Archivo '{$nombreElemento}' creado dentro de '{$directorioBase}'.</p>";
                        } else {
                            $mensajeGlobal .= "<p class='error'>Error al crear archivo '{$nombreElemento}'.</p>";
                        }
                    } else {
                        $mensajeGlobal .= "<p class='info'>El archivo '{$nombreElemento}' ya existe.</p>";
                    }
                    break;
                case 'eliminar':
                    if (file_exists($rutaCompletaElemento)) {
                        if (is_dir($rutaCompletaElemento)) {
                            // rmdir solo elimina directorios vacíos.
                            // Para directorios no vacíos, se necesitaría una función recursiva.
                            if (@rmdir($rutaCompletaElemento)) { // @ para suprimir warning si no está vacío
                                $mensajeGlobal .= "<p class='success'>Directorio '{$nombreElemento}' eliminado.</p>";
                            } else {
                                $mensajeGlobal .= "<p class='error'>Error al eliminar directorio '{$nombreElemento}'. Puede que no esté vacío o no tengas permisos.</p>";
                            }
                        } else { // Es un archivo
                            if (unlink($rutaCompletaElemento)) {
                                $mensajeGlobal .= "<p class='success'>Archivo '{$nombreElemento}' eliminado.</p>";
                            } else {
                                $mensajeGlobal .= "<p class='error'>Error al eliminar archivo '{$nombreElemento}'.</p>";
                            }
                        }
                    } else {
                         $mensajeGlobal .= "<p class='error'>El elemento '{$nombreElemento}' no existe para eliminar.</p>";
                    }
                    break;
            }
        } elseif (!empty($nombreElemento)) {
            $mensajeGlobal .= "<p class='error'>Nombre de elemento '{$nombreElemento}' no válido (no uses ., / o \\).</p>";
        } else {
             $mensajeGlobal .= "<p class='error'>El nombre del elemento no puede estar vacío.</p>";
        }
    }
}
echo $mensajeGlobal; // Mostrar mensajes de acciones

?>
<div class="action-form">
    <h3>Acciones en '<?php echo $directorioBase; ?>':</h3>
    <form method="POST" action="directorios.php" style="display:inline-block; margin-right:10px;">
        <label for="nombre_crear_dir">Nuevo Dir:</label>
        <input type="text" id="nombre_crear_dir" name="nombre_elemento" placeholder="nombre_directorio" required>
        <input type="hidden" name="accion" value="crear_dir">
        <button type="submit">Crear Directorio</button>
    </form>
    <form method="POST" action="directorios.php" style="display:inline-block; margin-right:10px;">
        <label for="nombre_crear_archivo">Nuevo Archivo:</label>
        <input type="text" id="nombre_crear_archivo" name="nombre_elemento" placeholder="nombre_archivo.txt" required>
        <input type="hidden" name="accion" value="crear_archivo">
        <button type="submit">Crear Archivo</button>
    </form>
    <form method="POST" action="directorios.php" style="display:inline-block;">
        <label for="nombre_eliminar">Eliminar:</label>
        <input type="text" id="nombre_eliminar" name="nombre_elemento" placeholder="nombre_elemento" required>
        <input type="hidden" name="accion" value="eliminar">
        <button type="submit" class="delete">Eliminar Elemento</button>
    </form>
</div>
<hr/>
<?php

// ========= 1. Comprobar si un directorio existe: is_dir() =========
echo "<h2>1. Comprobar si un directorio existe: <code>is_dir()</code></h2>";
if (is_dir($directorioBase)) {
    echo "<p class='success'>El directorio '{$directorioBase}' existe.</p>";
} else {
    echo "<p class='error'>El directorio '{$directorioBase}' NO existe.</p>";
}
echo "<p><code>is_dir('archivo_inexistente.txt')</code>: " . (is_dir('archivo_inexistente.txt') ? 'Sí (Error lógico)' : 'No') . "</p>";
echo "<p><code>is_dir('leer_archivos.php')</code> (es un archivo): " . (is_dir('leer_archivos.php') ? 'Sí (Error lógico)' : 'No') . "</p>";
echo "<hr/>";


// ========= 2. Crear un directorio: mkdir() =========
// Ya se usó arriba para el directorio base.
echo "<h2>2. Crear un directorio: <code>mkdir()</code></h2>";
$nuevoDir = $directorioBase . DIRECTORY_SEPARATOR . "subdirectorio_nuevo";
if (!is_dir($nuevoDir)) {
    if (mkdir($nuevoDir, 0777, true)) { // 0777 (más permisivo, cuidado en producción), true para crear recursivamente si los padres no existen
        echo "<p class='success'>Directorio '{$nuevoDir}' creado con éxito.</p>";
        // Crear un archivo dentro para probar eliminación de no vacíos
        file_put_contents($nuevoDir . DIRECTORY_SEPARATOR . "archivo_interno.txt", "test");
    } else {
        echo "<p class='error'>Error al crear '{$nuevoDir}'.</p>";
    }
} else {
    echo "<p class='info'>El directorio '{$nuevoDir}' ya existía.</p>";
}
echo "<hr/>";


// ========= 3. Eliminar un directorio: rmdir() =========
// Solo funciona si el directorio está vacío.
echo "<h2>3. Eliminar un directorio: <code>rmdir()</code></h2>";
$dirParaEliminar = $directorioBase . DIRECTORY_SEPARATOR . "dir_a_borrar";
if (!is_dir($dirParaEliminar)) {
    mkdir($dirParaEliminar); // Crear para el ejemplo
    echo "<p class='info'>Directorio '{$dirParaEliminar}' creado para prueba de eliminación.</p>";
}

if (is_dir($dirParaEliminar)) {
    if (rmdir($dirParaEliminar)) {
        echo "<p class='success'>Directorio '{$dirParaEliminar}' (vacío) eliminado con éxito.</p>";
    } else {
        echo "<p class='error'>Error al eliminar '{$dirParaEliminar}'. Puede que no esté vacío o no tengas permisos.</p>";
    }
}

// Intentar eliminar el directorio no vacío creado antes ($nuevoDir)
if (is_dir($nuevoDir)) {
     echo "<p>Intentando eliminar '{$nuevoDir}' (que no está vacío):</p>";
    if (@rmdir($nuevoDir)) { // Usamos @ para suprimir el warning esperado
        echo "<p class='success'>Directorio '{$nuevoDir}' eliminado (esto no debería pasar si no está vacío).</p>";
    } else {
        echo "<p class='error'>Error al eliminar '{$nuevoDir}' con rmdir(), probablemente porque no está vacío.</p>";
    }
}
echo "<hr/>";


// ========= 4. Leer el contenido de un directorio: scandir() =========
// Devuelve un array con los nombres de archivos y directorios, incluyendo "." y "..".
echo "<h2>4. Leer contenido de un directorio: <code>scandir()</code></h2>";
if (is_dir($directorioBase)) {
    echo "<p>Contenido de '{$directorioBase}' usando <code>scandir()</code>:</p>";
    $elementos = scandir($directorioBase);
    echo "<pre>";
    print_r($elementos);
    echo "</pre>";

    echo "<ul>";
    foreach ($elementos as $elemento) {
        if ($elemento != "." && $elemento != "..") {
            $rutaCompleta = $directorioBase . DIRECTORY_SEPARATOR . $elemento;
            if (is_dir($rutaCompleta)) {
                echo "<li class='dir'>[DIR] " . htmlspecialchars($elemento) . "</li>";
            } else {
                echo "<li class='file'>[FILE] " . htmlspecialchars($elemento) . "</li>";
            }
        }
    }
    echo "</ul>";
} else {
    echo "<p class='error'>El directorio '{$directorioBase}' no existe para listar.</p>";
}
echo "<hr/>";


// ========= 5. Leer el contenido de un directorio: opendir(), readdir(), closedir() =========
// Método más tradicional y con más control, similar a como se leen archivos.
echo "<h2>5. Leer contenido con <code>opendir()</code>, <code>readdir()</code>, <code>closedir()</code></h2>";
if (is_dir($directorioBase)) {
    echo "<p>Contenido de '{$directorioBase}' usando el método tradicional:</p>";
    if ($manejadorDir = opendir($directorioBase)) {
        echo "<ul>";
        // readdir() devuelve el nombre del siguiente elemento, o false si no hay más.
        while (($entrada = readdir($manejadorDir)) !== false) {
            if ($entrada != "." && $entrada != "..") {
                 $rutaCompleta = $directorioBase . DIRECTORY_SEPARATOR . $entrada;
                 if (is_dir($rutaCompleta)) {
                    echo "<li class='dir'>[DIR] " . htmlspecialchars($entrada) . "</li>";
                } else {
                    echo "<li class='file'>[FILE] " . htmlspecialchars($entrada) . "</li>";
                }
            }
        }
        echo "</ul>";
        closedir($manejadorDir); // ¡Importante cerrar el manejador!
    } else {
        echo "<p class='error'>No se pudo abrir el directorio '{$directorioBase}'.</p>";
    }
} else {
    echo "<p class='error'>El directorio '{$directorioBase}' no existe para listar.</p>";
}
echo "<hr/>";


// ========= 6. Otras funciones útiles =========
echo "<h2>6. Otras Funciones Útiles para Directorios</h2>";
echo "<ul>";
echo "<li><code>getcwd()</code>: Obtiene el directorio de trabajo actual. -> <span class='info'>" . getcwd() . "</span></li>";
echo "<li><code>chdir(\$directorio)</code>: Cambia el directorio de trabajo actual.</li>";
// chdir($directorioBase); echo "<li>Directorio actual cambiado a: " . getcwd() . "</li>"; chdir(".."); // Volver
echo "<li><code>dirname(\$ruta)</code>: Devuelve la parte del directorio de una ruta. Ej: <code>dirname('{$directorioBase}/archivo.txt')</code> -> <span class='info'>" . dirname($directorioBase . '/archivo.txt') . "</span></li>";
echo "<li><code>basename(\$ruta)</code>: Devuelve el nombre base (archivo o directorio final) de una ruta. Ej: <code>basename('{$directorioBase}/archivo.txt')</code> -> <span class='info'>" . basename($directorioBase . '/archivo.txt') . "</span></li>";
echo "<li><code>is_writable(\$ruta)</code>: Comprueba si un archivo/directorio es escribible.</li>";
echo "<li><code>rename(\$viejo, \$nuevo)</code>: Renombra o mueve un archivo o directorio.</li>";
echo "</ul>";

// Ejemplo de rename (mover un archivo a un subdirectorio)
$archivoParaMover = $directorioBase . DIRECTORY_SEPARATOR . "moverme.txt";
$destinoMovimiento = $directorioBase . DIRECTORY_SEPARATOR . "subdirectorio_nuevo" . DIRECTORY_SEPARATOR . "movido_aqui.txt";
if (!file_exists($archivoParaMover) && is_dir($directorioBase . DIRECTORY_SEPARATOR . "subdirectorio_nuevo")) {
    file_put_contents($archivoParaMover, "Contenido para mover.");
    echo "<p class='info'>Archivo 'moverme.txt' creado para prueba de rename.</p>";
}
if (file_exists($archivoParaMover) && is_dir(dirname($destinoMovimiento)) && !file_exists($destinoMovimiento) ) {
    if (rename($archivoParaMover, $destinoMovimiento)) {
        echo "<p class='success'>Archivo '{$archivoParaMover}' renombrado/movido a '{$destinoMovimiento}'.</p>";
    } else {
        echo "<p class='error'>Error al renombrar/mover '{$archivoParaMover}'.</p>";
    }
} elseif (file_exists($destinoMovimiento)) {
     echo "<p class='info'>El archivo 'movido_aqui.txt' ya existe en el destino.</p>";
}


echo "<p><strong>Nota sobre eliminar directorios no vacíos:</strong> <code>rmdir()</code> solo funciona para directorios vacíos. Para eliminar directorios con contenido, necesitarías una función recursiva que primero elimine todos los archivos y subdirectorios dentro de él.</p>";

echo "<p><a href='index.php'>Volver al índice de manejo de archivos</a> (si existe)</p>";
echo "<p><a href='leer_archivos.php'>Ir a Leer Archivos</a> | <a href='escribir_archivos.php'>Ir a Escribir Archivos</a></p>";

echo "</div></body></html>";

?>
