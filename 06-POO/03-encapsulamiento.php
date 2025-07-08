<?php
// TEMA: PROGRAMACIÓN ORIENTADA A OBJETOS (POO) - Encapsulamiento (Visibilidad)

echo "<h1>POO: Encapsulamiento (Modificadores de Visibilidad)</h1>";

// El encapsulamiento es el concepto de restringir el acceso directo a algunas
// de las propiedades y métodos de un objeto. Se controla mediante modificadores
// de visibilidad: public, protected, y private.

// - public:    Se puede acceder desde cualquier lugar (fuera de la clase, en la misma clase, y en clases hijas).
// - protected: Se puede acceder dentro de la misma clase y en clases que heredan de ella (clases hijas). No desde fuera.
// - private:   Solo se puede acceder desde dentro de la misma clase que lo definió. Ni clases hijas ni desde fuera.

class CuentaBancaria {
    // --- Propiedades ---
    public $titular;            // Cualquiera puede ver y modificar el titular
    private $saldo;             // Solo la clase CuentaBancaria puede acceder directamente al saldo
    protected $tipoCuenta;      // Accesible por CuentaBancaria y sus clases hijas
    private $numeroCuenta;      // Solo accesible por CuentaBancaria

    const TIPO_AHORRO = "Ahorro";
    const TIPO_CORRIENTE = "Corriente";

    public function __construct($titular, $saldoInicial, $tipo = self::TIPO_AHORRO) {
        $this->titular = $titular;

        // La validación del saldo inicial se hace aquí, dentro de la clase
        if ($saldoInicial >= 0) {
            $this->saldo = $saldoInicial;
        } else {
            $this->saldo = 0;
            echo "Advertencia: El saldo inicial no puede ser negativo. Se estableció a 0.<br/>";
        }

        $this->tipoCuenta = $tipo;
        $this->numeroCuenta = $this->generarNumeroCuenta(); // Método privado para lógica interna
        echo "Cuenta creada para {$this->titular} con saldo inicial: {$this->saldo} EUR. Tipo: {$this->tipoCuenta}. Número: {$this->numeroCuenta}<br/>";
    }

    // --- Métodos Públicos (Interfaz de la clase) ---

    // Getter para el saldo (permite leer el saldo de forma controlada)
    public function getSaldo() {
        // Podríamos añadir lógica aquí, como permisos antes de mostrar el saldo
        return $this->saldo;
    }

    public function depositar($monto) {
        if ($monto > 0) {
            $this->saldo += $monto;
            $this->registrarTransaccion("Depósito", $monto);
            echo "Depósito de {$monto} EUR realizado. Nuevo saldo: {$this->saldo} EUR.<br/>";
        } else {
            echo "El monto a depositar debe ser positivo.<br/>";
        }
    }

    public function retirar($monto) {
        if ($monto <= 0) {
            echo "El monto a retirar debe ser positivo.<br/>";
            return false;
        }
        if ($this->saldo >= $monto) {
            $this->saldo -= $monto;
            $this->registrarTransaccion("Retiro", $monto);
            echo "Retiro de {$monto} EUR realizado. Nuevo saldo: {$this->saldo} EUR.<br/>";
            return true;
        } else {
            echo "Saldo insuficiente para retirar {$monto} EUR. Saldo actual: {$this->saldo} EUR.<br/>";
            return false;
        }
    }

    public function getNumeroCuentaPublico() {
        // Ofrecemos una forma pública de obtener el número de cuenta,
        // aunque la propiedad $numeroCuenta sea privada.
        return $this->numeroCuenta;
    }

    // --- Métodos Protegidos ---
    // (Serían útiles para clases hijas que quieran extender la funcionalidad de transacciones)
    protected function registrarTransaccion($tipo, $monto) {
        // Simulación: en un caso real, esto guardaría en una BD o log.
        echo "<em>Transacción registrada: {$tipo} de {$monto} EUR para la cuenta {$this->numeroCuenta}.</em><br/>";
    }

    // --- Métodos Privados ---
    // (Funcionalidad interna de la clase)
    private function generarNumeroCuenta() {
        // Lógica interna para generar un número de cuenta único (simulación)
        return "ES" . rand(1000000000, 9999999999);
    }

