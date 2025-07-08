<?php
// TEMA: SESIONES EN PHP

// Las sesiones son una forma de almacenar información del usuario a través de múltiples páginas
// en un sitio web o aplicación. A diferencia de las cookies, los datos de la sesión
// se almacenan en el servidor. PHP asigna un ID de sesión único al usuario,
// que generalmente se envía al navegador a través de una cookie de sesión.

// PASO 1: Iniciar o reanudar una sesión
// session_start() DEBE ser llamado ANTES de cualquier salida HTML o de texto al navegador.
// Es una buena práctica colocarlo al inicio de cada script que necesite acceso a la sesión.
if (session_status() == PHP_SESSION_NONE) { // Verificar si la sesión ya está iniciada
    session_start();
}

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>Manejo de Sesiones en PHP - Inicio</title>
    <style>
        body { font-family: sans-serif; padding: 20px; line-height: 1.6; }
        .container { max-width: 600px; margin: auto; background: #f4f4f4; padding: 20px; border-radius: 5px; }
        pre { background-color: #eee; padding: 10px; border: 1px solid #ccc; }
        a { margin-right: 10px; text-decoration: none; color: #007bff; }
        a:hover { text-decoration: underline; }
        .info { padding: 10px; background-color: #e7f3fe; border-left: 6px solid #2196F3; margin-bottom:15px;}
    </style>
</head>
<body>
<div class='container'>";

echo "<h1>Página de Inicio de Sesión (Sesiones)</h1>";

// PASO 2: Almacenar datos en la sesión
// Los datos de la sesión se almacenan en el array superglobal $_SESSION.
// Puedes asignar cualquier tipo de dato (strings, números, arrays, objetos).

if (!isset($_SESSION['visitas'])) {
    $_SESSION['visitas'] = 0;
}
$_SESSION['visitas']++;

if (!isset($_SESSION['hora_inicio'])) {
    $_SESSION['hora_inicio'] = date('H:i:s');
}

// Ejemplo: simular un inicio de sesión de usuario
if (isset($_POST['username']) && !empty($_POST['username'])) {
    $_SESSION['usuario_logueado'] = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $_SESSION['rol'] = (strtolower($_POST['username']) === 'admin') ? 'administrador' : 'usuario_estandar';
    echo "<p class='info' style='background-color: #d4edda; border-left-color: #28a745;'>Usuario '{$_SESSION['usuario_logueado']}' ha iniciado sesión.</p>";
}

// PASO 3: Acceder a los datos de la sesión
echo "<div class='info'>";
echo "<h2>Datos de la Sesión Actual:</h2>";
echo "ID de Sesión: " . session_id() . "<br/>"; // Obtener el ID de la sesión actual

if (isset($_SESSION['usuario_logueado'])) {
    echo "Usuario Logueado: " . $_SESSION['usuario_logueado'] . "<br/>";
    echo "Rol: " . $_SESSION['rol'] . "<br/>";
} else {
    echo "Usuario: No ha iniciado sesión.<br/>";
}

echo "Número de visitas a esta página en esta sesión: " . $_SESSION['visitas'] . "<br/>";
echo "Hora de inicio de esta sesión (o primera visita a esta página): " . $_SESSION['hora_inicio'] . "<br/>";
echo "</div>";

// Mostrar todo el contenido de $_SESSION (para depuración)
echo "<h3>Contenido completo de \$_SESSION:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

?>

<h2>Simular Inicio de Sesión:</h2>
<form method="POST" action="sesiones_inicio.php">
    <label for="username">Nombre de Usuario:</label>
    <input type="text" id="username" name="username" required>
    <button type="submit">Iniciar Sesión</button>
</form>
<p><em>Intenta ingresar "admin" (sensible a mayúsculas/minúsculas para el rol) o cualquier otro nombre.</em></p>

<hr>
<p>
    <a href="sesiones_pagina2.php">Ir a Página 2 (para ver persistencia de sesión)</a>
    <a href="sesiones_cerrar.php">Cerrar Sesión</a>
</p>
<p>Recarga esta página para ver cómo aumenta el contador de visitas.</p>

<?php
echo "</div></body></html>";
?>
