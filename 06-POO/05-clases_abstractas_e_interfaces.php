<?php
// TEMA: PROGRAMACIÓN ORIENTADA A OBJETOS (POO) - Clases Abstractas e Interfaces

echo "<h1>POO: Clases Abstractas e Interfaces</h1>";

// Tanto las clases abstractas como las interfaces son herramientas para lograr la abstracción
// y definir "contratos" que otras clases deben seguir. Sin embargo, tienen diferencias clave.

// ========= CLASES ABSTRACTAS =========
echo "<h2>Clases Abstractas</h2>";
echo "<p>Una clase abstracta es una clase que no puede ser instanciada directamente.
Está diseñada para ser heredada por otras clases (clases concretas).</p>";
echo "<p>Puede contener tanto métodos abstractos (sin implementación, solo la firma)
como métodos concretos (con implementación).</p>";
echo "<p>Una clase que contiene al menos un método abstracto DEBE ser declarada como abstracta.</p>";
echo "<p>Las clases hijas DEBEN implementar todos los métodos abstractos de la clase padre abstracta,
o bien, la clase hija también debe ser declarada como abstracta.</p>";

// Definición de una clase abstracta
abstract class Figura {
    protected $color;

    public function __construct($color = "negro") {
        $this->color = $color;
        echo "Constructor de Figura: Color base establecido a " . htmlspecialchars($this->color) . ".<br/>";
    }

    // Método concreto (con implementación)
    public function getColor() {
        return $this->color;
    }

    public function setColor($color) {
        $this->color = $color;
    }

    // Método abstracto (sin implementación, solo la firma)
    // Las clases hijas deberán proporcionar la implementación.
    abstract public function calcularArea();
    abstract public function dibujar(); // Otro método abstracto
}

class CirculoConcreto extends Figura {
    private $radio;

    public function __construct($radio, $color = "rojo") {
        parent::__construct($color); // Llama al constructor de la clase padre (Figura)
        $this->radio = $radio;
        echo "Constructor de CirculoConcreto: Radio establecido a " . htmlspecialchars($this->radio) . ".<br/>";
    }

    // Implementación obligatoria del método abstracto calcularArea()
    public function calcularArea() {
        return pi() * pow($this->radio, 2);
    }

    // Implementación obligatoria del método abstracto dibujar()
    public function dibujar() {
        return "Dibujando un círculo de color " . htmlspecialchars($this->color) . " con radio " . htmlspecialchars($this->radio) . ".";
    }

    public function getRadio() { // Método específico de esta clase
        return $this->radio;
    }
}

class CuadradoConcreto extends Figura {
    private $lado;

    public function __construct($lado, $color = "azul") {
        parent::__construct($color);
        $this->lado = $lado;
        echo "Constructor de CuadradoConcreto: Lado establecido a " . htmlspecialchars($this->lado) . ".<br/>";
    }

    public function calcularArea() {
        return pow($this->lado, 2);
    }

    public function dibujar() {
        return "Dibujando un cuadrado de color " . htmlspecialchars($this->color) . " con lado " . htmlspecialchars($this->lado) . ".";
    }
}

// $figuraGenerica = new Figura(); // Error Fatal: Cannot instantiate abstract class Figura

echo "<h3>Ejemplos con Clases Abstractas:</h3>";
$miCirculo = new CirculoConcreto(5, "verde");
echo $miCirculo->dibujar() . "<br/>";
echo "Área del círculo: " . $miCirculo->calcularArea() . "<br/>";
echo "Color del círculo: " . $miCirculo->getColor() . "<br/>";

$miCuadrado = new CuadradoConcreto(4);
echo $miCuadrado->dibujar() . "<br/>";
echo "Área del cuadrado: " . $miCuadrado->calcularArea() . "<br/>";
$miCuadrado->setColor("amarillo");
echo "Nuevo color del cuadrado: " . $miCuadrado->getColor() . "<br/>";
echo "<hr/>";


// ========= INTERFACES =========
echo "<h2>Interfaces</h2>";
echo "<p>Una interfaz es un contrato que define un conjunto de métodos que una clase DEBE implementar.</p>";
echo "<p>Solo especifica las firmas de los métodos (nombre, parámetros, tipo de retorno), NO su implementación.</p>";
echo "<p>Una clase puede implementar múltiples interfaces (a diferencia de la herencia, donde solo puede heredar de una clase padre).</p>";
echo "<p>Las interfaces se definen con la palabra clave `interface` y se implementan en una clase con `implements`.</p>";
echo "<p>Todos los métodos declarados en una interfaz deben ser públicos en la clase que la implementa.</p>";
echo "<p>Las interfaces también pueden definir constantes.</p>";

interface AnimalDomestico {
    const TIPO_MASCOTA = "Doméstico"; // Constante en una interfaz

    public function serAlimentado($comida);
    public function jugar();
}

interface Volador {
    public function despegar();
    public function aterrizar();
    public function volar();
}

class PerroDomestico implements AnimalDomestico {
    private $nombre;

    public function __construct($nombre) {
        $this->nombre = $nombre;
    }

    public function serAlimentado($comida) {
        return htmlspecialchars($this->nombre) . " está comiendo " . htmlspecialchars($comida) . ". ¡Qué rico!";
    }

    public function jugar() {
        return htmlspecialchars($this->nombre) . " está jugando con la pelota.";
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getTipoMascota() {
        return self::TIPO_MASCOTA; // Accediendo a la constante de la interfaz
    }
}

class PajaroDomestico implements AnimalDomestico, Volador { // Implementa dos interfaces
    private $nombre;
    private $especie;

    public function __construct($nombre, $especie) {
        $this->nombre = $nombre;
        $this->especie = $especie;
    }

