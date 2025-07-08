<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Índice - Manejo de Errores y Excepciones en PHP</title>
    <style>
        body { font-family: sans-serif; margin: 0; padding: 0; background-color: #f4f7f6; color: #333; }
        .header { background-color: #dc3545; color: white; padding: 20px; text-align: center; }
        .header h1 { margin: 0; }
        .container { max-width: 900px; margin: 20px auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #c82333; border-bottom: 2px solid #dc3545; padding-bottom: 5px; }
        ul { list-style-type: none; padding: 0; }
        ul li { margin-bottom: 10px; }
        ul li a {
            display: block;
            padding: 12px 15px;
            background-color: #f8d7da;
            color: #721c24;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
            font-weight: 500;
            border-left: 5px solid #dc3545;
        }
        ul li a:hover { background-color: #dc3545; color: white; }
        .config-info {
            background-color: #e2e3e5; /* Un gris claro, menos alarmante que amarillo */
            border: 1px solid #d6d8db;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            color: #383d41;
        }
        .config-info code { background-color: #d1d3e2; padding: 2px 4px; border-radius: 3px; }
        .footer { text-align: center; margin-top: 30px; padding: 15px; font-size: 0.9em; color: #777; }
    </style>
</head>
<body>

    <header class="header">
        <h1>Manejo de Errores y Excepciones en PHP</h1>
    </header>

    <div class="container">
        <div class="config-info">
            <p><strong>Configuración de Errores:</strong></p>
            <p>Para ver los errores y notificaciones directamente en la página durante el desarrollo (como en estos ejemplos), asegúrate de que las siguientes directivas de PHP estén configuradas adecuadamente (generalmente en <code>php.ini</code> o usando <code>ini_set()</code>):</p>
            <ul>
                <li><code>error_reporting = E_ALL</code> (para reportar todos los niveles de error)</li>
                <li><code>display_errors = On</code> (para mostrar los errores en la salida HTML)</li>
            </ul>
            <p><strong>En un entorno de producción, <code>display_errors</code> DEBE estar en <code>Off</code></strong>, y los errores deben registrarse en un archivo de log (<code>log_errors = On</code>).</p>
        </div>

        <h2>Temas Cubiertos:</h2>
        <ul>
            <li><a href="errores_basicos.php">1. Errores Básicos y Niveles de Error (E_NOTICE, E_WARNING, E_PARSE, E_ERROR)</a></li>
            <li><a href="set_error_handler.php">2. Manejador de Errores Personalizado (set_error_handler)</a></li>
            <li><a href="excepciones_try_catch.php">3. Excepciones (try, catch, finally, throw) y Excepciones Personalizadas</a></li>
            <li><a href="set_exception_handler.php">4. Manejador de Excepciones Global (set_exception_handler)</a></li>
        </ul>

        <p>El manejo efectivo de errores y excepciones es crucial para desarrollar aplicaciones PHP robustas, seguras y fáciles de depurar.</p>
    </div>

    <footer class="footer">
        <p>Explora cada enlace para aprender a gestionar y controlar los errores en tus aplicaciones PHP.</p>
    </footer>

</body>
</html>
