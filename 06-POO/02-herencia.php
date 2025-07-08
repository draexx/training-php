<?php
// TEMA: PROGRAMACIÓN ORIENTADA A OBJETOS (POO) - Herencia

echo "<h1>POO: Herencia</h1>";

// La herencia es un mecanismo que permite a una clase (clase hija o subclase)
// heredar propiedades y métodos de otra clase (clase padre o superclase).
// Se utiliza la palabra clave `extends`.

// ========= CLASE PADRE (SUPERCLASE) =========
class Vehiculo {
    public $marca;
    public $modelo;
    protected $encendido = false; // protected: accesible en esta clase y en clases hijas

    public function __construct($marca, $modelo) {
        $this->marca = $marca;
        $this->modelo = $modelo;
        echo "Constructor de Vehiculo: Creado un {$this->marca} {$this->modelo}.<br/>";
    }

    public function encender() {
        if (!$this->encendido) {
            $this->encendido = true;
            echo "El {$this->marca} {$this->modelo} se ha encendido.<br/>";
        } else {
            echo "El {$this->marca} {$this->modelo} ya estaba encendido.<br/>";
        }
    }

    public function apagar() {
        if ($this->encendido) {
            $this->encendido = false;
            echo "El {$this->marca} {$this->modelo} se ha apagado.<br/>";
        } else {
            echo "El {$this->marca} {$this->modelo} ya estaba apagado.<br/>";
        }
    }

    public function getEstadoMotor() {
        return $this->encendido ? "Encendido" : "Apagado";
    }

    // Método que podría ser sobrescrito por clases hijas
    public function getTipoVehiculo() {
        return "Vehículo Genérico";
    }
}

echo "<h2>Clase Padre: Vehiculo</h2>";
$miVehiculo = new Vehiculo("MarcaGenérica", "ModeloX");
$miVehiculo->encender();
echo "Estado del motor: " . $miVehiculo->getEstadoMotor() . "<br/>";
echo "Tipo: " . $miVehiculo->getTipoVehiculo() . "<br/>";
$miVehiculo->apagar();
echo "<hr/>";


// ========= CLASE HIJA (SUBCLASE) =========
// La clase Coche hereda de Vehiculo
class Coche extends Vehiculo {
    public $color;
    private $numeroPuertas;

    // Constructor de la clase hija
    public function __construct($marca, $modelo, $color, $numeroPuertas = 4) {
        // Llamar al constructor de la clase padre
        parent::__construct($marca, $modelo); // Llama a Vehiculo::__construct()

        $this->color = $color;
        $this->numeroPuertas = $numeroPuertas;
        echo "Constructor de Coche: Se ha configurado el color a {$this->color} y {$this->numeroPuertas} puertas.<br/>";
    }

    // Método específico de la clase Coche
    public function tocarClaxon() {
        echo "¡Beep beep! Soy el coche {$this->marca} {$this->modelo}.<br/>";
    }

    // Sobrescritura de un método de la clase padre (Overriding)
    // Debe tener la misma (o menos restrictiva) visibilidad y, idealmente, la misma firma.
    public function getTipoVehiculo() {
        return "Coche de Pasajeros";
    }

    // Acceder a una propiedad protected de la clase padre
    public function intentarEncenderDesdeHija() {
        if (!$this->encendido) { // $this->encendido es accesible porque es protected en Vehiculo
            $this->encender(); // Llama al método encender() heredado
            echo "El coche {$this->marca} se encendió a través de un método de la hija.<br/>";
        } else {
            echo "El coche {$this->marca} ya estaba encendido (verificado desde la hija).<br/>";
        }
    }

    public function getNumeroPuertas() {
        return $this->numeroPuertas;
    }
}

echo "<h2>Clase Hija: Coche (hereda de Vehiculo)</h2>";
$miCoche = new Coche("Toyota", "Corolla", "Rojo", 4);

// Métodos heredados de Vehiculo
$miCoche->encender(); // El Toyota Corolla se ha encendido.
echo "Estado del motor del coche: " . $miCoche->getEstadoMotor() . "<br/>"; // Encendido

// Métodos propios de Coche
$miCoche->tocarClaxon(); // ¡Beep beep! Soy el coche Toyota Corolla.

