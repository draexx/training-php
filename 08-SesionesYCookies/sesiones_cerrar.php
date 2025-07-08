<?php
// TEMA: SESIONES EN PHP - CERRAR SESIÓN

// PASO 1: Iniciar o reanudar la sesión existente.
// Es necesario para poder acceder a las funciones de sesión y al array $_SESSION.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>Cerrar Sesión en PHP</title>
    <style>
        body { font-family: sans-serif; padding: 20px; line-height: 1.6; }
        .container { max-width: 600px; margin: auto; background: #f4f4f4; padding: 20px; border-radius: 5px; }
        .message { padding: 15px; background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; border-radius: 4px; margin-bottom: 20px;}
        a { margin-right: 10px; text-decoration: none; color: #007bff; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class='container'>";

echo "<h1>Cerrar Sesión</h1>";

// PASO 2: Eliminar todas las variables de sesión.
// session_unset() libera todas las variables de sesión actualmente registradas.
$_SESSION = array(); // También se puede hacer asignando un array vacío a $_SESSION.
// session_unset(); // Alternativa a $_SESSION = array();

// PASO 3: Destruir la sesión.
// session_destroy() destruye toda la información asociada con la sesión actual.
// No borra las variables globales asociadas con la sesión, ni borra la cookie de sesión.
// Por eso, es común usar session_unset() o $_SESSION = array() antes.
if (session_destroy()) {
    echo "<div class='message'>Has cerrado la sesión correctamente. Todos los datos de la sesión han sido eliminados del servidor.</div>";
} else {
    echo "<div class='message' style='background-color: #f8d7da; border-color: #f5c6cb; color: #721c24;'>Error al intentar cerrar la sesión.</div>";
}


// PASO 4 (Opcional pero recomendado): Eliminar la cookie de sesión del navegador.
// session_destroy() no elimina la cookie del lado del cliente.
// Para asegurar que la sesión se elimina completamente, es buena práctica
// también eliminar la cookie de sesión.
if (ini_get("session.use_cookies")) { // Verificar si las sesiones usan cookies
    $params = session_get_cookie_params(); // Obtener los parámetros de la cookie de sesión
    setcookie(
        session_name(), // Nombre de la cookie de sesión (usualmente PHPSESSID)
        '',             // Valor vacío
        time() - 42000, // Tiempo de expiración en el pasado para eliminarla
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
    echo "<p><em>Se ha intentado eliminar la cookie de sesión del navegador.</em></p>";
}

echo "<p>Después de cerrar sesión, si intentas acceder a variables de sesión en otras páginas, estas ya no existirán.</p>";

// Comprobación (opcional): Intentar mostrar $_SESSION después de destruirla
// Debería estar vacío o no existir.
echo "<h3>Contenido de \$_SESSION después de cerrar sesión:</h3>";
echo "<pre>";
if (isset($_SESSION)) {
    print_r($_SESSION); // Debería estar vacío
} else {
    echo "El array \$_SESSION ya no está definido.";
}
echo "</pre>";
// Nota: session_id() podría seguir devolviendo el ID anterior hasta que se envíe una nueva cabecera o se cierre el script,
// pero ya no apuntará a datos de sesión válidos en el servidor.
echo "<p>ID de Sesión actual (puede ser el antiguo, pero ya no es válido): " . session_id() . "</p>";


echo "<hr>";
echo "<p><a href='sesiones_inicio.php'>Volver a la Página de Inicio (se iniciará una nueva sesión)</a></p>";
echo "<p><a href='sesiones_pagina2.php'>Ir a Página 2 (para comprobar que la sesión está cerrada)</a></p>";

echo "</div></body></html>";
?>
