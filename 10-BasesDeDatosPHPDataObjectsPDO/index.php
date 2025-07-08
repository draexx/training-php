<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Índice - Ejemplos de PDO en PHP</title>
    <style>
        body { font-family: sans-serif; margin: 0; padding: 0; background-color: #f4f7f6; color: #333; }
        .header { background-color: #007bff; color: white; padding: 20px; text-align: center; }
        .header h1 { margin: 0; }
        .container { max-width: 900px; margin: 20px auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #0056b3; border-bottom: 2px solid #007bff; padding-bottom: 5px; }
        ul { list-style-type: none; padding: 0; }
        ul li { margin-bottom: 10px; }
        ul li a {
            display: block;
            padding: 12px 15px;
            background-color: #e9ecef;
            color: #007bff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
            font-weight: 500;
        }
        ul li a:hover { background-color: #007bff; color: white; }
        .db-setup {
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            color: #856404;
        }
        .db-setup code { background-color: #e9ecef; padding: 2px 4px; border-radius: 3px; }
        .footer { text-align: center; margin-top: 30px; padding: 15px; font-size: 0.9em; color: #777; }
    </style>
</head>
<body>

    <header class="header">
        <h1>Ejemplos de PHP Data Objects (PDO)</h1>
    </header>

    <div class="container">
        <div class="db-setup">
            <p><strong>¡Importante antes de empezar!</strong></p>
            <p>Estos ejemplos asumen que tienes un servidor de base de datos MySQL/MariaDB en funcionamiento.</p>
            <p>Deberás crear una base de datos y un usuario, o usar uno existente. Los detalles de conexión se configuran en <code>config_db.php</code>.</p>
            <p>Por defecto, el script de conexión intentará usar:</p>
            <ul>
                <li>Host: <code>localhost</code></li>
                <li>Base de datos: <code>mi_tienda_pdo</code> (el script <code>conexion_pdo.php</code> intentará crear las tablas <code>productos</code> y <code>categorias</code> si no existen dentro de esta base de datos)</li>
                <li>Usuario: <code>root</code> (o el que configures)</li>
                <li>Contraseña: <code>''</code> (vacía, o la que configures)</li>
            </ul>
            <p>Asegúrate de que el usuario de la base de datos tenga los permisos necesarios (CREATE, INSERT, SELECT, UPDATE, DELETE) sobre la base de datos <code>mi_tienda_pdo</code>.</p>
            <p>Puedes crear la base de datos con el siguiente comando SQL (ej. en phpMyAdmin o consola MySQL):<br>
            <code>CREATE DATABASE mi_tienda_pdo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;</code></p>
        </div>

        <h2>Temas Cubiertos:</h2>
        <ul>
            <li><a href="config_db.php" target="_blank">Ver Archivo de Configuración (config_db.php)</a> <small>(No produce salida visible, solo define constantes)</small></li>
            <li><a href="conexion_pdo.php">1. Conexión a la Base de Datos con PDO</a></li>
            <li><a href="consultas_select_pdo.php">2. Consultas SELECT (Obtención de Datos)</a></li>
            <li><a href="consultas_insert_update_delete_pdo.php">3. Consultas INSERT, UPDATE, DELETE (Modificación de Datos)</a></li>
            <li><a href="transacciones_pdo.php">4. Transacciones en PDO (Commit y Rollback)</a></li>
            <li><a href="manejo_errores_pdo.php">5. Manejo de Errores en PDO</a></li>
        </ul>

        <p>Cada script está diseñado para ser auto-explicativo y demostrar un aspecto específico del uso de PDO.</p>
    </div>

    <footer class="footer">
        <p>Explora cada enlace para aprender más sobre PHP Data Objects.</p>
    </footer>

</body>
</html>
