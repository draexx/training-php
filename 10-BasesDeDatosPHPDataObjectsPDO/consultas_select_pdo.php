<?php
// TEMA: BASES DE DATOS CON PHP (PDO) - Consultas SELECT

require_once 'config_db.php'; // Contiene $dsn_mysql, DB_USER, DB_PASS, $opciones_pdo

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>Consultas SELECT con PDO</title>
    <style>
        body { font-family: sans-serif; padding: 20px; line-height: 1.6; }
        .container { max-width: 800px; margin: auto; background: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        h1, h2, h3 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        pre { background-color: #eee; padding: 10px; border: 1px solid #ccc; overflow-x:auto; }
        .message { padding: 10px; margin-bottom:15px; border-radius:4px; }
        .message.error { background-color: #f8d7da; border-color: #f5c6cb; color: #721c24; }
        .message.success { background-color: #d4edda; border-color: #c3e6cb; color: #155724; }
        .message.info { background-color: #e7f3fe; border-left: 6px solid #2196F3;}
        .form-container { margin-bottom: 20px; padding: 15px; border: 1px solid #ccc; background-color: #fff; }
        .form-container label { margin-right: 10px; }
        .form-container input[type='text'], .form-container input[type='number'] { padding: 5px; margin-right: 10px; }
        .form-container button { padding: 5px 10px; }
    </style>
</head>
<body>
<div class='container'>";

echo "<h1>Consultas SELECT con PDO</h1>";

$pdo = null;
try {
    $pdo = new PDO($dsn_mysql, DB_USER, DB_PASS, $opciones_pdo);
    echo "<p class='message success'>Conexión a la BD exitosa para consultas SELECT.</p>";

    // --- Insertar algunos datos de ejemplo si las tablas están vacías ---
    $stmtCheckProductos = $pdo->query("SELECT COUNT(*) FROM productos");
    if ($stmtCheckProductos->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO productos (nombre, descripcion, precio, stock) VALUES
            ('Laptop Pro X', 'Laptop de alto rendimiento para profesionales', 1250.99, 15),
            ('Mouse Óptico Gamer', 'Mouse con RGB y 6 botones programables', 49.50, 120),
            ('Teclado Mecánico Retroiluminado', 'Teclado mecánico con switches azules', 89.90, 75),
            ('Monitor Curvo 27\" Full HD', 'Monitor curvo para una experiencia inmersiva', 299.00, 30),
            ('SSD Externo 1TB', 'Unidad de estado sólido externa USB 3.1', 110.00, 50)");
        echo "<p class='message info'>Datos de ejemplo insertados en la tabla 'productos'.</p>";
    }
    $stmtCheckCategorias = $pdo->query("SELECT COUNT(*) FROM categorias");
    if ($stmtCheckCategorias->fetchColumn() == 0) {
        $pdo->exec("INSERT INTO categorias (nombre, descripcion) VALUES
            ('Electrónica', 'Dispositivos y componentes electrónicos'),
            ('Periféricos', 'Accesorios para computadoras'),
            ('Almacenamiento', 'Dispositivos para guardar datos')");
        echo "<p class='message info'>Datos de ejemplo insertados en la tabla 'categorias'.</p>";
    }


    // ========= 1. query() - Para consultas SIN parámetros de usuario =========
    // Adecuado para SELECTs simples donde no hay entrada del usuario en la consulta.
    echo "<h2>1. Usando <code>PDO::query()</code> para obtener todos los productos</h2>";
    $sqlTodosProductos = "SELECT id, nombre, precio, stock FROM productos ORDER BY nombre ASC";
    $stmt = $pdo->query($sqlTodosProductos); // $stmt es un objeto PDOStatement

    // Verificar si la consulta devolvió resultados
    if ($stmt->rowCount() > 0) {
        echo "<table><thead><tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Stock</th></tr></thead><tbody>";
        // fetchAll() obtiene todas las filas del conjunto de resultados.
        // Por defecto (o si se configuró PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC), devuelve un array de arrays asociativos.
        $productos = $stmt->fetchAll(); // Podría ser PDO::FETCH_ASSOC aquí también
        foreach ($productos as $producto) {
            echo "<tr>
                    <td>" . htmlspecialchars($producto['id']) . "</td>
                    <td>" . htmlspecialchars($producto['nombre']) . "</td>
                    <td>" . htmlspecialchars($producto['precio']) . "</td>
                    <td>" . htmlspecialchars($producto['stock']) . "</td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p class='message info'>No se encontraron productos.</p>";
    }
    echo "<hr/>";


    // ========= 2. prepare() y execute() - Para consultas CON parámetros (SENTENCIAS PREPARADAS) =========
    // ¡ESTA ES LA FORMA RECOMENDADA Y MÁS SEGURA cuando hay entrada del usuario!
    // Ayuda a prevenir la inyección SQL.

    echo "<h2>2. Usando <code>PDO::prepare()</code> y <code>execute()</code> para buscar productos</h2>";

    // Simular entrada del usuario (en una app real, vendría de $_GET, $_POST, etc.)
    $precioMaximoUsuario = isset($_GET['precio_max']) ? (float)$_GET['precio_max'] : 100.00;
    $nombreBusquedaUsuario = isset($_GET['nombre_busqueda']) ? $_GET['nombre_busqueda'] : 'Mouse';

    echo "<div class='form-container'>
            <form method='GET' action='consultas_select_pdo.php'>
                <label for='nombre_busqueda'>Buscar por nombre (contiene):</label>
                <input type='text' id='nombre_busqueda' name='nombre_busqueda' value='" . htmlspecialchars($nombreBusquedaUsuario) . "'>
                <label for='precio_max'>Precio Máximo:</label>
                <input type='number' step='0.01' id='precio_max' name='precio_max' value='" . htmlspecialchars($precioMaximoUsuario) . "'>
                <button type='submit'>Buscar Productos</button>
            </form>
          </div>";

    // --- A. Usando marcadores de posición anónimos (?) ---
    echo "<h3>2.1. Con marcadores de posición anónimos (?)</h3>";
    $sqlProductosFiltrados = "SELECT id, nombre, precio, stock FROM productos WHERE precio < ? AND nombre LIKE ? ORDER BY precio DESC";
    $stmtPreparado = $pdo->prepare($sqlProductosFiltrados);

    // Los valores se pasan a execute() en un array, en el mismo orden que los '?'
    $paramNombreLike = "%" . $nombreBusquedaUsuario . "%"; // Para el LIKE
    $stmtPreparado->execute([$precioMaximoUsuario, $paramNombreLike]);

    if ($stmtPreparado->rowCount() > 0) {
        echo "<h4>Resultados para precio < " . htmlspecialchars($precioMaximoUsuario) . " Y nombre contiene '" . htmlspecialchars($nombreBusquedaUsuario) . "':</h4>";
        echo "<table><thead><tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Stock</th></tr></thead><tbody>";
        while ($fila = $stmtPreparado->fetch()) { // fetch() obtiene una fila a la vez
            echo "<tr>
                    <td>" . htmlspecialchars($fila['id']) . "</td>
                    <td>" . htmlspecialchars($fila['nombre']) . "</td>
                    <td>" . htmlspecialchars($fila['precio']) . "</td>
                    <td>" . htmlspecialchars($fila['stock']) . "</td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p class='message info'>No se encontraron productos con esos criterios (usando marcadores ?).</p>";
    }
    $stmtPreparado->closeCursor(); // Buena práctica cerrar el cursor para liberar recursos, especialmente si se van a hacer más consultas.
    echo "<br/>";


    // --- B. Usando marcadores de posición con nombre (:) ---
    echo "<h3>2.2. Con marcadores de posición con nombre (:)</h3>";
    $sqlProductosPorNombre = "SELECT id, nombre, descripcion, precio FROM productos WHERE nombre LIKE :termino_busqueda";
    $stmtPreparadoNombre = $pdo->prepare($sqlProductosPorNombre);

    // Los valores se pasan a execute() en un array asociativo.
    // El ':' no se incluye en la clave del array.
    $paramTermino = "%" . $nombreBusquedaUsuario . "%";
    $stmtPreparadoNombre->execute(['termino_busqueda' => $paramTermino]);

    if ($stmtPreparadoNombre->rowCount() > 0) {
        echo "<h4>Resultados para nombre LIKE '%" . htmlspecialchars($nombreBusquedaUsuario) . "%':</h4>";
         echo "<table><thead><tr><th>ID</th><th>Nombre</th><th>Descripción</th><th>Precio</th></tr></thead><tbody>";
        $resultadosNombre = $stmtPreparadoNombre->fetchAll(PDO::FETCH_OBJ); // Obtener como objetos
        foreach ($resultadosNombre as $productoObj) {
            echo "<tr>
                    <td>" . htmlspecialchars($productoObj->id) . "</td>
                    <td>" . htmlspecialchars($productoObj->nombre) . "</td>
                    <td>" . htmlspecialchars(substr($productoObj->descripcion, 0, 50)) . "...</td>
                    <td>" . htmlspecialchars($productoObj->precio) . "</td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p class='message info'>No se encontraron productos con el nombre '" . htmlspecialchars($nombreBusquedaUsuario) . "' (usando marcadores :nombre).</p>";
    }
    $stmtPreparadoNombre->closeCursor();
    echo "<hr/>";

    // ========= 3. bindValue() o bindParam() para vincular parámetros =========
    // Ofrecen más control sobre la vinculación, especialmente el tipo de dato.
    echo "<h2>3. Usando <code>bindParam()</code> / <code>bindValue()</code></h2>";
    $idProductoUsuario = 3; // Simular entrada
    $sqlProductoPorId = "SELECT id, nombre, precio, stock FROM productos WHERE id = :id_producto";
    $stmtBind = $pdo->prepare($sqlProductoPorId);

    // bindValue(): Vincula un valor. El valor se evalúa cuando bindValue() es llamado.
    // $stmtBind->bindValue(':id_producto', $idProductoUsuario, PDO::PARAM_INT); // PDO::PARAM_INT especifica el tipo

    // bindParam(): Vincula una variable. El valor de la variable se evalúa cuando execute() es llamado.
    // Útil si el valor de la variable puede cambiar entre la vinculación y la ejecución (ej. en un bucle).
    $stmtBind->bindParam(':id_producto', $idProductoUsuario, PDO::PARAM_INT);
    // $idProductoUsuario = 4; // Si se cambia aquí, bindParam usaría 4, bindValue usaría 3.

    $stmtBind->execute();
    $productoUnico = $stmtBind->fetch(PDO::FETCH_ASSOC); // Obtener una sola fila

    if ($productoUnico) {
        echo "<h3>Producto encontrado con ID {$idProductoUsuario} (usando bindParam/bindValue):</h3>";
        echo "<pre>";
        print_r($productoUnico);
        echo "</pre>";
    } else {
        echo "<p class='message info'>No se encontró producto con ID {$idProductoUsuario}.</p>";
    }
    $stmtBind->closeCursor();
    echo "<hr/>";

    // ========= 4. Contar filas: fetchColumn() =========
    echo "<h2>4. Contar filas con <code>fetchColumn()</code></h2>";
    $sqlContarCategorias = "SELECT COUNT(*) FROM categorias";
    $stmtContar = $pdo->query($sqlContarCategorias);
    $numeroCategorias = $stmtContar->fetchColumn(); // Devuelve el valor de la primera columna de la primera fila
    echo "<p class='message info'>Número total de categorías: <strong>{$numeroCategorias}</strong>.</p>";
    echo "<hr/>";

    // ========= 5. Diferentes Modos de Obtención (Fetch Modes) =========
    echo "<h2>5. Diferentes Modos de Obtención (Fetch Modes)</h2>";
    $sqlUnProducto = "SELECT * FROM productos WHERE id = 1";
    $stmtModos = $pdo->query($sqlUnProducto);

    if ($stmtModos) {
        // PDO::FETCH_ASSOC (array asociativo) - ya lo usamos por defecto
        $productoAssoc = $stmtModos->fetch(PDO::FETCH_ASSOC);
        echo "<strong>PDO::FETCH_ASSOC:</strong><pre>"; print_r($productoAssoc); echo "</pre>";
        $stmtModos->closeCursor(); // Necesario para re-ejecutar o re-usar para otro fetch con el mismo statement
                                   // O mejor, preparar y ejecutar de nuevo. Por simplicidad, re-ejecutamos query.

        // PDO::FETCH_OBJ (objeto anónimo)
        $stmtModos = $pdo->query($sqlUnProducto); // Re-ejecutar
        $productoObj = $stmtModos->fetch(PDO::FETCH_OBJ);
        echo "<strong>PDO::FETCH_OBJ:</strong><pre>"; print_r($productoObj); echo "</pre>";
        if($productoObj) echo "Acceso como objeto: " . htmlspecialchars($productoObj->nombre) . "<br/>";
        $stmtModos->closeCursor();

        // PDO::FETCH_NUM (array numérico)
        $stmtModos = $pdo->query($sqlUnProducto); // Re-ejecutar
        $productoNum = $stmtModos->fetch(PDO::FETCH_NUM);
        echo "<strong>PDO::FETCH_NUM:</strong><pre>"; print_r($productoNum); echo "</pre>";
        if($productoNum) echo "Acceso numérico (ej. segundo campo): " . htmlspecialchars($productoNum[1]) . "<br/>";
        $stmtModos->closeCursor();

        // PDO::FETCH_BOTH (array asociativo y numérico) - por defecto si no se especifica ATTR_DEFAULT_FETCH_MODE
        $stmtModos = $pdo->query($sqlUnProducto); // Re-ejecutar
        $productoBoth = $stmtModos->fetch(PDO::FETCH_BOTH);
        echo "<strong>PDO::FETCH_BOTH:</strong><pre>"; print_r($productoBoth); echo "</pre>";
        $stmtModos->closeCursor();

        // PDO::FETCH_CLASS (mapear a una clase existente)
        echo "<strong>PDO::FETCH_CLASS:</strong><br/>";
        class ProductoDTO { // Data Transfer Object (o cualquier clase)
            public $id;
            public $nombre;
            public $descripcion;
            public $precio;
            public $stock;
            public $fecha_creacion;
            public function mostrarNombrePrecio() {
                return "Producto DTO: " . htmlspecialchars($this->nombre) . " - $" . htmlspecialchars($this->precio);
            }
        }
        $stmtModos = $pdo->query($sqlUnProducto);
        $stmtModos->setFetchMode(PDO::FETCH_CLASS, 'ProductoDTO'); // Configurar el modo para esta consulta
        $productoClase = $stmtModos->fetch();
        if ($productoClase instanceof ProductoDTO) {
            echo $productoClase->mostrarNombrePrecio() . "<br/>";
            echo "<pre>"; print_r($productoClase); echo "</pre>";
        } else {
            echo "No se pudo obtener como ProductoDTO.<br/>";
        }
        $stmtModos->closeCursor();

    } else {
        echo "<p class='message error'>Error al ejecutar consulta para modos de fetch.</p>";
    }


} catch (PDOException $e) {
    echo "<p class='message error'>Error en la consulta SELECT: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
} finally {
    // Cerrar la conexión
    $pdo = null;
}

echo "<hr>";
echo "<p><a href='index.php'>Volver al índice de PDO</a> (si existe)</p>";
echo "<p><a href='conexion_pdo.php'>Ir a Conexión PDO</a> | <a href='consultas_insert_update_delete_pdo.php'>Ir a Consultas INSERT, UPDATE, DELETE</a></p>";

echo "</div></body></html>";
?>
