<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Índice - Ejemplos de JSON en PHP</title>
    <style>
        body { font-family: sans-serif; margin: 0; padding: 0; background-color: #f4f7f6; color: #333; }
        .header { background-color: #fd7e14; /* Naranja distintivo para JSON */ color: white; padding: 20px; text-align: center; }
        .header h1 { margin: 0; }
        .container { max-width: 900px; margin: 20px auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #d9534f; /* Un rojo anaranjado */ border-bottom: 2px solid #fd7e14; padding-bottom: 5px; }
        ul { list-style-type: none; padding: 0; }
        ul li { margin-bottom: 10px; }
        ul li a {
            display: block;
            padding: 12px 15px;
            background-color: #ffe8cc; /* Naranja muy claro */
            color: #c95e00; /* Naranja oscuro */
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
            font-weight: 500;
            border-left: 5px solid #fd7e14;
        }
        ul li a:hover { background-color: #fd7e14; color: white; }
        .info {
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            color: #856404;
        }
        .footer { text-align: center; margin-top: 30px; padding: 15px; font-size: 0.9em; color: #777; }
    </style>
</head>
<body>

    <header class="header">
        <h1>Trabajando con JSON en PHP</h1>
    </header>

    <div class="container">
        <div class="info">
            <p><strong>JSON (JavaScript Object Notation)</strong> es un formato ligero de intercambio de datos. Es fácil de leer y escribir para los humanos, y fácil de interpretar y generar para las máquinas.</p>
            <p>PHP proporciona funciones nativas para trabajar con JSON de manera eficiente: <code>json_encode()</code> para convertir datos PHP a JSON, y <code>json_decode()</code> para convertir cadenas JSON a datos PHP.</p>
        </div>

        <h2>Temas Cubiertos:</h2>
        <ul>
            <li><a href="json_encode.php">1. Codificar Datos PHP a JSON (<code>json_encode</code>)</a></li>
            <li><a href="json_decode.php">2. Decodificar Cadenas JSON a Datos PHP (<code>json_decode</code>)</a></li>
            <li><a href="ejemplo_api_json.php">3. Ejemplo Práctico: Simulación de Consumo y Exposición de API JSON Simple</a></li>
        </ul>

        <p>Estos ejemplos te mostrarán cómo manipular datos en formato JSON, una habilidad esencial para la comunicación con APIs web y el almacenamiento de datos estructurados.</p>
    </div>

    <footer class="footer">
        <p>Aprende a integrar PHP con el formato de datos estándar de la web.</p>
    </footer>

</body>
</html>
