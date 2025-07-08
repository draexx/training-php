<?php
// TEMA: PROGRAMACIÓN ORIENTADA A OBJETOS (POO) - Clases y Objetos

echo "<h1>POO: Clases y Objetos</h1>";

// ========= DEFINICIÓN DE UNA CLASE =========
echo "<h2>Definición de una Clase</h2>";

// Una clase es una plantilla para crear objetos.
// Define propiedades (datos) y métodos (comportamientos).

class Coche {
    // --- Propiedades (o atributos, miembros de datos) ---
    // Visibilidad: public, private, protected (se verá más adelante)
    public $marca;
    public $modelo;
    public $color = "Blanco"; // Propiedad con valor por defecto
    public $velocidad = 0;
    private $kilometraje = 0; // Encapsulada, no accesible directamente desde fuera

    // --- Métodos (o funciones miembro) ---

    // Constructor: método especial que se llama automáticamente al crear un objeto.
    // Se usa para inicializar las propiedades del objeto.
    public function __construct($marca_param, $modelo_param, $color_param = "Rojo Fuego") {
        echo "Se ha creado un nuevo objeto Coche ({$marca_param}, {$modelo_param}).<br/>";
        $this->marca = $marca_param; // $this se refiere a la instancia actual del objeto
        $this->modelo = $modelo_param;
        $this->color = $color_param;
        $this->kilometraje = 0; // Inicializar kilometraje
    }

    public function acelerar($incremento) {
        if ($incremento > 0) {
            $this->velocidad += $incremento;
            echo "El coche {$this->marca} {$this->modelo} ha acelerado a {$this->velocidad} km/h.<br/>";
        }
    }

    public function frenar($decremento) {
        if ($decremento > 0) {
            $this->velocidad -= $decremento;
            if ($this->velocidad < 0) {
                $this->velocidad = 0;
            }
            echo "El coche {$this->marca} {$this->modelo} ha frenado a {$this->velocidad} km/h.<br/>";
        }
    }

    public function obtenerDescripcion() {
        return "Este coche es un {$this->marca} {$this->modelo} de color {$this->color}. Velocidad actual: {$this->velocidad} km/h.";
    }

    // Método para acceder al kilometraje (Getter)
    public function getKilometraje() {
        return $this->kilometraje . " km";
    }

    // Método para simular el avance y modificar el kilometraje (Setter indirecto)
    public function avanzar($km) {
        if ($km > 0) {
            $this->kilometraje += $km;
            echo "El coche avanzó {$km} km. Kilometraje total: {$this->kilometraje} km.<br/>";
        }
    }

    // Destructor: método especial que se llama cuando el objeto va a ser destruido
    // (por ejemplo, al final del script o cuando se elimina explícitamente con unset())
    public function __destruct() {
        echo "El objeto Coche ({$this->marca} {$this->modelo}) está siendo destruido.<br/>";
    }
}

echo "Clase 'Coche' definida.<br/>";


// ========= CREACIÓN DE OBJETOS (INSTANCIACIÓN) =========
echo "<h2>Creación de Objetos (Instanciación)</h2>";

// Un objeto es una instancia de una clase.
// Se crea usando la palabra clave `new`.

$miCoche = new Coche("Toyota", "Corolla", "Gris Plata"); // Llama al constructor __construct()
$cocheDeAna = new Coche("Honda", "Civic"); // Usará el color por defecto "Rojo Fuego" del constructor

echo "<hr/>";

// ========= ACCESO A PROPIEDADES Y MÉTODOS =========
echo "<h2>Acceso a Propiedades y Métodos</h2>";

// Se usa el operador -> (flecha de objeto)

// --- Acceso a propiedades públicas ---
echo "<h3>Acceso a Propiedades Públicas</h3>";
echo "Marca de mi coche: " . $miCoche->marca . "<br/>"; // Toyota
echo "Modelo del coche de Ana: " . $cocheDeAna->modelo . "<br/>"; // Civic
echo "Color inicial de mi coche: " . $miCoche->color . "<br/>"; // Gris Plata