    public function getTipoCuenta() {
        return $this->tipoCuenta;
    }
}

echo "<h2>Ejemplo con CuentaBancaria</h2>";
$miCuenta = new CuentaBancaria("Ana Pérez", 500, CuentaBancaria::TIPO_CORRIENTE);
$cuentaJuan = new CuentaBancaria("Juan Rodríguez", -100); // Prueba saldo negativo

echo "<br/>";

// Acceso a propiedad pública
echo "Titular de mi cuenta: " . $miCuenta->titular . "<br/>";
$miCuenta->titular = "Ana Pérez García"; // Modificación directa permitida
echo "Nuevo titular de mi cuenta: " . $miCuenta->titular . "<br/>";

// Intento de acceso a propiedad privada (generaría error)
// echo "Saldo directo: " . $miCuenta->saldo; // Fatal error: Uncaught Error: Cannot access private property CuentaBancaria::$saldo
// $miCuenta->saldo = 1000000; // Fatal error

// Acceso a través de métodos públicos (Getters/Setters indirectos)
echo "Saldo de Ana (vía getSaldo()): " . $miCuenta->getSaldo() . " EUR.<br/>";

$miCuenta->depositar(200);
$miCuenta->retirar(50);
$miCuenta->retirar(1000); // Intentar retirar más del saldo disponible

echo "Número de cuenta de Ana: " . $miCuenta->getNumeroCuentaPublico() . "<br/>";

// Intento de acceso a propiedad protected (generaría error desde fuera)
// echo "Tipo de cuenta directo: " . $miCuenta->tipoCuenta; // Fatal error: Uncaught Error: Cannot access protected property CuentaBancaria::$tipoCuenta

echo "<hr/>";

// --- Herencia y Encapsulamiento ---
class CuentaPremium extends CuentaBancaria {
    private $limiteSobregiro;

    public function __construct($titular, $saldoInicial, $limiteSobregiro) {
        parent::__construct($titular, $saldoInicial, "Premium"); // Llama al constructor padre
        $this->limiteSobregiro = $limiteSobregiro;
        echo "Cuenta Premium creada con límite de sobregiro: {$this->limiteSobregiro} EUR.<br/>";
    }

    // Sobrescribir el método retirar para permitir sobregiros
    public function retirar($monto) {
        if ($monto <= 0) {
            echo "El monto a retirar debe ser positivo.<br/>";
            return false;
        }
        // $this->saldo es private en CuentaBancaria, no podemos accederlo directamente.
        // Necesitamos usar getSaldo() o modificar CuentaBancaria para que $saldo sea protected.
        // ¡Vamos a asumir que para este ejemplo, CuentaBancaria::$saldo fuera protected!
        // Si $saldo fuera protected:
        /*
        if ($this->saldo + $this->limiteSobregiro >= $monto) {
            $this->saldo -= $monto; // Error si $saldo es private
            $this->registrarTransaccion("Retiro Premium", $monto); // registrarTransaccion es protected, se puede llamar.
            echo "Retiro Premium de {$monto} EUR. Nuevo saldo: {$this->saldo} EUR.<br/>";
            return true;
        } else {
            echo "Límite de sobregiro excedido. No se puede retirar {$monto} EUR.<br/>";
            return false;
        }
        */

        // FORMA CORRECTA SI $saldo ES PRIVADO (usando interfaz pública):
        $saldoActual = $this->getSaldo(); // Usamos el getter público
        if ($saldoActual + $this->limiteSobregiro >= $monto) {
            // Para modificar el saldo, necesitaríamos un método en la clase padre
            // que permita la modificación (ej. un `protected function modificarSaldo($nuevoSaldo)` )
            // o que `retirar` en el padre sea más flexible o `saldo` sea `protected`.
            // Simulación: Si tuviéramos un método `protected function _setSaldo($nuevoSaldo)` en CuentaBancaria:
            // $this->_setSaldo($saldoActual - $monto);

            // Por ahora, vamos a re-implementar la lógica de forma más simple,
            // pero esto ilustra la importancia de cómo se diseña la visibilidad.
            // Si CuentaBancaria::retirar() fuera suficiente, podríamos llamar parent::retirar()
            // o si necesitáramos modificar el saldo directamente y es privado, tendríamos un problema de diseño.

            // Para este ejemplo, vamos a usar el retirar del padre y manejar el caso especial.
            // Esta no es la mejor forma si la lógica de sobregiro es muy distinta.
            if ($saldoActual >= $monto) { // Si hay saldo suficiente, usa el método normal
                return parent::retirar($monto);
            } elseif ($saldoActual + $this->limiteSobregiro >= $monto) {
                // Lógica específica para sobregiro (simplificada)
                // Esto es una simplificación. En un caso real, la clase base debería ser más flexible
                // o la propiedad $saldo debería ser `protected`.
                // Aquí no podemos modificar $this->saldo directamente.
                echo "Retiro con sobregiro (simulado): {$monto} EUR. Se necesita modificar la clase base o saldo protected.<br/>";
                // $this->saldo -= $monto; // ¡Esto seguiría siendo un error si saldo es private!
                // Para que esto funcione, CuentaBancaria necesitaría un método `protected function _ajustarSaldo($diferencia)`
                // o hacer `saldo` protected.
                // Por ahora, este método no puede realmente cambiar el saldo si es privado.
                // Vamos a enfocarnos en el acceso a `tipoCuenta` que sí es `protected`.
                $this->registrarTransaccion("Retiro Premium con Sobregiro (simulado)", $monto);
                return true; // Simulación
            } else {
                 echo "Límite de sobregiro excedido. No se puede retirar {$monto} EUR.<br/>";
                 return false;
            }

        } else {
             echo "Monto de retiro inválido.<br/>";
             return false;
        }
    }