    // Métodos de AnimalDomestico
    public function serAlimentado($comida) {
        return htmlspecialchars($this->nombre) . " (un " . htmlspecialchars($this->especie) . ") está picoteando " . htmlspecialchars($comida) . ".";
    }

    public function jugar() {
        return htmlspecialchars($this->nombre) . " está revoloteando y cantando.";
    }

    // Métodos de Volador
    public function despegar() {
        return htmlspecialchars($this->nombre) . " bate sus alas y despega.";
    }

    public function aterrizar() {
        return htmlspecialchars($this->nombre) . " aterriza suavemente en su percha.";
    }

    public function volar() {
        return htmlspecialchars($this->nombre) . " está volando por la habitación.";
    }

    public function getNombre() {
        return $this->nombre;
    }
}

echo "<h3>Ejemplos con Interfaces:</h3>";
$miPerro = new PerroDomestico("Buddy");
echo $miPerro->jugar() . "<br/>";
echo $miPerro->serAlimentado("croquetas") . "<br/>";
echo $miPerro->getNombre() . " es de tipo: " . $miPerro->getTipoMascota() . "<br/>";
echo "Constante TIPO_MASCOTA directamente desde la interfaz: " . AnimalDomestico::TIPO_MASCOTA . "<br/>";


$miCanario = new PajaroDomestico("Piolín", "Canario");
echo $miCanario->jugar() . "<br/>";
echo $miCanario->serAlimentado("semillas de alpiste") . "<br/>";
echo $miCanario->despegar() . "<br/>";
echo $miCanario->volar() . "<br/>";
echo $miCanario->aterrizar() . "<br/>";

// Función que espera un objeto que implemente AnimalDomestico
function cuidarMascota(AnimalDomestico $mascota, $comida) {
    echo "<p>Cuidando a " . htmlspecialchars($mascota->getNombre() ?? 'una mascota') . ":<br/>"; // Asumiendo que tiene getNombre()
    echo "- " . $mascota->jugar() . "<br/>";
    echo "- " . $mascota->serAlimentado($comida) . "<br/>";
    if ($mascota instanceof Volador) { // Comprobamos si también es Volador
        echo "- Esta mascota puede volar: " . $mascota->volar() . "<br/>";
    }
    echo "</p>";
}

cuidarMascota($miPerro, "hueso de juguete");
cuidarMascota($miCanario, "mijo");

echo "<hr/>";

// ========= DIFERENCIAS CLAVE Y CUÁNDO USAR QUÉ =========
echo "<h2>Diferencias Clave y Cuándo Usar</h2>";
echo "<table border='1' cellpadding='5' cellspacing='0' style='width:100%; border-collapse: collapse;'>
        <thead>
            <tr>
                <th>Característica</th>
                <th>Clase Abstracta</th>
                <th>Interfaz</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Instanciación</strong></td>
                <td>No se puede instanciar directamente.</td>
                <td>No se puede instanciar directamente.</td>
            </tr>
            <tr>
                <td><strong>Métodos</strong></td>
                <td>Puede tener métodos abstractos (sin cuerpo) y métodos concretos (con cuerpo).</td>
                <td>Solo puede tener firmas de métodos (sin cuerpo). Todos los métodos son implícitamente abstractos y públicos.</td>
            </tr>
            <tr>
                <td><strong>Propiedades (Variables Miembro)</strong></td>
                <td>Puede tener propiedades (variables miembro) con cualquier visibilidad (public, protected, private).</td>
                <td>No puede tener propiedades de instancia. Solo puede definir constantes.</td>
            </tr>
            <tr>
                <td><strong>Constructores</strong></td>
                <td>Puede tener un constructor.</td>
                <td>No puede tener un constructor.</td>
            </tr>
            <tr>
                <td><strong>Herencia / Implementación</strong></td>
                <td>Una clase puede heredar de <strong>una sola</strong> clase abstracta (<code>extends</code>).</td>
                <td>Una clase puede implementar <strong>múltiples</strong> interfaces (<code>implements</code>).</td>
            </tr>
            <tr>
                <td><strong>Propósito Principal</strong></td>
                <td>Proveer una clase base común con alguna implementación compartida y forzar la implementación de ciertos métodos en las subclases. Define una relación 'ES UN' (un Círculo ES UNA Figura).</td>
                <td>Definir un contrato de capacidades o comportamientos que una clase puede tener, sin importar su jerarquía de herencia. Define una relación 'PUEDE HACER' (un Pájaro PUEDE HACER [lo que define] Volador).</td>
            </tr>
            <tr>
                <td><strong>Cuándo usar (generalmente)</strong></td>
                <td>Cuando quieres compartir código (métodos concretos, propiedades) entre clases estrechamente relacionadas y definir una estructura común.</td>
                <td>Cuando quieres definir un contrato para clases que pueden ser de diferentes tipos o jerarquías, pero deben ofrecer ciertas funcionalidades. Para lograr polimorfismo con clases no relacionadas.</td>
            </tr>
        </tbody>
      </table>";

echo "<p>En resumen:
      <ul>
        <li>Usa <strong>clases abstractas</strong> cuando quieras crear una plantilla para un grupo de subclases relacionadas, compartiendo alguna implementación base.</li>
        <li>Usa <strong>interfaces</strong> cuando quieras definir un contrato de comportamiento que clases, posiblemente no relacionadas, deben adherir. Es ideal para desacoplar componentes y permitir múltiples 'tipos' para un objeto.</li>
      </ul>
      A menudo, se pueden usar juntas. Una clase puede extender una clase abstracta e implementar una o más interfaces.
      </p>";


echo "<br/><hr/>Fin del script de clases abstractas e interfaces.<br/>";
?>
