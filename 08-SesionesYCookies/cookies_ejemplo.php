<?php
// TEMA: COOKIES EN PHP

// Las cookies son pequeños archivos de texto que el servidor web puede almacenar en el
// navegador del cliente. Se utilizan para recordar información sobre el usuario
// entre diferentes visitas o páginas.

// IMPORTANTE: Las cookies se envían con las cabeceras HTTP.
// Por lo tanto, la función setcookie() DEBE ser llamada ANTES de cualquier
// salida HTML o de texto al navegador (similar a session_start()).

// ========= ESTABLECER UNA COOKIE =========
// setcookie(nombre, valor, expiracion, path, domain, secure, httponly)

// 1. Cookie básica que expira al cerrar el navegador (cookie de sesión)
// setcookie("usuario", "JuanPerez"); // Comentado para no interferir con el formulario

// 2. Cookie con tiempo de expiración (1 hora desde ahora)
// time() devuelve el timestamp actual en segundos. 3600 segundos = 1 hora.
// setcookie("preferencia_color", "azul", time() + 3600); // Comentado

// 3. Cookie con expiración, path y domain
// path = '/': La cookie estará disponible en todo el dominio.
// domain: Si se especifica, la cookie solo está disponible para ese dominio y sus subdominios.
//         Por defecto es el dominio actual.
// setcookie("idioma", "es-ES", time() + (86400 * 30), "/"); // 86400 seg = 1 día. Expira en 30 días.

// Procesar el formulario si se ha enviado para establecer/actualizar cookies
$mensajeCookie = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['nombre_usuario']) && !empty(trim($_POST['nombre_usuario']))) {
        $nombreUsuario = trim($_POST['nombre_usuario']);
        // Establecer cookie por 1 día
        setcookie("nombre_usuario", $nombreUsuario, time() + (86400 * 1), "/");
        $mensajeCookie .= "Cookie 'nombre_usuario' establecida/actualizada a '{$nombreUsuario}'.<br>";
    }

    if (isset($_POST['color_fondo']) && !empty($_POST['color_fondo'])) {
        $colorFondo = $_POST['color_fondo'];
        // Establecer cookie por 30 días
        setcookie("color_fondo_pref", $colorFondo, time() + (86400 * 30), "/");
        $mensajeCookie .= "Cookie 'color_fondo_pref' establecida/actualizada a '{$colorFondo}'.<br>";
    }

    if (isset($_POST['eliminar_todas'])) {
        // Para eliminar una cookie, se establece con un tiempo de expiración en el pasado.
        if (isset($_COOKIE['nombre_usuario'])) {
            setcookie("nombre_usuario", "", time() - 3600, "/");
            $mensajeCookie .= "Cookie 'nombre_usuario' eliminada.<br>";
        }
        if (isset($_COOKIE['color_fondo_pref'])) {
            setcookie("color_fondo_pref", "", time() - 3600, "/");
            $mensajeCookie .= "Cookie 'color_fondo_pref' eliminada.<br>";
        }
        // Importante: Redirigir después de modificar cookies para que los cambios se reflejen en $_COOKIE
        header("Location: cookies_ejemplo.php?cookies_modificadas=1");
        exit;
    }

    if (!empty($mensajeCookie)) {
         // Redirigir para que los cambios en las cookies se reflejen en la superglobal $_COOKIE en la misma carga de página
        header("Location: cookies_ejemplo.php?cookies_modificadas=1");
        exit;
    }
}

