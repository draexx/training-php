<?php
// TEMA: BASES DE DATOS CON PHP (PDO) - Consultas INSERT, UPDATE, DELETE

require_once 'config_db.php'; // Contiene $dsn_mysql, DB_USER, DB_PASS, $opciones_pdo

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>Consultas INSERT, UPDATE, DELETE con PDO</title>
    <style>
        body { font-family: sans-serif; padding: 20px; line-height: 1.6; }
        .container { max-width: 800px; margin: auto; background: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        h1, h2, h3 { color: #333; }
        pre { background-color: #eee; padding: 10px; border: 1px solid #ccc; overflow-x:auto; }
        .message { padding: 10px; margin-bottom:15px; border-radius:4px; }
        .message.error { background-color: #f8d7da; border-color: #f5c6cb; color: #721c24; }
        .message.success { background-color: #d4edda; border-color: #c3e6cb; color: #155724; }
        .message.info { background-color: #e7f3fe; border-left: 6px solid #2196F3;}
        .form-container { margin-bottom: 20px; padding: 15px; border: 1px solid #ccc; background-color: #fff; }
        .form-container label { display: block; margin-top: 10px; }
        .form-container input[type='text'], .form-container input[type='number'], .form-container textarea {
            width: calc(100% - 16px); padding: 6px; margin-top: 5px; border:1px solid #ccc; border-radius:3px;
        }
        .form-container button { padding: 8px 15px; margin-top:10px; background-color:#007bff; color:white; border:none; border-radius:3px; cursor:pointer; }
        .form-container button:hover { background-color:#0056b3; }
        .form-container button.update { background-color:#ffc107; color:#212529; }
        .form-container button.update:hover { background-color:#e0a800; }
        .form-container button.delete { background-color:#dc3545; }
        .form-container button.delete:hover { background-color:#c82333; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
<div class='container'>";

echo "<h1>Consultas INSERT, UPDATE, DELETE con PDO</h1>";

$pdo = null;
$mensajeGlobal = "";

try {
    $pdo = new PDO($dsn_mysql, DB_USER, DB_PASS, $opciones_pdo);

    // --- Manejo de Formularios para INSERT, UPDATE, DELETE ---
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // --- INSERTAR PRODUCTO ---
        if (isset($_POST['insertar_producto'])) {
            $nombre = trim($_POST['nombre_prod']);
            $descripcion = trim($_POST['desc_prod']);
            $precio = filter_var($_POST['precio_prod'], FILTER_VALIDATE_FLOAT);
            $stock = filter_var($_POST['stock_prod'], FILTER_VALIDATE_INT);

            if (!empty($nombre) && $precio !== false && $stock !== false) {
                $sqlInsert = "INSERT INTO productos (nombre, descripcion, precio, stock) VALUES (:nombre, :descripcion, :precio, :stock)";
                $stmt = $pdo->prepare($sqlInsert);
                $stmt->bindParam(':nombre', $nombre);
                $stmt->bindParam(':descripcion', $descripcion);
                $stmt->bindParam(':precio', $precio);
                $stmt->bindParam(':stock', $stock);

                if ($stmt->execute()) {
                    $ultimoIdInsertado = $pdo->lastInsertId(); // Obtener el ID del último registro insertado
                    $mensajeGlobal .= "<p class='message success'>Producto '{$nombre}' insertado correctamente con ID: {$ultimoIdInsertado}. Filas afectadas: " . $stmt->rowCount() . "</p>";
                } else {
                    $mensajeGlobal .= "<p class='message error'>Error al insertar producto.</p>";
                }
            } else {
                $mensajeGlobal .= "<p class='message error'>Datos de inserción inválidos. Nombre, precio y stock son requeridos y deben tener el formato correcto.</p>";
            }
        }

        // --- ACTUALIZAR PRODUCTO ---
        if (isset($_POST['actualizar_producto'])) {
            $idProdActualizar = filter_var($_POST['id_prod_actualizar'], FILTER_VALIDATE_INT);
            $nuevoPrecio = filter_var($_POST['nuevo_precio_prod'], FILTER_VALIDATE_FLOAT);
            $nuevoStock = filter_var($_POST['nuevo_stock_prod'], FILTER_VALIDATE_INT);

            if ($idProdActualizar !== false && $nuevoPrecio !== false && $nuevoStock !== false) {
                $sqlUpdate = "UPDATE productos SET precio = :precio, stock = :stock WHERE id = :id";
                $stmt = $pdo->prepare($sqlUpdate);
                $stmt->bindParam(':precio', $nuevoPrecio);
                $stmt->bindParam(':stock', $nuevoStock);
                $stmt->bindParam(':id', $idProdActualizar, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    $filasAfectadas = $stmt->rowCount(); // Número de filas afectadas por UPDATE/DELETE
                    if ($filasAfectadas > 0) {
                        $mensajeGlobal .= "<p class='message success'>Producto con ID {$idProdActualizar} actualizado. Precio: {$nuevoPrecio}, Stock: {$nuevoStock}. Filas afectadas: {$filasAfectadas}.</p>";
                    } else {
                        $mensajeGlobal .= "<p class='message info'>No se actualizó ningún producto (quizás el ID no existe o los datos eran los mismos). Filas afectadas: {$filasAfectadas}.</p>";
                    }
                } else {
                    $mensajeGlobal .= "<p class='message error'>Error al actualizar producto con ID {$idProdActualizar}.</p>";
                }
            } else {
                $mensajeGlobal .= "<p class='message error'>Datos de actualización inválidos. ID, nuevo precio y nuevo stock son requeridos y deben tener el formato correcto.</p>";
            }
        }

        // --- ELIMINAR PRODUCTO ---
        if (isset($_POST['eliminar_producto'])) {
            $idProdEliminar = filter_var($_POST['id_prod_eliminar'], FILTER_VALIDATE_INT);
            if ($idProdEliminar !== false) {
                $sqlDelete = "DELETE FROM productos WHERE id = :id";
                $stmt = $pdo->prepare($sqlDelete);
                $stmt->bindParam(':id', $idProdEliminar, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    $filasAfectadas = $stmt->rowCount();
                     if ($filasAfectadas > 0) {
                        $mensajeGlobal .= "<p class='message success'>Producto con ID {$idProdEliminar} eliminado. Filas afectadas: {$filasAfectadas}.</p>";
                    } else {
                        $mensajeGlobal .= "<p class='message info'>No se eliminó ningún producto (quizás el ID no existe). Filas afectadas: {$filasAfectadas}.</p>";
                    }
                } else {
                    $mensajeGlobal .= "<p class='message error'>Error al eliminar producto con ID {$idProdEliminar}.</p>";
                }
            } else {
                 $mensajeGlobal .= "<p class='message error'>ID de producto para eliminar no válido.</p>";
            }
        }
    } // Fin if ($_SERVER['REQUEST_METHOD'] == 'POST')

    if ($mensajeGlobal) {
        echo $mensajeGlobal; // Mostrar mensajes de operaciones
    }

    // --- Formularios para INSERT, UPDATE, DELETE ---
    echo "<div class='form-container'>";
    echo "<h2>Insertar Nuevo Producto</h2>";
    echo "<form method='POST' action='consultas_insert_update_delete_pdo.php'>";
    echo "<label for='nombre_prod'>Nombre:</label><input type='text' id='nombre_prod' name='nombre_prod' required>";
    echo "<label for='desc_prod'>Descripción:</label><textarea id='desc_prod' name='desc_prod'></textarea>";
    echo "<label for='precio_prod'>Precio:</label><input type='number' step='0.01' id='precio_prod' name='precio_prod' required>";
    echo "<label for='stock_prod'>Stock:</label><input type='number' id='stock_prod' name='stock_prod' required>";
    echo "<button type='submit' name='insertar_producto'>Insertar Producto</button>";
    echo "</form>";
    echo "</div>";

    echo "<div class='form-container'>";
    echo "<h2>Actualizar Producto</h2>";
    echo "<form method='POST' action='consultas_insert_update_delete_pdo.php'>";
    echo "<label for='id_prod_actualizar'>ID del Producto a Actualizar:</label><input type='number' id='id_prod_actualizar' name='id_prod_actualizar' required>";
    echo "<label for='nuevo_precio_prod'>Nuevo Precio:</label><input type='number' step='0.01' id='nuevo_precio_prod' name='nuevo_precio_prod' required>";
    echo "<label for='nuevo_stock_prod'>Nuevo Stock:</label><input type='number' id='nuevo_stock_prod' name='nuevo_stock_prod' required>";
    echo "<button type='submit' name='actualizar_producto' class='update'>Actualizar Producto</button>";
    echo "</form>";
    echo "</div>";

    echo "<div class='form-container'>";
    echo "<h2>Eliminar Producto</h2>";
    echo "<form method='POST' action='consultas_insert_update_delete_pdo.php'>";
    echo "<label for='id_prod_eliminar'>ID del Producto a Eliminar:</label><input type='number' id='id_prod_eliminar' name='id_prod_eliminar' required>";
    echo "<button type='submit' name='eliminar_producto' class='delete' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este producto?\");'>Eliminar Producto</button>";
    echo "</form>";
    echo "</div><hr/>";


    // --- Mostrar lista de productos actual ---
    echo "<h2>Lista Actual de Productos</h2>";
    $stmtLista = $pdo->query("SELECT id, nombre, descripcion, precio, stock FROM productos ORDER BY id DESC LIMIT 10");
    if ($stmtLista->rowCount() > 0) {
        echo "<table><thead><tr><th>ID</th><th>Nombre</th><th>Descripción</th><th>Precio</th><th>Stock</th></tr></thead><tbody>";
        while ($fila = $stmtLista->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>" . htmlspecialchars($fila['id']) . "</td>
                    <td>" . htmlspecialchars($fila['nombre']) . "</td>
                    <td>" . htmlspecialchars(substr($fila['descripcion'],0,70)) . "...</td>
                    <td>" . htmlspecialchars($fila['precio']) . "</td>
                    <td>" . htmlspecialchars($fila['stock']) . "</td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p class='message info'>No hay productos para mostrar.</p>";
    }


    // ========= Uso de PDO::exec() para INSERT, UPDATE, DELETE (menos común para esto) =========
    // exec() es útil para sentencias que no devuelven resultados y no necesitan vinculación de parámetros.
    // Devuelve el número de filas afectadas. NO USAR CON DATOS DE USUARIO DIRECTAMENTE EN EL SQL (riesgo de inyección).
    echo "<hr><h2>Uso de <code>PDO::exec()</code> (ejemplo con INSERT)</h2>";
    // ¡CUIDADO! Este ejemplo es solo para demostrar exec(). No es seguro si los datos vinieran del usuario.
    $nombreCategoriaNueva = "Novedades " . date('His');
    $descCategoriaNueva = "Categoría de productos nuevos y emocionantes.";
    // Para hacer esto seguro con exec, los valores tendrían que ser escapados manualmente (NO RECOMENDADO)
    // $sqlInsertCategoria = "INSERT INTO categorias (nombre, descripcion) VALUES (" . $pdo->quote($nombreCategoriaNueva) . ", " . $pdo->quote($descCategoriaNueva) . ")";
    // $filasAfectadasExec = $pdo->exec($sqlInsertCategoria);
    // echo "<p class='message info'>Usando exec() para insertar categoría: {$filasAfectadasExec} fila(s) afectada(s). (Ejemplo, no usar con datos de usuario sin sanitizar)</p>";

    // La forma correcta incluso si se quiere usar exec (aunque prepare es mejor) sería con quote:
    $sqlInsertCategoriaSeguro = "INSERT INTO categorias (nombre, descripcion) VALUES (".$pdo->quote($nombreCategoriaNueva).", ".$pdo->quote($descCategoriaNueva).")";
    try {
        $filasAfectadasExec = $pdo->exec($sqlInsertCategoriaSeguro);
        if ($filasAfectadasExec !== false) {
             $mensajeGlobal .= "<p class='message success'>Categoría '{$nombreCategoriaNueva}' insertada con exec(). Filas afectadas: {$filasAfectadasExec}.</p>";
        } else {
            $errorInfo = $pdo->errorInfo();
            $mensajeGlobal .= "<p class='message error'>Error al insertar categoría con exec(): " . $errorInfo[2] . "</p>";
        }
    } catch (PDOException $e) {
        // Esto podría pasar si, por ejemplo, el nombre de categoría ya existe y hay una restricción UNIQUE
        $mensajeGlobal .= "<p class='message error'>Excepción al insertar categoría con exec(): " . $e->getMessage() . "</p>";
    }
    echo $mensajeGlobal;


} catch (PDOException $e) {
    echo "<p class='message error'>Error de base de datos: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
} finally {
    $pdo = null;
}

echo "<hr>";
echo "<p><a href='index.php'>Volver al índice de PDO</a> (si existe)</p>";
echo "<p><a href='consultas_select_pdo.php'>Ir a Consultas SELECT</a> | <a href='transacciones_pdo.php'>Ir a Transacciones PDO</a></p>";

echo "</div></body></html>";
?>
