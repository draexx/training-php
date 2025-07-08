<?php
// TEMA: PROGRAMACIÓN ORIENTADA A OBJETOS (POO) - Traits

echo "<h1>POO: Traits</h1>";

// Los Traits son un mecanismo para la reutilización de código en lenguajes de herencia simple como PHP.
// Un Trait permite declarar métodos que pueden ser usados en múltiples clases.
// Ayudan a reducir las limitaciones de la herencia simple, permitiendo a los desarrolladores
// reutilizar conjuntos de métodos libremente en varias clases independientes y de diferentes jerarquías.
// Es como "copiar y pegar" código de manera controlada.

// ========= DEFINICIÓN DE UN TRAIT =========
echo "<h2>Definición de un Trait</h2>";

trait MensajesLog {
    // Propiedad que puede ser usada por el trait
    protected $prefijoLog = "[LOG]";

    public function log($mensaje) {
        echo htmlspecialchars($this->prefijoLog . " " . date("Y-m-d H:i:s") . ": " . $mensaje) . "<br/>";
    }

    public function error($mensaje) {
        echo "<strong style='color:red;'>" . htmlspecialchars($this->prefijoLog . " [ERROR] " . date("Y-m-d H:i:s") . ": " . $mensaje) . "</strong><br/>";
    }

    // Un trait también puede tener métodos abstractos,
    // lo que significa que la clase que usa el trait DEBE implementar ese método.
    abstract public function getIdentificadorParaLog();
}

trait UtilidadesString {
    public function capitalizar($texto) {
        return ucwords(strtolower($texto));
    }

    public function esEmailValido($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}

echo "Traits 'MensajesLog' y 'UtilidadesString' definidos.<br/>";
echo "<hr/>";

// ========= USO DE TRAITS EN CLASES =========
echo "<h2>Uso de Traits en Clases</h2>";
// Se utiliza la palabra clave `use` dentro de la definición de la clase.

class Usuario {
    use MensajesLog, UtilidadesString; // Se pueden usar múltiples traits separados por comas

    public $nombre;
    public $email;

    public function __construct($nombre, $email) {
        $this->nombre = $this->capitalizar($nombre); // Usando método de UtilidadesString

        if ($this->esEmailValido($email)) { // Usando método de UtilidadesString
            $this->email = $email;
        } else {
            $this->email = "invalido@example.com";
            $this->error("El email '{$email}' proporcionado para {$this->nombre} no es válido."); // Usando método de MensajesLog
        }
        $this->log("Usuario '{$this->nombre}' creado con email '{$this->email}'."); // Usando método de MensajesLog
    }

    // Implementación del método abstracto requerido por el trait MensajesLog
    public function getIdentificadorParaLog() {
        return "Usuario:" . $this->nombre;
    }

    public function setEmail($nuevoEmail) {
        if ($this->esEmailValido($nuevoEmail)) {
            $this->email = $nuevoEmail;
            $this->log("Email actualizado para {$this->getIdentificadorParaLog()} a {$this->email}");
        } else {
            $this->error("Intento de actualizar a email inválido: {$nuevoEmail} para {$this->getIdentificadorParaLog()}");
        }
    }
}

class ArticuloBlog {
    use MensajesLog; // Solo usa un trait

    public $titulo;
    private $id;

    public function __construct($id, $titulo) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->prefijoLog = "[BLOG_POST]"; // Podemos sobrescribir propiedades usadas por el trait
        $this->log("Artículo de blog '{$this->titulo}' creado.");
    }

    // Implementación del método abstracto requerido por MensajesLog
    public function getIdentificadorParaLog() {
        return "ArticuloID:" . $this->id;
    }

    public function publicar() {
        $this->log("El artículo '{$this->titulo}' (ID: {$this->id}) ha sido publicado.");
    }
}

echo "<h3>Ejemplos con Clases que usan Traits:</h3>";
$usuario1 = new Usuario("juan perez", "juan.perez@example.com");
// Salida del constructor:
// [LOG] Fecha Hora: Usuario 'Juan Perez' creado con email 'juan.perez@example.com'.

$usuario2 = new Usuario("ana gómez", "ana.gomez_example.com"); // Email inválido
// Salida del constructor:
// [LOG] [ERROR] Fecha Hora: El email 'ana.gomez_example.com' proporcionado para Ana Gómez no es válido.
// [LOG] Fecha Hora: Usuario 'Ana Gómez' creado con email 'invalido@example.com'.

echo "<br/>";
$usuario1->setEmail("juan.nuevo@mail.com");
// [LOG] Fecha Hora: Email actualizado para Usuario:Juan Perez a juan.nuevo@mail.com

$usuario1->setEmail("esto no es un email");
// [LOG] [ERROR] Fecha Hora: Intento de actualizar a email inválido: esto no es un email para Usuario:Juan Perez

echo "<br/>";
$articulo1 = new ArticuloBlog(101, "Introducción a los Traits en PHP");
// [BLOG_POST] Fecha Hora: Artículo de blog 'Introducción a los Traits en PHP' creado.
$articulo1->publicar();
// [BLOG_POST] Fecha Hora: El artículo 'Introducción a los Traits en PHP' (ID: 101) ha sido publicado.

echo "<hr/>";

