<?php
// TEMA: BASES DE DATOS CON PHP (PDO) - Conexión

// Incluir el archivo de configuración con los parámetros de la BD
require_once 'config_db.php'; // Usar require_once para asegurar que se carga una sola vez y es esencial

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>Conexión a Base de Datos con PDO</title>
    <style>
        body { font-family: sans-serif; padding: 20px; line-height: 1.6; }
        .container { max-width: 700px; margin: auto; background: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        h1 { color: #333; }
        .message { padding: 15px; margin-bottom: 20px; border-radius: 4px; border: 1px solid transparent; }
        .message.success { background-color: #d4edda; border-color: #c3e6cb; color: #155724; }
        .message.error { background-color: #f8d7da; border-color: #f5c6cb; color: #721c24; }
        pre { background-color: #eee; padding: 10px; border: 1px solid #ccc; }
    </style>
</head>
<body>
<div class='container'>";

echo "<h1>Conexión a Base de Datos con PDO</h1>";

// Variable para almacenar el objeto PDO
$pdo = null; // Inicializar como null

// --- Intentar la conexión ---
// Es crucial usar un bloque try-catch para manejar posibles errores de conexión (PDOException).
try {
    // Crear una nueva instancia de PDO para conectar a MySQL (usando el DSN y opciones de config_db.php)
    // $dsn_mysql está definido en config_db.php
    // DB_USER y DB_PASS están definidos en config_db.php
    // $opciones_pdo está definido en config_db.php
    $pdo = new PDO($dsn_mysql, DB_USER, DB_PASS, $opciones_pdo);

    // Si la conexión es exitosa, $pdo contendrá el objeto de conexión.
    echo "<p class='message success'>¡Conexión a la base de datos MySQL ('" . DB_NAME . "') establecida exitosamente usando PDO!</p>";

    // --- Mostrar información sobre la conexión (opcional, para depuración) ---
    echo "<h3>Detalles de la Conexión (Cliente PDO):</h3>";
    // getAttribute() puede usarse para obtener varios atributos de la conexión/driver.
    echo "<ul>";
    echo "<li><strong>Versión del Cliente PDO Driver:</strong> " . $pdo->getAttribute(PDO::ATTR_CLIENT_VERSION) . "</li>";
    echo "<li><strong>Versión del Servidor de BD:</strong> " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . "</li>";
    echo "<li><strong>Información del Servidor de BD:</strong> " . $pdo->getAttribute(PDO::ATTR_SERVER_INFO) . "</li>";
    echo "<li><strong>Estado de la Conexión:</strong> " . $pdo->getAttribute(PDO::ATTR_CONNECTION_STATUS) . "</li>";
    echo "<li><strong>Driver Utilizado:</strong> " . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . "</li>";
    echo "</ul>";


    // --- Ejemplo: Crear una tabla si no existe (solo para demostración inicial) ---
    // En una aplicación real, la estructura de la BD (schema) se manejaría con migraciones o scripts SQL separados.
    $sqlCrearTablaProductos = "
    CREATE TABLE IF NOT EXISTS productos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(255) NOT NULL,
        descripcion TEXT,
        precio DECIMAL(10, 2) NOT NULL,
        stock INT DEFAULT 0,
        fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=" . DB_CHARSET . ";";

    // Ejecutar la sentencia SQL para crear la tabla
    // exec() es adecuado para sentencias que no devuelven resultados (como CREATE, INSERT, UPDATE, DELETE).
    // Devuelve el número de filas afectadas (o 0 si no hay o no aplica, como en CREATE TABLE).
    $pdo->exec($sqlCrearTablaProductos);
    echo "<p class='message success'>Tabla 'productos' verificada/creada exitosamente.</p>";

    $sqlCrearTablaCategorias = "
    CREATE TABLE IF NOT EXISTS categorias (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL UNIQUE,
        descripcion TEXT
    ) ENGINE=InnoDB DEFAULT CHARSET=" . DB_CHARSET . ";";
    $pdo->exec($sqlCrearTablaCategorias);
    echo "<p class='message success'>Tabla 'categorias' verificada/creada exitosamente.</p>";


} catch (PDOException $e) {
    // Si ocurre un error durante la conexión o la ejecución de una consulta (y ATTR_ERRMODE es ERRMODE_EXCEPTION),
    // se lanzará una PDOException.
    echo "<p class='message error'>Error de conexión a la base de datos: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
    // En un entorno de producción, no mostrarías $e->getMessage() directamente al usuario.
    // En su lugar, registrarías el error en un archivo de log y mostrarías un mensaje genérico.
    // die("Error de conexión. Por favor, inténtelo más tarde."); // Detener el script si la conexión es vital.
}


// --- Cerrar la conexión (opcional en scripts cortos, pero buena práctica) ---
// PHP cierra automáticamente las conexiones PDO cuando el script termina.
// Sin embargo, si necesitas liberar la conexión antes (ej. en scripts largos o bucles),
// puedes hacerlo estableciendo el objeto PDO a null.
// $pdo = null;
// echo "<p>La conexión PDO se cerrará automáticamente al finalizar el script, o puede cerrarse explícitamente asignando null al objeto PDO.</p>";


// --- ¿Qué sigue? ---
// Con el objeto $pdo establecido, ahora puedes:
// 1. Ejecutar consultas (SELECT, INSERT, UPDATE, DELETE) usando:
//    - $pdo->query() para consultas simples (generalmente SELECT sin parámetros de usuario).
//    - $pdo->prepare() y luego $stmt->execute() para sentencias preparadas (MÁS SEGURO, especialmente con datos de usuario).
//    - $pdo->exec() para sentencias que no devuelven conjuntos de resultados (INSERT, UPDATE, DELETE, CREATE).
// 2. Manejar transacciones.
// 3. Obtener resultados (fetch).

echo "<hr>";
echo "<p><a href='index.php'>Volver al índice de PDO</a> (si existe)</p>";
echo "<p><a href='consultas_select_pdo.php'>Ir a Consultas SELECT con PDO</a></p>";

echo "</div></body></html>";

// La variable $pdo (si la conexión fue exitosa) podría ser utilizada por otros scripts si este archivo
// se incluye y no se cierra la conexión explícitamente aquí.
// Por ejemplo, podrías tener un archivo `db_handler.php` que establece $pdo y luego otros scripts lo usan.
?>
