<?php
// TEMA: PROGRAMACIÓN ORIENTADA A OBJETOS (POO) - Polimorfismo

echo "<h1>POO: Polimorfismo</h1>";

// Polimorfismo significa "muchas formas". En POO, se refiere a la capacidad
// de objetos de diferentes clases de responder al mismo mensaje (llamada a método)
// de diferentes maneras. Generalmente se logra a través de:
// 1. Herencia (sobrescritura de métodos).
// 2. Interfaces (implementación de métodos).

// ========= POLIMORFISMO CON HERENCIA =========
echo "<h2>Polimorfismo con Herencia</h2>";

interface AnimalSonido {
    public function hacerSonido(); // Todas las clases que implementen esta interfaz DEBEN definir este método.
}

// Clase base (puede ser abstracta si no se quiere instanciar directamente)
abstract class Animal {
    protected $nombre;

    public function __construct($nombre) {
        $this->nombre = $nombre;
    }

    // Método abstracto: debe ser implementado por las clases hijas.
    // Esto fuerza a las subclases a tener su propia implementación.
    abstract public function moverse();

    // Método común que puede ser usado por todas las clases hijas
    public function getNombre() {
        return $this->nombre;
    }

    // Un método que podría ser sobrescrito, pero no es abstracto
    public function describir() {
        echo "Soy un animal llamado " . htmlspecialchars($this->nombre) . ". ";
    }
}

class Perro extends Animal implements AnimalSonido {
    public function __construct($nombre) {
        parent::__construct($nombre);
    }

    // Implementación del método abstracto de la clase padre
    public function moverse() {
        return htmlspecialchars($this->nombre) . " corre y salta.";
    }

    // Implementación del método de la interfaz
    public function hacerSonido() {
        return "Guau guau!";
    }

    // Sobrescribiendo el método describir
    public function describir() {
        parent::describir(); // Llama al método del padre
        echo "Soy un perro leal y amigo del hombre. <br/>";
    }
}

class Gato extends Animal implements AnimalSonido {
    public function __construct($nombre) {
        parent::__construct($nombre);
    }

    public function moverse() {
        return htmlspecialchars($this->nombre) . " camina sigilosamente y trepa.";
    }

    public function hacerSonido() {
        return "Miau miau!";
    }

    public function describir() {
        parent::describir();
        echo "Soy un gato independiente y elegante. <br/>";
    }
}

class Pajaro extends Animal implements AnimalSonido {
    public function __construct($nombre) {
        parent::__construct($nombre);
    }

    public function moverse() {
        return htmlspecialchars($this->nombre) . " vuela por el cielo.";
    }

    public function hacerSonido() {
        return "Pío pío!";
    }

    public function describir() {
        parent::describir();
        echo "Soy un pájaro cantor y libre. <br/>";
    }
}

// Creación de objetos de diferentes clases (pero todas son 'Animal')
$animales = [
    new Perro("Fido"),
    new Gato("Mishi"),
    new Pajaro("Piolín"),
    new Perro("Rex")
];

echo "<h3>Demostración de Polimorfismo:</h3>";
foreach ($animales as $animal) {
    // A pesar de que $animal puede ser Perro, Gato o Pajaro,
    // todos responden a los métodos getNombre(), moverse() y hacerSonido().
    // La implementación específica de moverse() y hacerSonido() depende de la clase real del objeto.
    echo "<strong>" . htmlspecialchars($animal->getNombre()) . ":</strong><br/>";
    echo " - Movimiento: " . $animal->moverse() . "<br/>";

    // Verificamos si el objeto implementa AnimalSonido antes de llamar a hacerSonido()
    // Esto es útil si no todos los Animales hicieran sonidos (aunque en este caso, todos lo hacen)
    if ($animal instanceof AnimalSonido) {
        echo " - Sonido: " . $animal->hacerSonido() . "<br/>";
    }

    // Llamada al método describir, que también es polimórfico
    echo " - Descripción: ";
    $animal->describir(); // Cada animal se describe de forma diferente
    echo "<br/>";
}
echo "<hr/>";


// ========= POLIMORFISMO CON INTERFACES =========
echo "<h2>Polimorfismo con Interfaces</h2>";

// Una interfaz define un contrato de métodos que una clase debe implementar.
// No contiene implementación, solo firmas de métodos.

interface FormaGeometrica {
    public function calcularArea();
    public function getNombreForma();
}

class Circulo implements FormaGeometrica {
    private $radio;

    public function __construct($radio) {
        $this->radio = $radio;
    }

    public function calcularArea() {
        return pi() * pow($this->radio, 2);
    }

    public function getNombreForma() {
        return "Círculo";
    }

    public function getRadio() { // Método específico de Circulo
        return $this->radio;
    }
}

class Rectangulo implements FormaGeometrica {
    private $ancho;
    private $alto;

    public function __construct($ancho, $alto) {
        $this->ancho = $ancho;
        $this->alto = $alto;
    }

    public function calcularArea() {
        return $this->ancho * $this->alto;
    }

    public function getNombreForma() {
        return "Rectángulo";
    }
}

class Triangulo implements FormaGeometrica {
    private $base;
    private $altura;

    public function __construct($base, $altura) {
        $this->base = $base;
        $this->altura = $altura;
    }

    public function calcularArea() {
        return ($this->base * $this->altura) / 2;
    }

    public function getNombreForma() {
        return "Triángulo";
    }
}

// Función que trabaja con cualquier objeto que implemente FormaGeometrica
// Esto es un ejemplo de "programar para la interfaz, no para la implementación".
function imprimirDetallesForma(FormaGeometrica $forma) {
    echo "Forma: " . htmlspecialchars($forma->getNombreForma()) . "<br/>";
    echo "Área: " . htmlspecialchars($forma->calcularArea()) . "<br/>";

    // Si queremos acceder a un método específico de una clase, necesitamos verificar el tipo.
    if ($forma instanceof Circulo) {
        echo "Radio del círculo: " . htmlspecialchars($forma->getRadio()) . "<br/>";
    }
    echo "<br/>";
}

$formas = [
    new Circulo(5),
    new Rectangulo(4, 6),
    new Triangulo(3, 8),
    new Circulo(2.5)
];

echo "<h3>Demostración de Polimorfismo con Interfaces:</h3>";
foreach ($formas as $forma) {
    imprimirDetallesForma($forma); // La función maneja cada forma polimórficamente
}

echo "<p>El polimorfismo permite escribir código más flexible y extensible.
Se pueden agregar nuevas clases (nuevos animales, nuevas formas) sin tener que modificar
el código que ya utiliza las abstracciones (clase base Animal, interfaz FormaGeometrica),
siempre y cuando las nuevas clases cumplan con el contrato establecido (hereden y/o implementen
los métodos requeridos).</p>";

echo "<br/><hr/>Fin del script de polimorfismo.<br/>";
?>
