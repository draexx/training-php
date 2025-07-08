<?php
// TEMA: MANEJO DE ARCHIVOS - PROCESAR SUBIDA DE ARCHIVOS

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>Resultado de Subida de Archivo</title>
    <style>
        body { font-family: sans-serif; padding: 20px; line-height: 1.6; }
        .container { max-width: 700px; margin: auto; background: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        h1 { color: #333; }
        .message { padding: 15px; margin-bottom: 20px; border-radius: 4px; border: 1px solid transparent; }
        .message.success { background-color: #d4edda; border-color: #c3e6cb; color: #155724; }
        .message.error { background-color: #f8d7da; border-color: #f5c6cb; color: #721c24; }
        .file-details { background-color: #e7f3fe; padding:15px; border-left: 5px solid #2196F3; }
        .file-details p { margin: 5px 0; }
        .file-details strong { color: #333; }
        img.preview { max-width: 300px; max-height:300px; margin-top:10px; border:1px solid #ccc; }
        a { color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class='container'>";

echo "<h1>Resultado de la Subida del Archivo</h1>";

$directorioSubidas = "uploads/"; // Directorio donde se guardarán los archivos subidos
                                 // ¡Asegúrate de que este directorio exista y tenga permisos de escritura!

// Crear el directorio de subidas si no existe
if (!is_dir($directorioSubidas)) {
    if (mkdir($directorioSubidas, 0755, true)) { // 0755 permisos, true para crear recursivamente
        echo "<p class='message success'>Directorio '{$directorioSubidas}' creado.</p>";
    } else {
        // Si no se puede crear el directorio, la subida fallará.
        echo "<p class='message error'>Error crítico: No se pudo crear el directorio de subidas '{$directorioSubidas}'. Verifica los permisos del servidor.</p>";
        // Podríamos detener el script aquí si es un error fatal para la lógica de la app.
        // exit;
    }
}


// Verificar si el formulario fue enviado y si se seleccionó un archivo
// $_FILES es una variable superglobal que contiene información sobre los archivos subidos.
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_file']) && isset($_FILES['archivo_usuario'])) {

    $archivoInfo = $_FILES['archivo_usuario'];
    $descripcion = isset($_POST['descripcion_archivo']) ? htmlspecialchars(trim($_POST['descripcion_archivo']), ENT_QUOTES, 'UTF-8') : "Sin descripción";

    echo "<div class='file-details'>";
    echo "<h3>Información del Archivo Recibido (\$_FILES):</h3>";
    echo "<pre>";
    print_r($archivoInfo);
    echo "</pre>";
    echo "<p><strong>Descripción proporcionada:</strong> " . $descripcion . "</p>";
    echo "</div><br>";

    // --- Comprobaciones de Errores de Subida ---
    // $archivoInfo['error'] contiene un código de error. 0 significa sin error.
    if ($archivoInfo['error'] === UPLOAD_ERR_OK) { // UPLOAD_ERR_OK es 0
        $nombreOriginal = $archivoInfo['name'];
        $tipoMIME = $archivoInfo['type']; // Tipo MIME proporcionado por el navegador (no siempre fiable)
        $tamanoBytes = $archivoInfo['size'];
        $rutaTemporal = $archivoInfo['tmp_name']; // Ruta temporal donde PHP guarda el archivo subido

        // --- Validaciones de Seguridad y Lógica ---

        // 1. Validar tamaño del archivo (ej. máximo 2MB)
        $maxTamanoPermitido = 2 * 1024 * 1024; // 2 MB en bytes
        if ($tamanoBytes > $maxTamanoPermitido) {
            echo "<p class='message error'>Error: El archivo es demasiado grande. Máximo permitido: " . ($maxTamanoPermitido / 1024 / 1024) . " MB.</p>";
        } else {
            // 2. Validar tipo de archivo (MIME type y extensión)
            // Es mejor validar tanto la extensión como el tipo MIME real si es posible (ej. con finfo_file).
            $extension = strtolower(pathinfo($nombreOriginal, PATHINFO_EXTENSION));
            $tiposPermitidos = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
            $mimesPermitidos = [
                'image/jpeg' => 'jpg', // Mapeo de MIME a extensión esperada
                'image/png' => 'png',
                'image/gif' => 'gif',
                'application/pdf' => 'pdf'
            ];

            // Verificación más robusta del tipo MIME usando Fileinfo (si la extensión está habilitada)
            $tipoMIMEReal = "";
            if (function_exists('finfo_open')) {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $tipoMIMEReal = finfo_file($finfo, $rutaTemporal);
                finfo_close($finfo);
            } else {
                // Fallback si Fileinfo no está disponible (menos seguro)
                $tipoMIMEReal = $tipoMIME; // Usar el tipo enviado por el navegador
                echo "<p class='message error' style='background-color:#fff3cd; border-color:#ffeeba; color:#856404;'>Advertencia: La extensión Fileinfo de PHP no está habilitada. La validación del tipo de archivo es menos segura.</p>";
            }


            if (in_array($extension, $tiposPermitidos) && isset($mimesPermitidos[$tipoMIMEReal]) && $mimesPermitidos[$tipoMIMEReal] == $extension) {

                // 3. Generar un nombre de archivo único para evitar sobrescrituras y problemas de seguridad.
                // Se puede usar uniqid(), hash, o combinar con el nombre original sanitizado.
                $nombreSeguro = uniqid("archivo_", true) . "." . $extension;
                // Alternativa: Sanitizar el nombre original
                // $nombreSanitizado = preg_replace("/[^a-zA-Z0-9.\-_]/", "", basename($nombreOriginal));
                // $nombreSeguro = time() . "_" . $nombreSanitizado;

                $rutaDestino = $directorioSubidas . $nombreSeguro;

                // 4. Mover el archivo de la ruta temporal a la ruta de destino final.
                // move_uploaded_file() es la función segura para esto.
                if (move_uploaded_file($rutaTemporal, $rutaDestino)) {
                    echo "<p class='message success'>¡El archivo '" . htmlspecialchars($nombreOriginal, ENT_QUOTES, 'UTF-8') . "' se ha subido y guardado correctamente como '{$nombreSeguro}'!</p>";
                    echo "<div class='file-details'>";
                    echo "<p><strong>Nombre original:</strong> " . htmlspecialchars($nombreOriginal, ENT_QUOTES, 'UTF-8') . "</p>";
                    echo "<p><strong>Nombre en servidor:</strong> " . htmlspecialchars($nombreSeguro, ENT_QUOTES, 'UTF-8') . "</p>";
                    echo "<p><strong>Tipo MIME (real):</strong> " . htmlspecialchars($tipoMIMEReal, ENT_QUOTES, 'UTF-8') . "</p>";
                    echo "<p><strong>Tamaño:</strong> " . round($tamanoBytes / 1024, 2) . " KB</p>";
                    echo "<p><strong>Ruta en servidor:</strong> " . htmlspecialchars($rutaDestino, ENT_QUOTES, 'UTF-8') . "</p>";
                    echo "<p><strong>Descripción:</strong> " . $descripcion . "</p>";

                    // Mostrar vista previa si es una imagen
                    if (strpos($tipoMIMEReal, 'image/') === 0) {
                        echo "<p><strong>Vista previa:</strong><br><img src='" . htmlspecialchars($rutaDestino, ENT_QUOTES, 'UTF-8') . "' alt='Vista previa' class='preview'></p>";
                    } elseif ($tipoMIMEReal == 'application/pdf') {
                         echo "<p><a href='" . htmlspecialchars($rutaDestino, ENT_QUOTES, 'UTF-8') . "' target='_blank'>Ver PDF subido</a></p>";
                    }
                    echo "</div>";

                    // Aquí podrías guardar la información del archivo (nombreSeguro, nombreOriginal, descripcion, etc.)
                    // en una base de datos.

                } else {
                    echo "<p class='message error'>Error: No se pudo mover el archivo subido al directorio de destino. Verifica los permisos de '{$directorioSubidas}'.</p>";
                }

            } else {
                echo "<p class='message error'>Error: Tipo de archivo no permitido. Solo se aceptan JPG, JPEG, PNG, GIF, PDF.</p>";
                echo "<p class='message error' style='font-size:0.9em'>(Extensión detectada: '{$extension}', Tipo MIME real: '{$tipoMIMEReal}')</p>";
            }
        }

    } else {
        // Manejar otros errores de subida de $_FILES['archivo_usuario']['error']
        $mensajeErrorSubida = "Error desconocido al subir el archivo.";
        switch ($archivoInfo['error']) {
            case UPLOAD_ERR_INI_SIZE:
                $mensajeErrorSubida = "El archivo excede la directiva upload_max_filesize en php.ini.";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $mensajeErrorSubida = "El archivo excede la directiva MAX_FILE_SIZE especificada en el formulario HTML.";
                break;
            case UPLOAD_ERR_PARTIAL:
                $mensajeErrorSubida = "El archivo se subió solo parcialmente.";
                break;
            case UPLOAD_ERR_NO_FILE:
                $mensajeErrorSubida = "No se subió ningún archivo.";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $mensajeErrorSubida = "Falta la carpeta temporal del servidor.";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $mensajeErrorSubida = "No se pudo escribir el archivo en el disco.";
                break;
            case UPLOAD_ERR_EXTENSION:
                $mensajeErrorSubida = "Una extensión de PHP detuvo la subida del archivo.";
                break;
        }
        echo "<p class='message error'>Error al subir el archivo: " . $mensajeErrorSubida . " (Código: {$archivoInfo['error']})</p>";
    }

} else {
    // Si no se accedió al script mediante POST o no se envió el archivo esperado
    echo "<p class='message error'>No se ha enviado ningún archivo o el formulario no se procesó correctamente.</p>";
}

echo "<p><a href='subida_archivos.html'>&laquo; Volver al formulario de subida</a></p>";
echo "</div></body></html>";

?>