// Método sobrescrito
echo "Tipo de vehículo (desde Coche): " . $miCoche->getTipoVehiculo() . "<br/>"; // Coche de Pasajeros

// Acceso a propiedad protected
$miCoche->apagar(); // Primero lo apagamos
$miCoche->intentarEncenderDesdeHija(); // El coche Toyota se encendió...
echo "Puertas: " . $miCoche->getNumeroPuertas() . "<br/>";

echo "<hr/>";


// ========= OTRA CLASE HIJA: Motocicleta =========
class Motocicleta extends Vehiculo {
    public $tipoChasis; // Doble cuna, multitubular, etc.

    public function __construct($marca, $modelo, $tipoChasis) {
        parent::__construct($marca, $modelo);
        $this->tipoChasis = $tipoChasis;
        echo "Constructor de Motocicleta: Tipo de chasis {$this->tipoChasis}.<br/>";
    }

    public function hacerCaballito() {
        if ($this->encendido) {
            echo "La motocicleta {$this->marca} {$this->modelo} está haciendo un caballito! (Wheee!)<br/>";
        } else {
            echo "La motocicleta {$this->marca} {$this->modelo} debe estar encendida para hacer un caballito.<br/>";
        }
    }

    // Sobrescritura
    public function getTipoVehiculo() {
        return "Motocicleta";
    }

    // Sobrescribir y llamar al método padre
    public function encender() {
        parent::encender(); // Llama a la implementación de encender() de Vehiculo
        echo "Además, la motocicleta {$this->marca} revisó el nivel de aceite al encender.<br/>"; // Comportamiento adicional
    }
}

echo "<h2>Otra Clase Hija: Motocicleta (hereda de Vehiculo)</h2>";
$miMoto = new Motocicleta("Yamaha", "MT-07", "Diamante");

$miMoto->encender();
// Salida:
// El Yamaha MT-07 se ha encendido. (de parent::encender())
// Además, la motocicleta Yamaha revisó el nivel de aceite al encender. (de Motocicleta::encender())

$miMoto->hacerCaballito(); // La motocicleta Yamaha MT-07 está haciendo un caballito!
echo "Tipo de vehículo (desde Motocicleta): " . $miMoto->getTipoVehiculo() . "<br/>"; // Motocicleta
$miMoto->apagar();

echo "<hr/>";

// ========= PALABRA CLAVE `final` =========
echo "<h2>Palabra Clave `final`</h2>";

// `final` en una clase: Evita que la clase sea heredada.
/*
final class ClaseFinal {
    public function miMetodo() {
        echo "Método de una clase final.<br/>";
    }
}
// class IntentoDeHerencia extends ClaseFinal {} // Error Fatal: Class IntentoDeHerencia may not inherit from final class (ClaseFinal)
*/

// `final` en un método: Evita que el método sea sobrescrito en clases hijas.
class ClaseBaseConMetodoFinal {
    public final function metodoNoSobrescribible() {
        echo "Este método no puede ser sobrescrito.<br/>";
    }

    public function metodoNormal() {
        echo "Este método sí puede ser sobrescrito.<br/>";
    }
}

class ClaseHijaDeFinal extends ClaseBaseConMetodoFinal {
    // public function metodoNoSobrescribible() {} // Error Fatal: Cannot override final method ClaseBaseConMetodoFinal::metodoNoSobrescribible()

    public function metodoNormal() {
        parent::metodoNormal(); // Llama al método del padre
        echo "Y la clase hija añade algo más.<br/>";
    }
}

$objFinalTest = new ClaseHijaDeFinal();
$objFinalTest->metodoNoSobrescribible();
$objFinalTest->metodoNormal();

echo "<p>La herencia es una herramienta poderosa para la reutilización de código y la creación de jerarquías de clases lógicas. Permite el polimorfismo (que se verá más adelante).</p>";

echo "<br/><hr/>Fin del script de herencia.<br/>";

// Los destructores de los objetos serán llamados aquí si están definidos.
// Vehiculo no tiene destructor explícito, Coche y Motocicleta tampoco (heredarían si Vehiculo tuviera uno y no fuera privado).
?>