// ========= RESOLUCIÓN DE CONFLICTOS DE NOMBRES =========
echo "<h2>Resolución de Conflictos de Nombres</h2>";
echo "<p>Si dos traits que una clase usa tienen un método con el mismo nombre, PHP generará un error fatal.
Se debe resolver explícitamente usando `insteadof` (en lugar de) y/o `as` (alias).</p>";

trait TraitA {
    public function metodoComun() {
        return "Método común desde TraitA<br/>";
    }
    public function metodoUnicoA() {
        return "Método único de TraitA<br/>";
    }
}

trait TraitB {
    public function metodoComun() {
        return "Método común desde TraitB<br/>";
    }
    public function metodoUnicoB() {
        return "Método único de TraitB<br/>";
    }
}

class ClaseConConflicto {
    use TraitA, TraitB {
        TraitA::metodoComun insteadof TraitB; // Usar metodoComun de TraitA en lugar del de TraitB
        TraitB::metodoComun as metodoComunDeB;   // Hacer disponible metodoComun de TraitB con un alias
    }
}

$objConflicto = new ClaseConConflicto();
echo $objConflicto->metodoComun();      // Llama a TraitA::metodoComun() -> "Método común desde TraitA"
echo $objConflicto->metodoComunDeB();   // Llama a TraitB::metodoComun() -> "Método común desde TraitB"
echo $objConflicto->metodoUnicoA();     // -> "Método único de TraitA"
echo $objConflicto->metodoUnicoB();     // -> "Método único de TraitB"
echo "<hr/>";


// ========= CAMBIO DE VISIBILIDAD DE MÉTODOS DE TRAITS =========
echo "<h2>Cambio de Visibilidad de Métodos de Traits</h2>";
echo "<p>La clase que usa un trait puede cambiar la visibilidad de los métodos del trait usando la palabra clave `as`.</p>";

trait TraitConVisibilidad {
    public function metodoPublicoOriginal() {
        return "Soy público en el trait.<br/>";
    }

    protected function metodoProtegidoOriginal() {
        return "Soy protegido en el trait.<br/>";
    }

    private function metodoPrivadoOriginal() { // Aunque sea privado en el trait, la clase no puede accederlo directamente
        return "Soy privado en el trait.<br/>";
    }

    public function llamarPrivadoDesdeTrait() {
        return $this->metodoPrivadoOriginal(); // El trait sí puede llamar a sus propios métodos privados
    }
}

class ClaseConCambioVisibilidad {
    use TraitConVisibilidad {
        TraitConVisibilidad::metodoPublicoOriginal as protected metodoProtegidoAdaptado;
        TraitConVisibilidad::metodoProtegidoOriginal as public metodoPublicoAdaptado;
        // TraitConVisibilidad::metodoPrivadoOriginal as public; // Error: No se puede cambiar la visibilidad de un método privado de un trait de esta forma.
                                                                // Los métodos privados de un trait son solo para uso interno del trait.
    }

    public function testVisibilidad() {
        // echo $this->metodoPublicoOriginal(); // Error: ahora es protected (metodoProtegidoAdaptado)
        echo "Llamando a metodoProtegidoAdaptado (originalmente público): " . $this->metodoProtegidoAdaptado();
        echo "Llamando a metodoPublicoAdaptado (originalmente protegido): " . $this->metodoPublicoAdaptado();
        echo "Llamando a llamarPrivadoDesdeTrait: " . $this->llamarPrivadoDesdeTrait();
    }
}

$objVisibilidad = new ClaseConCambioVisibilidad();
// echo $objVisibilidad->metodoPublicoOriginal(); // Error: No existe con ese nombre o visibilidad
// echo $objVisibilidad->metodoProtegidoAdaptado(); // Error: Es protected, no se puede llamar desde fuera
echo "Desde fuera, llamando a metodoPublicoAdaptado: " . $objVisibilidad->metodoPublicoAdaptado();
$objVisibilidad->testVisibilidad();
echo "<hr/>";


// ========= TRAITS COMPUESTOS POR OTROS TRAITS =========
echo "<h2>Traits Compuestos por Otros Traits</h2>";
echo "<p>Los traits también pueden usar otros traits.</p>";

trait LoggerBase {
    public function registrar($msg) {
        echo "[REGISTRO BASE] " . $msg . "<br/>";
    }
}

trait LoggerAvanzado {
    use LoggerBase; // LoggerAvanzado usa LoggerBase

    public function registrarConTimestamp($msg) {
        $this->registrar(date("Y-m-d H:i:s") . " - " . $msg); // Llama al método registrar() de LoggerBase
    }
}

class MiAplicacion {
    use LoggerAvanzado;

    public function ejecutarAccion($accion) {
        $this->registrarConTimestamp("Ejecutando acción: " . $accion);
    }
}

$app = new MiAplicacion();
$app->ejecutarAccion("Procesar Pedido");
// [REGISTRO BASE] Fecha Hora - Ejecutando acción: Procesar Pedido

echo "<p>Los Traits son una forma flexible de añadir funcionalidad a las clases sin recurrir a la herencia múltiple (que PHP no soporta directamente para clases). Son útiles para agrupar métodos relacionados que pueden ser necesarios en diversas partes de una aplicación.</p>";

echo "<br/><hr/>Fin del script de traits.<br/>";
?>
