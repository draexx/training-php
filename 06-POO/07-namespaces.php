<?php
// TEMA: PROGRAMACIÓN ORIENTADA A OBJETOS (POO) - Namespaces (Espacios de Nombres)

// Los namespaces son una forma de encapsular elementos como clases, interfaces, funciones y constantes.
// Ayudan a evitar colisiones de nombres entre código creado por diferentes desarrolladores o en diferentes bibliotecas.
// Permiten organizar el código de manera más estructurada, similar a los directorios en un sistema de archivos.

// NOTA IMPORTANTE: La declaración de namespace DEBE ser la primera instrucción en un archivo PHP (excepto comentarios o declare()).
// Por lo tanto, para demostrar múltiples namespaces o su uso, normalmente se haría en archivos separados.
// En este script, simularemos la estructura y explicaremos los conceptos.
// Para ejecutar ejemplos prácticos de namespaces, cada namespace y las clases que contiene
// deberían estar en su propio archivo, y luego se usarían con `use` o nombres completamente calificados.

echo "<h1>POO: Namespaces (Espacios de Nombres)</h1>";

echo "<p><strong>Declaración:</strong> <code>namespace MiProyecto\SubNivel;</code></p>";
echo "<p>Normalmente, esta declaración iría al inicio del archivo.</p>";

echo "<hr/>";

// ========= SIMULACIÓN DE ARCHIVOS Y NAMESPACES =========
// Imaginemos que tenemos la siguiente estructura de directorios y archivos:
// /MiProyecto/
//   Utils/
//     Logger.php       (namespace MiProyecto\Utils)
//     Validador.php    (namespace MiProyecto\Utils)
//   Modelos/
//     Usuario.php      (namespace MiProyecto\Modelos)
//     Producto.php     (namespace MiProyecto\Modelos)
//   index.php          (Puede estar en el namespace global o en uno propio)


// --- Archivo: MiProyecto/Utils/Logger.php ---
/*
<?php
namespace MiProyecto\Utils; // Declaración del namespace

class Logger {
    public static function log($mensaje) {
        echo "[LOG Utils] " . date("Y-m-d H:i:s") . ": " . htmlspecialchars($mensaje) . "<br/>";
    }
}

function debug($variable) { // Función dentro del namespace MiProyecto\Utils
    echo "<pre>Debug (MiProyecto\\Utils): ";
    print_r($variable);
    echo "</pre>";
}

const VERSION = "1.0 Utils"; // Constante dentro del namespace MiProyecto\Utils
?>
*/

// --- Archivo: MiProyecto/Modelos/Usuario.php ---
/*
<?php
namespace MiProyecto\Modelos;

class Usuario {
    public $nombre;
    public function __construct($nombre) {
        $this->nombre = $nombre;
    }
    public function getNombre() {
        return $this->nombre;
    }
}
?>
*/

// --- Archivo: MiProyecto/Modelos/Producto.php ---
/*
<?php
namespace MiProyecto\Modelos;

// Clase con el mismo nombre que una en otro namespace (ej. si hubiera un Utils\Producto)
class Producto {
    public $nombreProducto;
    public function __construct($nombre) {
        $this->nombreProducto = $nombre;
    }
    public function getNombreProducto() {
        return $this->nombreProducto;
    }
}

// Para usar una clase del mismo namespace, no se necesita prefijo.
// Si quisiéramos usar MiProyecto\Modelos\Usuario aquí:
// $usr = new Usuario("Test");
?>
*/


// --- Archivo: index.php (o donde se usan los namespaces) ---
// <?php
// (Este archivo podría estar en el namespace global o en su propio namespace, ej. `namespace App;`)

echo "<h2>Uso de Clases y Elementos con Namespace</h2>";

echo "<h3>1. Nombre Completamente Calificado (Fully Qualified Name)</h3>";
echo "<p>Se usa la ruta completa del namespace, empezando con una barra invertida <code>\</code> si estamos dentro de otro namespace, o sin ella si estamos en el espacio global y el namespace no es sub-nivel del actual.</p>";
echo "<p>Ejemplo: <code>\$logger = new \MiProyecto\Utils\Logger();</code></p>";

// Para que este ejemplo funcione, necesitaríamos incluir los archivos y que sus namespaces estén definidos.
// Como estamos en un solo archivo, vamos a definir las clases aquí mismo bajo namespaces simulados mediante comentarios.