// Aplicar el color de fondo si la cookie existe
$estiloBody = "";
if (isset($_COOKIE['color_fondo_pref'])) {
    $colorGuardado = htmlspecialchars($_COOKIE['color_fondo_pref'], ENT_QUOTES, 'UTF-8');
    // Validar que sea un color simple para evitar XSS si se usa directamente en style.
    // Una mejor validación sería una lista de colores permitidos o un regex.
    if (preg_match('/^[a-zA-Z0-9#]+$/', $colorGuardado)) {
        $estiloBody = "style='background-color: {$colorGuardado};'";
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Manejo de Cookies en PHP</title>
    <style>
        body { font-family: sans-serif; padding: 20px; line-height: 1.6; transition: background-color 0.5s; }
        .container { max-width: 700px; margin: auto; background: rgba(255,255,255,0.9); padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        pre { background-color: #eee; padding: 10px; border: 1px solid #ccc; white-space: pre-wrap; word-wrap: break-word; }
        .cookie-info { padding: 10px; background-color: #e7f3fe; border-left: 6px solid #2196F3; margin-bottom:15px;}
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; }
        .form-group input[type="text"], .form-group select { width: calc(100% - 22px); padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        .form-group button { padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .form-group button:hover { background-color: #0056b3; }
        .form-group button.delete { background-color: #dc3545; }
        .form-group button.delete:hover { background-color: #c82333; }
        .message { padding: 10px; background-color: #d4edda; border-color: #c3e6cb; color: #155724; margin-bottom: 15px; border-radius: 4px;}
    </style>
</head>
<body <?php echo $estiloBody; ?>>
<div class="container">

    <h1>Manejo de Cookies con PHP</h1>

    <?php
    if (isset($_GET['cookies_modificadas'])) {
        echo "<p class='message'>Las preferencias de cookies han sido actualizadas. Puede que necesites recargar la página una vez más para ver todos los cambios si acabas de eliminar cookies.</p>";
    }
    ?>

    <form method="POST" action="cookies_ejemplo.php" class="form-group">
        <div>
            <label for="nombre_usuario">Tu Nombre (Guardar en Cookie 'nombre_usuario'):</label>
            <input type="text" id="nombre_usuario" name="nombre_usuario" value="<?php echo isset($_COOKIE['nombre_usuario']) ? htmlspecialchars($_COOKIE['nombre_usuario'], ENT_QUOTES, 'UTF-8') : ''; ?>">
        </div>
        <br>
        <div>
            <label for="color_fondo">Color de Fondo Preferido (Cookie 'color_fondo_pref'):</label>
            <select id="color_fondo" name="color_fondo">
                <option value="">-- Selecciona un color --</option>
                <option value="lightblue" <?php echo (isset($_COOKIE['color_fondo_pref']) && $_COOKIE['color_fondo_pref'] == 'lightblue') ? 'selected' : ''; ?>>Azul Claro</option>
                <option value="lightgreen" <?php echo (isset($_COOKIE['color_fondo_pref']) && $_COOKIE['color_fondo_pref'] == 'lightgreen') ? 'selected' : ''; ?>>Verde Claro</option>
                <option value="lightyellow" <?php echo (isset($_COOKIE['color_fondo_pref']) && $_COOKIE['color_fondo_pref'] == 'lightyellow') ? 'selected' : ''; ?>>Amarillo Claro</option>
                <option value="#f0f0f0" <?php echo (isset($_COOKIE['color_fondo_pref']) && $_COOKIE['color_fondo_pref'] == '#f0f0f0') ? 'selected' : ''; ?>>Gris Claro (default)</option>
            </select>
        </div>
        <br>
        <button type="submit">Guardar Preferencias en Cookies</button>
    </form>

    <form method="POST" action="cookies_ejemplo.php" class="form-group" style="margin-top: 20px;">
        <button type="submit" name="eliminar_todas" class="delete">Eliminar Todas las Cookies de Preferencia</button>
    </form>

    <hr>

    <h2>Leer Cookies Almacenadas</h2>
    <div class="cookie-info">
    <?php
    // ========= LEER UNA COOKIE =========
    // Las cookies enviadas por el navegador al servidor están disponibles en el array superglobal $_COOKIE.
    // $_COOKIE es poblado al inicio del script. Los cambios hechos con setcookie()
    // no se reflejarán en $_COOKIE hasta la próxima carga de página.

    if (isset($_COOKIE['nombre_usuario'])) {
        // Es MUY IMPORTANTE sanitizar el valor de la cookie antes de mostrarlo para prevenir XSS.
        $nombreUsuarioCookie = htmlspecialchars($_COOKIE['nombre_usuario'], ENT_QUOTES, 'UTF-8');
        echo "<p>Bienvenido de nuevo, <strong_class='data-value'>{$nombreUsuarioCookie}</strong>! (Leído de la cookie 'nombre_usuario')</p>";
    } else {
        echo "<p>La cookie 'nombre_usuario' no está establecida.</p>";
    }

    if (isset($_COOKIE['color_fondo_pref'])) {
        $colorFondoCookie = htmlspecialchars($_COOKIE['color_fondo_pref'], ENT_QUOTES, 'UTF-8');
        echo "<p>Tu color de fondo preferido es: <strong style='color:{$colorFondoCookie}; background-color:#fff; padding:2px 5px;'>{$colorFondoCookie}</strong> (Leído de la cookie 'color_fondo_pref')</p>";
    } else {
        echo "<p>La cookie 'color_fondo_pref' no está establecida. El fondo será el predeterminado.</p>";
    }

    // Mostrar todas las cookies (para depuración)
    echo "<h3>Contenido completo de \$_COOKIE:</h3>";
    if (empty($_COOKIE)) {
        echo "<p>No hay cookies disponibles en esta solicitud o han sido eliminadas y la página no se ha recargado lo suficiente.</p>";
    } else {
        echo "<pre>";
        print_r($_COOKIE); // Sanitizar si se va a mostrar cada valor individualmente en un contexto no <pre>
        echo "</pre>";
    }
    ?>
    </div>

    <hr>
    <h2>Consideraciones sobre Cookies</h2>
    <ul>
        <li><strong>Limitaciones de Tamaño y Número:</strong> Los navegadores imponen límites en el tamaño de cada cookie (usualmente ~4KB) y el número total de cookies por dominio.</li>
        <li><strong>Seguridad:</strong>
            <ul>
                <li>No almacenes información sensible directamente en cookies (ej. contraseñas, IDs de sesión si no son seguros).</li>
                <li>Usa el flag <code>HttpOnly</code> para prevenir el acceso a la cookie vía JavaScript (ayuda contra XSS). <code>setcookie("nombre", "valor", ["httponly" => true]);</code> (PHP 7.3+) o el séptimo parámetro.</li>
                <li>Usa el flag <code>Secure</code> para asegurar que la cookie solo se envíe sobre conexiones HTTPS. <code>setcookie("nombre", "valor", ["secure" => true, "httponly" => true]);</code> o los parámetros sexto y séptimo.</li>
                <li>Considera el flag <code>Samesite</code> (Lax, Strict, None) para controlar cuándo se envía la cookie en solicitudes cross-site (ayuda contra CSRF). <code>setcookie("nombre", "valor", ["samesite" => "Lax"]);</code></li>
            </ul>
        </li>
        <li><strong>Privacidad del Usuario (GDPR, etc.):</strong> Informa a los usuarios sobre el uso de cookies y obtén su consentimiento si es necesario, especialmente para cookies de seguimiento o no esenciales.</li>
        <li><strong>Alternativas:</strong> Para almacenar datos más grandes o más sensibles en el servidor, usa Sesiones. Para almacenamiento del lado del cliente más moderno y con mayor capacidad, considera Web Storage (localStorage, sessionStorage), pero estos son accesibles solo por JavaScript, no enviados automáticamente al servidor.</li>
    </ul>
    <p><em>Recuerda que después de usar <code>setcookie()</code> para establecer o eliminar una cookie, el array <code>$_COOKIE</code> no se actualizará hasta la próxima solicitud HTTP (es decir, la próxima vez que la página se cargue). Por eso a veces es necesario redirigir.</em></p>

</div>
</body>
</html>