    public function getDetallesCuentaPremium() {
        // $this->tipoCuenta es protected, por lo que es accesible desde la clase hija.
        return "Titular: {$this->titular}, Tipo: {$this->tipoCuenta}, Saldo: {$this->getSaldo()} EUR, Límite Sobregiro: {$this->limiteSobregiro} EUR.";
    }

    // Intento de acceder a una propiedad privada de la clase padre (no funcionará)
    public function intentarAccederNumeroCuentaPadre() {
        // return $this->numeroCuenta; // Fatal error: Uncaught Error: Cannot access private property CuentaBancaria::$numeroCuenta
        return "No se puede acceder a numeroCuenta directamente.";
    }
}

echo "<h2>Herencia y Encapsulamiento con CuentaPremium</h2>";
$cuentaVip = new CuentaPremium("Laura VIP", 1000, 500);
echo $cuentaVip->getDetallesCuentaPremium() . "<br/>";

$cuentaVip->depositar(100); // Usa el método depositar() heredado
$cuentaVip->retirar(1200); // Prueba el método retirar() sobrescrito (con lógica simulada de sobregiro)
// Salida esperada (si $saldo fuera protected o tuviéramos _setSaldo):
// "Retiro Premium de 1200 EUR. Nuevo saldo: -100 EUR."
// Salida actual (con $saldo private y simulación):
// "Retiro con sobregiro (simulado): 1200 EUR..."

echo $cuentaVip->intentarAccederNumeroCuentaPadre() . "<br/>"; // No se puede acceder...
echo "Número de cuenta de Laura (vía método público del padre): " . $cuentaVip->getNumeroCuentaPublico() . "<br/>";


echo "<p><strong>Beneficios del Encapsulamiento:</strong><br/>
- <strong>Control:</strong> La clase controla cómo se accede y se modifican sus datos. Permite validaciones.
- <strong>Seguridad:</strong> Protege los datos internos de modificaciones accidentales o maliciosas desde fuera.
- <strong>Flexibilidad y Mantenimiento:</strong> Se puede cambiar la implementación interna de la clase (ej. cómo se almacena el saldo) sin afectar el código que la utiliza, siempre que la interfaz pública (métodos públicos) se mantenga.
- <strong>Abstracción:</strong> Oculta la complejidad interna y expone solo lo necesario.
</p>";

echo "<br/><hr/>Fin del script de encapsulamiento.<br/>";
?>
