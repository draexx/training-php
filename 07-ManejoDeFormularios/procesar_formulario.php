<?php
// TEMA: MANEJO DE FORMULARIOS EN PHP

// Este script procesará datos enviados tanto por GET como por POST.

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>Procesamiento de Formulario</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; padding: 20px; background-color: #f9f9f9; }
        .container { max-width: 800px; margin: auto; background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        h1, h2 { color: #333; }
        pre { background-color: #eee; padding: 10px; border: 1px solid #ccc; overflow-x: auto; }
        .data-section { margin-bottom: 20px; padding-bottom:10px; border-bottom: 1px dotted #ccc; }
        .data-label { font-weight: bold; color: #555; }
        .data-value { color: #0066cc; }
        .error { color: red; font-weight: bold;}
        .success { color: green; font-weight: bold;}
        a { color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class='container'>";

echo "<h1>Resultados del Procesamiento del Formulario</h1>";

// ========= DETECTAR EL MÉTODO DE SOLICITUD =========
$metodo = $_SERVER['REQUEST_METHOD'];
echo "<p class='data-section'><span class='data-label'>Método de Solicitud HTTP:</span> <span class='data-value'>{$metodo}</span></p>";

// ========= PROCESAMIENTO DE DATOS DE $_GET =========
// Los datos enviados por GET son visibles en la URL (ej: script.php?nombre=valor&otro=valor2)
// Se acceden a través del array superglobal $_GET.
if ($metodo == 'GET' && !empty($_GET)) {
    echo "<h2>Datos Recibidos por GET:</h2>";
    echo "<pre>";
    print_r($_GET);
    echo "</pre>";

    // Acceder a un parámetro específico de GET
    // Es CRUCIAL validar y sanitizar los datos antes de usarlos, especialmente si se van a mostrar o guardar.
    if (isset($_GET['q'])) {
        $terminoBusqueda = htmlspecialchars($_GET['q'], ENT_QUOTES, 'UTF-8'); // Sanitizar para evitar XSS
        echo "<p><span class='data-label'>Término de búsqueda (q):</span> <span class='data-value'>{$terminoBusqueda}</span></p>";
    }
    if (isset($_GET['cat'])) {
        $categoriaBusqueda = htmlspecialchars($_GET['cat'], ENT_QUOTES, 'UTF-8');
        echo "<p><span class='data-label'>Categoría (cat):</span> <span class='data-value'>{$categoriaBusqueda}</span></p>";
    }
    echo "<hr/>";
}


// ========= PROCESAMIENTO DE DATOS DE $_POST =========
// Los datos enviados por POST no son visibles en la URL.
// Se acceden a través del array superglobal $_POST.
// Ideal para datos sensibles o grandes cantidades de datos.
if ($metodo == 'POST' && !empty($_POST)) {
    echo "<h2>Datos Recibidos por POST:</h2>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    echo "<h3>Validación y Sanitización de Datos POST:</h3>";

    $errores = [];
    $datosValidos = [];

    // --- Nombre Completo ---
    if (isset($_POST['nombre'])) {
        $nombre = trim($_POST['nombre']); // Eliminar espacios en blanco al inicio y final
        if (empty($nombre)) {
            $errores['nombre'] = "El nombre completo es obligatorio.";
        } elseif (strlen($nombre) < 3) {
            $errores['nombre'] = "El nombre debe tener al menos 3 caracteres.";
        } else {
            $datosValidos['nombre'] = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');
        }
    } else {
        $errores['nombre'] = "El campo nombre no fue enviado.";
    }

    // --- Correo Electrónico ---
    if (isset($_POST['email'])) {
        $email = trim($_POST['email']);
        if (empty($email)) {
            $errores['email'] = "El correo electrónico es obligatorio.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) { // filter_var para validar email
            $errores['email'] = "El formato del correo electrónico no es válido.";
        } else {
            // Sanitizar email (aunque filter_var ya hace una buena parte)
            $datosValidos['email'] = filter_var($email, FILTER_SANITIZE_EMAIL);
        }
    } else {
        $errores['email'] = "El campo email no fue enviado.";
    }

    // --- Contraseña (Ejemplo básico - NO HACER ESTO EN PRODUCCIÓN SIN HASHING) ---
    // En un sistema real, las contraseñas NUNCA se deben guardar o mostrar en texto plano.
    // Se deben usar funciones de hashing como password_hash() y password_verify().
    if (isset($_POST['contrasena'])) {
        $contrasena = $_POST['contrasena']; // No sanitizar para mostrar, pero sí para BBDD (hashear)
        if (empty($contrasena)) {
            $errores['contrasena'] = "La contraseña es obligatoria.";
        } elseif (strlen($contrasena) < 6) {
            $errores['contrasena'] = "La contraseña debe tener al menos 6 caracteres.";
        } else {
            // Para mostrarla aquí (solo ejemplo, no hacer en real)
            $datosValidos['contrasena_recibida_longitud'] = strlen($contrasena);
            // En un caso real: $hashContrasena = password_hash($contrasena, PASSWORD_DEFAULT);
        }
    } else {
        $errores['contrasena'] = "El campo contraseña no fue enviado.";
    }

    // --- Asunto ---
    if (isset($_POST['asunto'])) {
        $asunto = trim($_POST['asunto']);
        if (empty($asunto)) {
            $errores['asunto'] = "El asunto es obligatorio.";
        } else {
            $datosValidos['asunto'] = htmlspecialchars($asunto, ENT_QUOTES, 'UTF-8');
        }
    }

    // --- Género (Radio Buttons) ---
    // Solo uno puede ser seleccionado.
    if (isset($_POST['genero'])) {
        $genero = $_POST['genero'];
        $generosPermitidos = ['masculino', 'femenino', 'otro'];
        if (in_array($genero, $generosPermitidos)) {
            $datosValidos['genero'] = htmlspecialchars($genero, ENT_QUOTES, 'UTF-8');
        } else {
            $errores['genero'] = "Valor de género no válido.";
        }
    } else {
        $errores['genero'] = "Debe seleccionar un género.";
    }

    // --- País (Select) ---
    if (isset($_POST['pais'])) {
        $pais = $_POST['pais'];
        // Podríamos tener una lista de países válidos para comparar
        if (!empty($pais)) {
            $datosValidos['pais'] = htmlspecialchars($pais, ENT_QUOTES, 'UTF-8');
        } else {
            $errores['pais'] = "Debe seleccionar un país.";
        }
    }

    // --- Intereses (Checkboxes) ---
    // Pueden seleccionarse múltiples. Llegan como un array si name="intereses[]".
    if (isset($_POST['intereses']) && is_array($_POST['intereses'])) {
        $interesesSeleccionados = [];
        $interesesPermitidos = ['deporte', 'tecnologia', 'arte', 'viajes']; // Lista de valores válidos
        foreach ($_POST['intereses'] as $interes) {
            if (in_array($interes, $interesesPermitidos)) {
                $interesesSeleccionados[] = htmlspecialchars($interes, ENT_QUOTES, 'UTF-8');
            }
        }
        if (!empty($interesesSeleccionados)) {
            $datosValidos['intereses'] = $interesesSeleccionados;
        } else {
             $errores['intereses'] = "Valor de interés no válido o no se seleccionó ninguno que sea válido.";
        }
    } else {
        // No es un error si no se selecciona ninguno, a menos que sea obligatorio.
        $datosValidos['intereses'] = []; // O un mensaje como "Ningún interés seleccionado"
    }

    // --- Mensaje (Textarea) ---
    if (isset($_POST['mensaje'])) {
        $mensaje = trim($_POST['mensaje']);
        if (empty($mensaje)) {
            $errores['mensaje'] = "El mensaje es obligatorio.";
        } else {
            // Es importante sanitizar texto largo que puede contener HTML o JS.
            // htmlspecialchars es un buen comienzo. Para mayor seguridad, se pueden usar librerías como HTMLPurifier.
            $datosValidos['mensaje'] = htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8');
            // Si quieres preservar saltos de línea para mostrarlos en HTML:
            // $datosValidos['mensaje_html'] = nl2br(htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8'));
        }
    }

    // --- Términos y Condiciones (Checkbox único) ---
    if (isset($_POST['terminos']) && $_POST['terminos'] == 'aceptado') {
        $datosValidos['terminos'] = "Aceptados";
    } else {
        $errores['terminos'] = "Debe aceptar los términos y condiciones.";
    }

    // --- Campo Oculto ---
    if (isset($_POST['id_formulario'])) {
        $datosValidos['id_formulario'] = htmlspecialchars($_POST['id_formulario'], ENT_QUOTES, 'UTF-8');
    }


    // --- Mostrar Errores o Datos Válidos ---
    if (!empty($errores)) {
        echo "<h4><span class='error'>Errores de Validación:</span></h4>";
        echo "<ul>";
        foreach ($errores as $campo => $error) {
            echo "<li><span class='data-label'>" . ucfirst($campo) . ":</span> <span class='error'>{$error}</span></li>";
        }
        echo "</ul>";
    } else {
        echo "<h4><span class='success'>¡Formulario validado correctamente!</span></h4>";
        echo "<p><span class='success'>Procesando los siguientes datos:</span></p>";
        echo "<ul>";
        foreach ($datosValidos as $campo => $valor) {
            if (is_array($valor)) {
                echo "<li><span class='data-label'>" . ucfirst($campo) . ":</span> <span class='data-value'>" . implode(", ", $valor) . "</span></li>";
            } else {
                echo "<li><span class='data-label'>" . ucfirst($campo) . ":</span> <span class='data-value'>{$valor}</span></li>";
            }
        }
        echo "</ul>";
        // Aquí iría la lógica para guardar en base de datos, enviar email, etc.
        echo "<p class='success'><em>Simulación: Los datos serían enviados por email o guardados en una base de datos.</em></p>";
    }
    echo "<hr/>";
}


// ========= $_REQUEST =========
// $_REQUEST es un array asociativo que por defecto contiene el contenido de $_GET, $_POST y $_COOKIE.
// Su uso no es muy recomendado porque puede llevar a confusiones sobre el origen de los datos
// y posibles problemas de seguridad si no se tiene cuidado (ej. HTTP Verb Tampering).
// Es mejor ser explícito y usar $_GET o $_POST.
if (!empty($_REQUEST) && ($metodo == 'GET' || $metodo == 'POST')) {
    echo "<h2>Contenido de \$_REQUEST:</h2>";
    echo "<p><em>Nota: \$_REQUEST combina \$_GET, \$_POST y \$_COOKIE (el orden de precedencia depende de la configuración de PHP 'request_order' o 'variables_order'). Su uso directo es generalmente desaconsejado por claridad y seguridad.</em></p>";
    echo "<pre>";
    print_r($_REQUEST);
    echo "</pre>";
    echo "<hr/>";
}


// ========= SEGURIDAD EN FORMULARIOS (Conceptos Clave) =========
echo "<h2>Consideraciones de Seguridad Importantes:</h2>";
echo "<ul>
        <li><strong>Validación de Datos (Server-Side):</strong> SIEMPRE validar los datos en el servidor, incluso si hay validación en el cliente (JavaScript). La validación del cliente es solo por UX.</li>
        <li><strong>Sanitización de Salida (Output Sanitization):</strong> Al mostrar datos recibidos del usuario en una página HTML, SIEMPRE sanitizarlos para prevenir ataques XSS (Cross-Site Scripting). Usa funciones como <code>htmlspecialchars()</code>.</li>
        <li><strong>Prevención de CSRF (Cross-Site Request Forgery):</strong> Implementar tokens CSRF para asegurar que las solicitudes POST provengan de tu propio formulario y no de un sitio malicioso.</li>
        <li><strong>Inyección SQL:</strong> Si interactúas con bases de datos, usa sentencias preparadas (prepared statements) con PDO o MySQLi para prevenir la inyección SQL. NUNCA concatenes directamente datos del usuario en consultas SQL.</li>
        <li><strong>Manejo de Contraseñas:</strong> NUNCA guardes contraseñas en texto plano. Usa <code>password_hash()</code> para crearlas y <code>password_verify()</code> para comprobarlas.</li>
        <li><strong>Subida de Archivos:</strong> Ser extremadamente cuidadoso con la subida de archivos. Validar tipo, tamaño, nombre, y guardarlos en una ubicación segura fuera del directorio web raíz si es posible.</li>
        <li><strong>HTTPS:</strong> Usar HTTPS para encriptar la comunicación entre el cliente y el servidor, especialmente si se manejan datos sensibles.</li>
      </ul>";


echo "<p><a href='formulario.html'>&laquo; Volver al formulario</a></p>";
echo "</div></body></html>";

?>