// Simulando que estas clases están definidas en sus respectivos namespaces:
if (!class_exists('MiProyecto\Utils\Logger')) {
    // Esto es solo una simulación para este script único.
    // En la vida real, esto estaría en Logger.php
    namespace MiProyecto\Utils { // INICIO DEL NAMESPACE SIMULADO
        class Logger {
            public static function log($mensaje) {
                echo "[LOG Utils] " . date("Y-m-d H:i:s") . ": " . htmlspecialchars($mensaje) . "<br/>";
            }
        }
        function debug($variable) {
             echo "<pre>Debug (MiProyecto\\Utils): <br/>";
             print_r($variable);
             echo "</pre><br/>";
        }
        const VERSION = "1.0 Utils";
    } // FIN DEL NAMESPACE SIMULADO MiProyecto\Utils

    namespace MiProyecto\Modelos { // INICIO DEL NAMESPACE SIMULADO
        class Usuario {
            public $nombre;
            public function __construct($nombre) { $this->nombre = $nombre; }
            public function getNombre() { return $this->nombre; }
        }
        class Producto {
            public $nombreProducto;
            public function __construct($nombre) { $this->nombreProducto = $nombre; }
            public function getNombreProducto() { return $this->nombreProducto; }
        }
    } // FIN DEL NAMESPACE SIMULADO MiProyecto\Modelos

    // Volvemos al namespace global (o el namespace del archivo actual si estuviera definido)
    namespace { // Namespace global

        // Usando Nombre Completamente Calificado
        \MiProyecto\Utils\Logger::log("Mensaje desde el logger usando nombre completo.");
        $usuario = new \MiProyecto\Modelos\Usuario("Juan Pérez");
        echo "Usuario: " . htmlspecialchars($usuario->getNombre()) . "<br/>";

        // Usando función con nombre completamente calificado
        \MiProyecto\Utils\debug(["a" => 1, "b" => 2]);

        // Usando constante con nombre completamente calificado
        echo "Versión del Logger (Utils): " . \MiProyecto\Utils\VERSION . "<br/>";

        echo "<hr/>";


        echo "<h3>2. Importación con la palabra clave `use`</h3>";
        echo "<p>Permite importar clases, interfaces, funciones o constantes para usarlas con un nombre más corto (alias o nombre de la clase/función/constante).</p>";
        echo "<p><code>use MiProyecto\Utils\Logger;</code><br/>
                 <code>use MiProyecto\Modelos\Usuario as User;</code> (con alias)<br/>
                 <code>use function MiProyecto\Utils\debug;</code> (para funciones)<br/>
                 <code>use const MiProyecto\Utils\VERSION;</code> (para constantes)</p>";
        echo "<p>La sentencia `use` se coloca al principio del script (después de la declaración de namespace, si la hay).</p>";

        // Simulación de importación (en un script real, esto iría arriba)
        // Para que esto funcione tal cual, estas declaraciones 'use' deberían estar en el ámbito global o al inicio del namespace actual.
        // Aquí, las comentaremos porque su efecto ya está cubierto por la definición simulada anterior
        // y para evitar errores de "ya en uso" si se ejecutara este bloque múltiples veces.

        // use MiProyecto\Utils\Logger; // Importa la clase Logger
        // use MiProyecto\Modelos\Usuario as UserModelo; // Importa Usuario con un alias
        // use function MiProyecto\Utils\debug as debugUtil; // Importa la función debug con alias
        // use const MiProyecto\Utils\VERSION as LOGGER_VERSION; // Importa la constante VERSION con alias

        // Uso después de 'use' (asumiendo que las líneas 'use' están activas y al inicio del scope)
        // Logger::log("Mensaje usando 'use Logger'."); // Ya no necesita \MiProyecto\Utils\
        // $userModelo = new UserModelo("Ana García");
        // echo "Usuario (con alias UserModelo): " . htmlspecialchars($userModelo->getNombre()) . "<br/>";
        // debugUtil(["clave" => "valor"]);
        // echo "Versión del Logger (con alias): " . LOGGER_VERSION . "<br/>";

        // Como las 'use' están comentadas para esta simulación, volvemos a usar nombres completos:
        \MiProyecto\Utils\Logger::log("Mensaje usando 'use Logger' (simulado con nombre completo).");
        $userModelo = new \MiProyecto\Modelos\Usuario("Ana García"); // Sin alias aquí
        echo "Usuario (sin alias UserModelo): " . htmlspecialchars($userModelo->getNombre()) . "<br/>";
        \MiProyecto\Utils\debug(["clave" => "valor"]); // Sin alias aquí
        echo "Versión del Logger (sin alias): " . \MiProyecto\Utils\VERSION . "<br/>";

        echo "<hr/>";


        echo "<h3>3. Namespace Relativo y la palabra clave `namespace`</h3>";
        echo "<p>Dentro de un namespace, se puede hacer referencia a otros elementos del mismo namespace directamente.</p>";
        echo "<p>La palabra clave <code>namespace</code> como prefijo se refiere al namespace actual. <code>namespace\ClaseEnMismoNs</code>.</p>";

        // Simulación: Si estuviéramos dentro de `namespace MiProyecto\Utils { ... }`
        // $logger = new Logger(); // Se refiere a MiProyecto\Utils\Logger
        // $otraClase = new namespace\OtraClaseDelMismoUtils(); // También se refiere a MiProyecto\Utils\OtraClaseDelMismoUtils

        echo "<p>Esto es más relevante cuando se trabaja con sub-namespaces.</p>";

        echo "<hr/>";

        echo "<h2>Sub-Namespaces</h2>";
        echo "<p>Los namespaces pueden anidarse: <code>namespace MiProyecto\Servicios\Autenticacion;</code></p>";
        // class Autenticador { ... } // Estaría en MiProyecto\Servicios\Autenticacion\Autenticador
        // Para usarla: new \MiProyecto\Servicios\Autenticacion\Autenticador();
        // O con `use MiProyecto\Servicios\Autenticacion\Autenticador;`

        echo "<hr/>";

        echo "<h2>Namespace Global</h2>";
        echo "<p>El código que no está dentro de un namespace declarado pertenece al namespace global.</p>";
        echo "<p>Para acceder a clases del namespace global desde dentro de un namespace, se usa una barra invertida al inicio: <code>\$fecha = new \DateTime();</code></p>";

        // Ejemplo: Si estuviéramos en `namespace MiProyecto;`
        // $dt = new DateTime(); // Intentaría buscar MiProyecto\DateTime (que no existe) -> Error
        // $dt = new \DateTime(); // Correcto, busca en el namespace global.

        $fechaGlobal = new \DateTime(); // Estamos en el namespace global simulado, \ es opcional pero buena práctica
        echo "Fecha desde el namespace global: " . $fechaGlobal->format('Y-m-d H:i:s') . "<br/>";

        echo "<hr/>";

        echo "<h2>Beneficios de los Namespaces</h2>";
        echo "<ul>
                <li><strong>Prevención de colisiones de nombres:</strong> Permite tener clases o funciones con el mismo nombre en diferentes contextos (namespaces).</li>
                <li><strong>Organización del código:</strong> Ayuda a estructurar proyectos grandes, agrupando código relacionado.</li>
                <li><strong>Mejora la legibilidad:</strong> Nombres más cortos al usar la sentencia `use`.</li>
                <li><strong>Facilita la creación de bibliotecas reutilizables.</strong></li>
              </ul>";

        echo "<p><strong>Autocarga (Autoloading):</strong> Los namespaces son fundamentales para los sistemas de autocarga modernos (como PSR-4).
        Un autocargador puede mapear un namespace a una estructura de directorios, cargando automáticamente los archivos de clase cuando se necesitan.
        Por ejemplo, la clase <code>MiProyecto\Utils\Logger</code> podría estar en el archivo <code>src/MiProyecto/Utils/Logger.php</code> o, más comúnmente bajo PSR-4, <code>src/Utils/Logger.php</code> si 'MiProyecto' es el vendor o un prefijo base del namespace mapeado a 'src/'.
        </p>";
    } // Cierre del namespace global simulado
} else {
    // Este bloque se ejecutaría si las clases ya estuvieran definidas (ej. si se recarga la página)
    // Para simplificar, solo mostraremos un mensaje.
    echo "<p>Clases de namespaces ya definidas (simulación anterior). Recargue para ver la ejecución completa.</p>";
    // Aquí podríamos llamar a los métodos si quisiéramos probarlos de nuevo
    \MiProyecto\Utils\Logger::log("Mensaje de prueba en recarga.");
    $usuarioRecarga = new \MiProyecto\Modelos\Usuario("Usuario Recarga");
    echo "Usuario recargado: " . htmlspecialchars($usuarioRecarga->getNombre()) . "<br/>";
}


echo "<br/><hr/>Fin del script de namespaces.<br/>";
?>
