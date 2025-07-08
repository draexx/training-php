<?php
// TEMA: SESIONES EN PHP - PÁGINA 2

// Iniciar o reanudar la sesión para acceder a los datos almacenados.
// DEBE ser lo primero en el script.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>Manejo de Sesiones en PHP - Página 2</title>
    <style>
        body { font-family: sans-serif; padding: 20px; line-height: 1.6; }
        .container { max-width: 600px; margin: auto; background: #f4f4f4; padding: 20px; border-radius: 5px; }
        pre { background-color: #eee; padding: 10px; border: 1px solid #ccc; }
        a { margin-right: 10px; text-decoration: none; color: #007bff; }
        a:hover { text-decoration: underline; }
        .info { padding: 10px; background-color: #e7f3fe; border-left: 6px solid #2196F3; margin-bottom:15px;}
        .warning { padding: 10px; background-color: #fff3cd; border-left: 6px solid #ffc107;}
    </style>
</head>
<body>
<div class='container'>";

echo "<h1>Página 2 (Sesiones)</h1>";

// Acceder a los datos de la sesión establecidos en sesiones_inicio.php
echo "<div class='info'>";
echo "<h2>Datos de Sesión Recuperados:</h2>";
echo "ID de Sesión: " . session_id() . "<br/>";

if (isset($_SESSION['usuario_logueado'])) {
    echo "Usuario Logueado: " . htmlspecialchars($_SESSION['usuario_logueado'], ENT_QUOTES, 'UTF-8') . "<br/>";
    echo "Rol: " . htmlspecialchars($_SESSION['rol'], ENT_QUOTES, 'UTF-8') . "<br/>";
} else {
    echo "<p class='warning'>Usuario: No ha iniciado sesión. Los datos de usuario no están disponibles.</p>";
}

if (isset($_SESSION['visitas'])) {
    echo "Número de visitas (desde la página de inicio): " . $_SESSION['visitas'] . "<br/>";
} else {
    echo "Contador de visitas no encontrado en la sesión.<br/>";
}

if (isset($_SESSION['hora_inicio'])) {
    echo "Hora de inicio de sesión (desde la página de inicio): " . $_SESSION['hora_inicio'] . "<br/>";
} else {
    echo "Hora de inicio no encontrada en la sesión.<br/>";
}
echo "</div>";


// Modificar un dato de la sesión desde esta página
if (!isset($_SESSION['pagina2_visitada'])) {
    $_SESSION['pagina2_visitada'] = "Sí, a las " . date('H:i:s');
}

// Mostrar todo el contenido de $_SESSION (para depuración)
echo "<h3>Contenido completo de \$_SESSION en Página 2:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// PASO 4: Eliminar datos específicos de la sesión (opcional)
// Se puede usar unset() para eliminar una variable de sesión específica.
// Ejemplo: unset($_SESSION['dato_temporal']);

if (isset($_POST['eliminar_rol'])) {
    if (isset($_SESSION['rol'])) {
        unset($_SESSION['rol']);
        echo "<p class='info' style='background-color: #f8d7da; border-left-color: #dc3545;'>Se ha eliminado el 'rol' de la sesión. Refresca para ver el cambio o ve a la página de inicio.</p>";
        // Es buena práctica refrescar o redirigir después de tales cambios para ver el efecto.
        // header("Location: sesiones_pagina2.php"); // Podría causar bucle si no se maneja bien
        // exit;
    } else {
        echo "<p class='warning'>El 'rol' ya no existe en la sesión.</p>";
    }
}
?>

<form method="POST" action="sesiones_pagina2.php">
    <button type="submit" name="eliminar_rol">Eliminar Rol de la Sesión</button>
</form>

<hr>
<p>
    <a href="sesiones_inicio.php">Volver a Página de Inicio</a>
    <a href="sesiones_cerrar.php">Cerrar Sesión (destruir todos los datos)</a>
</p>

<?php
echo "</div></body></html>";
?>