// Modificar propiedades públicas
$miCoche->color = "Azul Marino";
echo "Nuevo color de mi coche: " . $miCoche->color . "<br/>"; // Azul Marino


// --- Llamada a métodos ---
echo "<h3>Llamada a Métodos</h3>";
$miCoche->acelerar(50); // El coche Toyota Corolla ha acelerado a 50 km/h.
$cocheDeAna->acelerar(30); // El coche Honda Civic ha acelerado a 30 km/h.

$miCoche->frenar(20); // El coche Toyota Corolla ha frenado a 30 km/h.

echo $miCoche->obtenerDescripcion() . "<br/>";
// Este coche es un Toyota Corolla de color Azul Marino. Velocidad actual: 30 km/h.
echo $cocheDeAna->obtenerDescripcion() . "<br/>";
// Este coche es un Honda Civic de color Rojo Fuego. Velocidad actual: 30 km/h.


// --- Acceso a propiedades privadas (indirectamente a través de métodos) ---
echo "<h3>Acceso a Propiedades Privadas (indirecto)</h3>";
// echo $miCoche->kilometraje; // Error Fatal: Cannot access private property Coche::$kilometraje
echo "Kilometraje de mi coche: " . $miCoche->getKilometraje() . "<br/>"; // 0 km

$miCoche->avanzar(150); // El coche avanzó 150 km. Kilometraje total: 150 km.
echo "Nuevo kilometraje de mi coche: " . $miCoche->getKilometraje() . "<br/>"; // 150 km


// ========= LA REFERENCIA $this =========
echo "<h2>La Referencia \$this</h2>";
echo "Como se vio en la definición de la clase, \$this se utiliza DENTRO de los métodos de una clase para referirse al objeto actual (la instancia sobre la cual se está llamando el método).<br/>";
echo "Permite acceder a las propiedades y otros métodos del mismo objeto.<br/>";


// ========= MÁS EJEMPLOS DE OBJETOS =========
echo "<h2>Más Ejemplos</h2>";

class Libro {
    public $titulo;
    public $autor;
    public $isbn;
    public $estaPrestado = false;

    public function __construct($titulo, $autor, $isbn) {
        $this->titulo = $titulo;
        $this->autor = $autor;
        $this->isbn = $isbn;
    }

    public function prestar() {
        if (!$this->estaPrestado) {
            $this->estaPrestado = true;
            echo "El libro '{$this->titulo}' ha sido prestado.<br/>";
        } else {
            echo "El libro '{$this->titulo}' ya está prestado.<br/>";
        }
    }

    public function devolver() {
        if ($this->estaPrestado) {
            $this->estaPrestado = false;
            echo "El libro '{$this->titulo}' ha sido devuelto.<br/>";
        } else {
            echo "El libro '{$this->titulo}' no estaba prestado.<br/>";
        }
    }

    public function getInfo() {
        return "Título: {$this->titulo}, Autor: {$this->autor}, ISBN: {$this->isbn}. Estado: " . ($this->estaPrestado ? 'Prestado' : 'Disponible');
    }
}

$libroPHP = new Libro("PHP para Profesionales", "Juan Autor", "123-456-789");
$libroJava = new Libro("Java desde Cero", "Ana Programadora", "987-654-321");

echo $libroPHP->getInfo() . "<br/>";
$libroPHP->prestar();
echo $libroPHP->getInfo() . "<br/>";
$libroPHP->prestar(); // Intentar prestar de nuevo
$libroPHP->devolver();
echo $libroPHP->getInfo() . "<br/>";

echo "<hr/>";
echo "Al final del script, los destructores de los objetos \$miCoche, \$cocheDeAna, \$libroPHP y \$libroJava serán llamados automáticamente.<br/>";
// Si quisiéramos destruir un objeto explícitamente antes:
// unset($libroJava); // Esto llamaría al destructor de $libroJava si tuviera uno definido.
// Los objetos Coche tienen destructor, los Libro no (en este ejemplo).

echo "<br/><hr/>Fin del script de clases y objetos.<br/>";
?>
