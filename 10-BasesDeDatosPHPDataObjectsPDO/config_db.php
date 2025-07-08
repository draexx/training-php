<?php
// TEMA: BASES DE DATOS CON PHP (PDO) - Archivo de Configuración

// Este archivo contendrá los parámetros de conexión a la base de datos.
// Es una buena práctica mantener esta información separada del código principal
// por seguridad y facilidad de mantenimiento.

// --- Parámetros de Conexión a la Base de Datos ---
// Modifica estos valores según tu configuración de base de datos.

// Para MySQL / MariaDB:
define('DB_HOST', 'localhost');      // Host de la base de datos (ej. 'localhost' o una IP)
define('DB_NAME', 'mi_tienda_pdo');  // Nombre de la base de datos
define('DB_USER', 'root');       // Usuario de la base de datos
define('DB_PASS', '');       // Contraseña del usuario
define('DB_CHARSET', 'utf8mb4');     // Juego de caracteres (utf8mb4 es recomendado para MySQL)

// Para PostgreSQL:
// define('DB_HOST_PG', 'localhost');
// define('DB_NAME_PG', 'mi_base_pg');
// define('DB_USER_PG', 'usuario_pg');
// define('DB_PASS_PG', 'contraseña_pg');
// define('DB_CHARSET_PG', 'utf8');

// Para SQLite:
// La "conexión" a SQLite es simplemente la ruta al archivo de la base de datos.
// define('DB_PATH_SQLITE', __DIR__ . '/mi_base_sqlite.db'); // __DIR__ es el directorio actual del archivo

// --- Data Source Name (DSN) ---
// El DSN varía según el tipo de base de datos.

// DSN para MySQL / MariaDB
$dsn_mysql = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

// DSN para PostgreSQL
// $dsn_pgsql = "pgsql:host=" . DB_HOST_PG . ";dbname=" . DB_NAME_PG . ";options='--client_encoding=" . DB_CHARSET_PG . "'";

// DSN para SQLite
// $dsn_sqlite = "sqlite:" . DB_PATH_SQLITE;


// --- Opciones de PDO (opcional pero recomendado) ---
$opciones_pdo = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lanzar excepciones en errores (recomendado)
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Modo de obtención por defecto: array asociativo
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Desactivar emulación de sentencias preparadas para mayor seguridad (si el driver lo soporta bien)
];

/*
Explicación de las opciones de PDO:

- PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION:
  Esta es la configuración más importante para el manejo de errores.
  En lugar de que PDO devuelva errores silenciosos o warnings (que son fáciles de ignorar),
  lanzará una PDOException. Esto permite capturar los errores de base de datos
  usando bloques try-catch, lo cual es una práctica mucho más robusta.
  Otras opciones son:
    - PDO::ERRMODE_SILENT: (Por defecto) Solo establece códigos de error. Hay que comprobarlos manualmente con $pdo->errorCode() y $pdo->errorInfo().
    - PDO::ERRMODE_WARNING: Emite un E_WARNING.

- PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC:
  Define cómo PDO devuelve los resultados de las consultas SELECT por defecto.
  PDO::FETCH_ASSOC devuelve cada fila como un array asociativo (nombre_columna => valor).
  Otras opciones comunes:
    - PDO::FETCH_OBJ: Devuelve cada fila como un objeto anónimo con nombres de propiedad correspondientes a los nombres de columna.
    - PDO::FETCH_NUM: Devuelve cada fila como un array numérico.
    - PDO::FETCH_BOTH: (Por defecto) Devuelve cada fila como un array indexado tanto por nombre de columna como por número de columna. (Consume más memoria).
  Se puede sobrescribir este modo por defecto al llamar a los métodos fetch() o fetchAll().

- PDO::ATTR_EMULATE_PREPARES => false:
  Cuando se usan sentencias preparadas, PDO puede emularlas (PHP construye la consulta) o usar
  preparación nativa del servidor de base de datos.
  Desactivar la emulación (false) fuerza a PDO a usar sentencias preparadas nativas del SGBD,
  lo que generalmente es más seguro contra inyección SQL, especialmente con algunas
  configuraciones de MySQL más antiguas o drivers. Si el driver no soporta preparación nativa
  completamente, PDO podría recurrir a la emulación de todas formas o dar error.
  Para la mayoría de los drivers modernos (MySQL, PostgreSQL), `false` es la opción recomendada.

Otras opciones útiles podrían ser:
- PDO::ATTR_PERSISTENT => true:
  Para conexiones persistentes. Esto puede mejorar el rendimiento en algunos casos,
  pero debe usarse con cuidado, ya que las conexiones permanecen abiertas después de que
  el script termina. No siempre es recomendable para aplicaciones web típicas.

- Para MySQL específicamente:
  PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
  Aunque el charset ya se especifica en el DSN, esta opción asegura que el comando
  se ejecute inmediatamente después de la conexión. Es redundante si el charset del DSN funciona bien.
*/

// Este archivo solo define las constantes y variables.
// No produce ninguna salida, por lo que puede ser incluido (`require_once`)
// de forma segura en otros scripts PHP.

// echo "<p>Archivo de configuración de base de datos cargado (config_db.php).</p>"; // Solo para depuración si se accede directamente
?>
