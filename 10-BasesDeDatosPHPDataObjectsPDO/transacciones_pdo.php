<?php
// TEMA: BASES DE DATOS CON PHP (PDO) - Transacciones

require_once 'config_db.php';

echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <title>Transacciones con PDO</title>
    <style>
        body { font-family: sans-serif; padding: 20px; line-height: 1.6; }
        .container { max-width: 800px; margin: auto; background: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        h1, h2 { color: #333; }
        pre { background-color: #eee; padding: 10px; border: 1px solid #ccc; overflow-x:auto; }
        .message { padding: 10px; margin-bottom:15px; border-radius:4px; }
        .message.error { background-color: #f8d7da; border-color: #f5c6cb; color: #721c24; }
        .message.success { background-color: #d4edda; border-color: #c3e6cb; color: #155724; }
        .message.info { background-color: #e7f3fe; border-left: 6px solid #2196F3;}
        .action-buttons button { padding: 8px 15px; margin: 5px; background-color:#007bff; color:white; border:none; border-radius:3px; cursor:pointer; }
        .action-buttons button:hover { background-color:#0056b3; }
        .action-buttons button.error { background-color:#dc3545; }
        .action-buttons button.error:hover { background-color:#c82333; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
<div class='container'>";

echo "<h1>Transacciones con PDO</h1>";
echo "<p class='message info'>Las transacciones permiten ejecutar un grupo de consultas SQL como una única unidad de trabajo. Si todas las consultas tienen éxito, se confirman (<code>commit</code>). Si alguna falla, se pueden revertir todos los cambios (<code>rollback</code>), asegurando la integridad de los datos (Atomicidad).</p>";

$pdo = null;
$mensajeTransaccion = "";

// --- Función para mostrar productos (para ver el estado antes y después) ---
function mostrarProductos($pdo, $titulo = "Estado Actual de Productos") {
    echo "<h3>{$titulo} (primeros 5)</h3>";
    $stmt = $pdo->query("SELECT id, nombre, precio, stock FROM productos ORDER BY id DESC LIMIT 5");
    if ($stmt->rowCount() > 0) {
        echo "<table><thead><tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Stock</th></tr></thead><tbody>";
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>
                    <td>" . htmlspecialchars($fila['id']) . "</td>
                    <td>" . htmlspecialchars($fila['nombre']) . "</td>
                    <td>" . htmlspecialchars($fila['precio']) . "</td>
                    <td>" . htmlspecialchars($fila['stock']) . "</td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No hay productos para mostrar.</p>";
    }
}


try {
    $pdo = new PDO($dsn_mysql, DB_USER, DB_PASS, $opciones_pdo);

    // Mostrar estado inicial de productos de ejemplo
    // Asegurémonos de que hay algunos productos para trabajar
    $stmtCheck = $pdo->query("SELECT COUNT(*) FROM productos WHERE nombre LIKE 'Producto Transacción%'");
    if ($stmtCheck->fetchColumn() < 2) {
        $pdo->exec("DELETE FROM productos WHERE nombre LIKE 'Producto Transacción%'"); // Limpiar anteriores
        $pdo->exec("INSERT INTO productos (nombre, descripcion, precio, stock) VALUES ('Producto Transacción A', 'Para prueba de rollback', 10.00, 5)");
        $pdo->exec("INSERT INTO productos (nombre, descripcion, precio, stock) VALUES ('Producto Transacción B', 'Para prueba de commit', 20.00, 10)");
    }

    mostrarProductos($pdo, "Estado Inicial de Productos de Prueba");
    echo "<hr/>";


    // --- EJEMPLO DE TRANSACCIÓN EXITOSA (COMMIT) ---
    if (isset($_GET['accion']) && $_GET['accion'] == 'commit_test') {
        echo "<h2>Ejecutando Transacción Exitosa (COMMIT)</h2>";
        try {
            $pdo->beginTransaction(); // 1. Iniciar la transacción

            // Primera operación: insertar un nuevo producto
            $sql1 = "INSERT INTO productos (nombre, descripcion, precio, stock) VALUES (:nombre, :descripcion, :precio, :stock)";
            $stmt1 = $pdo->prepare($sql1);
            $stmt1->execute([
                ':nombre' => 'Producto Commit Test',
                ':descripcion' => 'Este producto se insertará correctamente.',
                ':precio' => 30.00,
                ':stock' => 25
            ]);
            $idNuevoProducto = $pdo->lastInsertId();
            $mensajeTransaccion .= "<p class='message success'>Operación 1 (INSERT) exitosa. Nuevo ID: {$idNuevoProducto}.</p>";

            // Segunda operación: actualizar el stock de otro producto
            $sql2 = "UPDATE productos SET stock = stock - 2 WHERE nombre = 'Producto Transacción B'"; // Asumimos que existe
            $stmt2 = $pdo->prepare($sql2);
            $stmt2->execute();
            $filasAfectadasUpdate = $stmt2->rowCount();
            $mensajeTransaccion .= "<p class='message success'>Operación 2 (UPDATE) exitosa. Filas afectadas: {$filasAfectadasUpdate}.</p>";

            // Si todo fue bien, confirmar la transacción
            $pdo->commit(); // 3. Confirmar los cambios
            $mensajeTransaccion .= "<p class='message success'><strong>TRANSACCIÓN COMPLETADA (COMMIT).</strong> Todos los cambios han sido guardados.</p>";

        } catch (PDOException $e) {
            // Si ocurre algún error en CUALQUIERA de las operaciones dentro del try...
            if ($pdo->inTransaction()) { // Verificar si la transacción sigue activa
                $pdo->rollBack(); // 3. Revertir los cambios
            }
            $mensajeTransaccion .= "<p class='message error'><strong>ERROR EN TRANSACCIÓN (COMMIT TEST):</strong> " . htmlspecialchars($e->getMessage()) . ". <strong>Se hizo ROLLBACK.</strong></p>";
        }
        echo $mensajeTransaccion;
        mostrarProductos($pdo, "Estado de Productos DESPUÉS de Commit Test");
        echo "<hr/>";
        $mensajeTransaccion = ""; // Limpiar para la siguiente
    }


    // --- EJEMPLO DE TRANSACCIÓN FALLIDA (ROLLBACK) ---
    if (isset($_GET['accion']) && $_GET['accion'] == 'rollback_test') {
        echo "<h2>Ejecutando Transacción Fallida (ROLLBACK)</h2>";
        try {
            $pdo->beginTransaction(); // 1. Iniciar la transacción

            // Primera operación: insertar un producto (esta podría tener éxito)
            $sqlR1 = "INSERT INTO productos (nombre, descripcion, precio, stock) VALUES (:nombre, :descripcion, :precio, :stock)";
            $stmtR1 = $pdo->prepare($sqlR1);
            $stmtR1->execute([
                ':nombre' => 'Producto Rollback Temp',
                ':descripcion' => 'Este producto NO debería persistir.',
                ':precio' => 5.00,
                ':stock' => 50
            ]);
            $idProductoTemp = $pdo->lastInsertId();
            $mensajeTransaccion .= "<p class='message success'>Operación 1 (INSERT en Rollback Test) ejecutada. ID temporal: {$idProductoTemp}.</p>";

            // Segunda operación: intentar una acción que falle
            // Por ejemplo, insertar un producto con un ID que ya existe (si ID fuera UNIQUE y no AUTO_INCREMENT),
            // o violar una restricción NOT NULL, o una consulta SQL mal formada.
            // Aquí simularemos una consulta SQL incorrecta.
            $sqlR2_fallida = "UPDATE productos SET precio = 'esto_no_es_numero' WHERE nombre = 'Producto Transacción A'"; // Error de tipo de dato
            // O una tabla que no existe: $sqlR2_fallida = "INSERT INTO tabla_inexistente (col) VALUES (1)";

            $stmtR2 = $pdo->prepare($sqlR2_fallida); // La preparación podría no fallar, pero sí la ejecución
            $stmtR2->execute(); // Esto debería lanzar una PDOException

            // Si llegamos aquí, algo no fue como se esperaba (la excepción no se lanzó)
            $mensajeTransaccion .= "<p class='message error'>Operación 2 (que debía fallar) parece que no lanzó excepción. Esto es inesperado.</p>";

            // Si todo fuera bien (lo cual no debería en este caso), se haría commit.
            $pdo->commit();
            $mensajeTransaccion .= "<p class='message error'><strong>ERROR DE LÓGICA: Se hizo COMMIT en una transacción que debía fallar.</strong></p>";

        } catch (PDOException $e) {
            // Error capturado, la transacción debe ser revertida.
            $mensajeTransaccion .= "<p class='message error'><strong>ERROR EN TRANSACCIÓN (ROLLBACK TEST) CAPTURADO:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
                $mensajeTransaccion .= "<p class='message success'><strong>TRANSACCIÓN REVERTIDA (ROLLBACK) exitosamente.</strong> Ningún cambio de esta transacción fue guardado.</p>";
            } else {
                $mensajeTransaccion .= "<p class='message error'>La transacción ya no estaba activa al intentar rollback (quizás ya se revirtió o hubo un error grave).</p>";
            }
        }
        echo $mensajeTransaccion;
        mostrarProductos($pdo, "Estado de Productos DESPUÉS de Rollback Test");
        echo "<hr/>";
        $mensajeTransaccion = ""; // Limpiar
    }


    // --- Botones para probar las transacciones ---
    echo "<div class='action-buttons'>";
    echo "<h3>Probar Transacciones:</h3>";
    echo "<form method='GET' action='transacciones_pdo.php' style='display:inline-block;'>
            <input type='hidden' name='accion' value='commit_test'>
            <button type='submit'>Ejecutar Transacción Exitosa (COMMIT)</button>
          </form>";
    echo "<form method='GET' action='transacciones_pdo.php' style='display:inline-block;'>
            <input type='hidden' name='accion' value='rollback_test'>
            <button type='submit' class='error'>Ejecutar Transacción Fallida (ROLLBACK)</button>
          </form>";
    echo "</div>";


    echo "<hr><h3>Puntos Clave sobre Transacciones:</h3>";
    echo "<ul>
            <li>No todos los motores de almacenamiento de bases de datos soportan transacciones (ej. MyISAM en MySQL no lo hace, InnoDB sí).</li>
            <li><code>beginTransaction()</code>: Desactiva el modo autocommit. Los cambios no se guardan permanentemente hasta <code>commit()</code>.</li>
            <li><code>commit()</code>: Guarda todos los cambios realizados desde <code>beginTransaction()</code>.</li>
            <li><code>rollBack()</code>: Deshace todos los cambios realizados desde <code>beginTransaction()</code>.</li>
            <li><code>inTransaction()</code>: Verifica si PDO está actualmente dentro de una transacción.</li>
            <li>Es crucial usar bloques <code>try...catch</code> para manejar errores y asegurar que se haga <code>rollBack()</code> si algo falla.</li>
            <li>Algunas sentencias DDL (Data Definition Language) como <code>CREATE TABLE</code>, <code>DROP TABLE</code>, <code>ALTER TABLE</code> pueden causar un commit implícito en algunas bases de datos, finalizando cualquier transacción activa. Ten cuidado al mezclarlas con DML (Data Manipulation Language) dentro de una transacción.</li>
          </ul>";


} catch (PDOException $e) {
    echo "<p class='message error'>Error de conexión general a la BD: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</p>";
} finally {
    $pdo = null;
}

echo "<hr>";
echo "<p><a href='index.php'>Volver al índice de PDO</a> (si existe)</p>";
echo "<p><a href='consultas_insert_update_delete_pdo.php'>Ir a Consultas INSERT, UPDATE, DELETE</a> | <a href='manejo_errores_pdo.php'>Ir a Manejo de Errores PDO</a></p>";

echo "</div></body></html>";
?>
