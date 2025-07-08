<?php
// TEMA: PROGRAMACIÓN ORIENTADA A OBJETOS (POO) - Métodos Mágicos

echo "<h1>POO: Métodos Mágicos</h1>";

// Los métodos mágicos son métodos especiales en PHP que se ejecutan automáticamente
// en respuesta a ciertos eventos o acciones sobre un objeto.
// Se reconocen por su prefijo de doble guion bajo (__).
// Permiten personalizar el comportamiento de los objetos de formas poderosas.

// __construct() y __destruct() ya se vieron (creación y destrucción de objetos).

class ObjetoMagico {
    private $datos = [];
    public $nombrePublico;

    // --- __construct(): Se llama al crear el objeto ---
    public function __construct($nombre = "Objeto Sin Nombre") {
        $this->nombrePublico = $nombre;
        $this->datos['id_interno'] = rand(100, 999);
        echo "__construct(): Objeto '{$this->nombrePublico}' creado con ID interno {$this->datos['id_interno']}.<br/>";
    }

    // --- __destruct(): Se llama cuando el objeto va a ser destruido ---
    public function __destruct() {
        echo "__destruct(): Objeto '{$this->nombrePublico}' (ID: {$this->datos['id_interno']}) siendo destruido.<br/>";
        // Útil para liberar recursos, cerrar conexiones, etc.
    }

    // --- __set($nombre, $valor): Se llama al intentar escribir en una propiedad inaccesible (privada o no existente) ---
    public function __set($nombrePropiedad, $valor) {
        echo "__set(): Intentando establecer la propiedad inaccesible '{$nombrePropiedad}' a '{$valor}'.<br/>";
        $this->datos[$nombrePropiedad] = $valor; // Guardamos en el array $datos
    }

    // --- __get($nombre): Se llama al intentar leer una propiedad inaccesible ---
    public function __get($nombrePropiedad) {
        echo "__get(): Intentando acceder a la propiedad inaccesible '{$nombrePropiedad}'.<br/>";
        if (array_key_exists($nombrePropiedad, $this->datos)) {
            return $this->datos[$nombrePropiedad];
        }
        trigger_error("Propiedad inaccesible o no definida: {$nombrePropiedad}", E_USER_NOTICE);
        return null;
    }

    // --- __isset($nombre): Se llama cuando se usa isset() o empty() en una propiedad inaccesible ---
    public function __isset($nombrePropiedad) {
        echo "__isset(): Verificando si la propiedad inaccesible '{$nombrePropiedad}' está definida.<br/>";
        return isset($this->datos[$nombrePropiedad]);
    }

    // --- __unset($nombre): Se llama cuando se usa unset() en una propiedad inaccesible ---
    public function __unset($nombrePropiedad) {
        echo "__unset(): Intentando eliminar la propiedad inaccesible '{$nombrePropiedad}'.<br/>";
        if (isset($this->datos[$nombrePropiedad])) {
            unset($this->datos[$nombrePropiedad]);
        }
    }

    // --- __call($nombreMetodo, $argumentos): Se llama al intentar invocar un método inaccesible (privado o no existente) en contexto de objeto ---
    public function __call($nombreMetodo, $argumentos) {
        echo "__call(): Intentando llamar al método de objeto inaccesible '{$nombreMetodo}' con argumentos: (" . implode(", ", $argumentos) . ").<br/>";
        // Podríamos implementar aquí un sistema de "métodos dinámicos"
        if (strpos($nombreMetodo, 'get') === 0) {
            $prop = strtolower(substr($nombreMetodo, 3));
            if (isset($this->datos[$prop])) {
                return $this->datos[$prop];
            }
        }
        return "El método '{$nombreMetodo}' no existe o no es accesible.<br/>";
    }

    // --- __callStatic($nombreMetodo, $argumentos): Se llama al intentar invocar un método estático inaccesible ---
    public static function __callStatic($nombreMetodo, $argumentos) {
        echo "__callStatic(): Intentando llamar al método estático inaccesible '{$nombreMetodo}' con argumentos: (" . implode(", ", $argumentos) . ").<br/>";
        return "El método estático '{$nombreMetodo}' no existe o no es accesible.<br/>";
    }

    // --- __toString(): Se llama cuando se intenta tratar un objeto como un string ---
    // Debe retornar un string.
    public function __toString() {
        echo "__toString(): Objeto siendo tratado como string.<br/>";
        return "ObjetoMagico[nombre='{$this->nombrePublico}', datos=" . json_encode($this->datos) . "]";
    }

    // --- __invoke(...$argumentos): Se llama cuando se intenta llamar a un objeto como si fuera una función ---
    public function __invoke(...$argumentos) {
        echo "__invoke(): Objeto siendo invocado como función con argumentos: (" . implode(", ", $argumentos) . ").<br/>";
        return "Resultado de la invocación del objeto.";
    }

    // --- __clone(): Se llama después de que un objeto ha sido clonado ---
    // Útil para modificar propiedades del clon, por ejemplo, si alguna propiedad es una referencia a otro objeto.
    public function __clone() {
        echo "__clone(): Objeto '{$this->nombrePublico}' ha sido clonado.<br/>";
        // Si $this->datos contuviera objetos, podríamos necesitar clonarlos profundamente.
        // Por ejemplo, si $this->datos['config'] = new Configuracion();
        // $this->datos['config'] = clone $this->datos['config'];
        $this->datos['id_interno'] = rand(1000, 1999); // Dar un nuevo ID interno al clon
        $this->nombrePublico = $this->nombrePublico . " (Clon)";
        echo "__clone(): Nuevo ID interno del clon: {$this->datos['id_interno']}. Nuevo nombre: {$this->nombrePublico}.<br/>";
    }

    // --- __debugInfo(): Controla qué propiedades se muestran cuando se usa var_dump() sobre el objeto (PHP 5.6+) ---
    public function __debugInfo() {
        echo "__debugInfo(): Preparando información para var_dump().<br/>";
        return [
            'nombrePublico' => $this->nombrePublico,
            'info_privada' => 'Esto es un secreto (controlado por __debugInfo)',
            'datos_internos' => $this->datos,
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }

    // Otros métodos mágicos incluyen:
    // __sleep() y __wakeup(): Para serialización y deserialización.
    // __serialize() y __unserialize(): Alternativa más nueva a __sleep y __wakeup (PHP 7.4+).
    // __set_state(): Se llama para las clases exportadas por var_export().
    // __serialize(), __unserialize() (PHP 7.4+)
    // __isset_PROPERTY(), __get_PROPERTY(), __set_PROPERTY(), __unset_PROPERTY() (Property overloading con nombre específico, PHP 8.2+)
}

echo "<h2>Demostración de Métodos Mágicos</h2>";

$obj = new ObjetoMagico("Mi Demo");
echo "<hr/>";

// --- __set y __get ---
echo "<h3>__set y __get:</h3>";
$obj->ciudad = "Madrid"; // Llama a __set('ciudad', 'Madrid')
$obj->pais = "España";   // Llama a __set('pais', 'España')
echo "Ciudad: " . $obj->ciudad . "<br/>"; // Llama a __get('ciudad')
echo "País: " . $obj->pais . "<br/>";   // Llama a __get('pais')
echo "Propiedad no existente: " . $obj->provincia . "<br/>"; // Llama a __get('provincia'), devuelve null y lanza Notice
echo "<hr/>";

// --- __isset y __unset ---
echo "<h3>__isset y __unset:</h3>";
echo "isset(\$obj->ciudad): " . (isset($obj->ciudad) ? 'true' : 'false') . "<br/>"; // Llama a __isset('ciudad')
echo "isset(\$obj->codigoPostal): " . (isset($obj->codigoPostal) ? 'true' : 'false') . "<br/>"; // Llama a __isset('codigoPostal')
unset($obj->pais); // Llama a __unset('pais')
echo "isset(\$obj->pais) después de unset: " . (isset($obj->pais) ? 'true' : 'false') . "<br/>";
echo "<hr/>";

// --- __call ---
echo "<h3>__call:</h3>";
echo $obj->getDetalles("param1", 123) . "<br/>"; // Llama a __call('getDetalles', ['param1', 123])
echo $obj->getciudad() . "<br/>"; // __call lo manejará y devolverá el valor de 'ciudad' si existe en $datos
echo "<hr/>";

// --- __callStatic ---
echo "<h3>__callStatic:</h3>";
echo ObjetoMagico::metodoEstaticoInexistente("arg1", "arg2") . "<br/>"; // Llama a __callStatic(...)
echo "<hr/>";

// --- __toString ---
echo "<h3>__toString:</h3>";
echo "El objeto como string: " . $obj . "<br/>"; // Llama a __toString()
echo "<hr/>";

// --- __invoke ---
echo "<h3>__invoke:</h3>";
echo $obj("argA", "argB") . "<br/>"; // Llama a __invoke('argA', 'argB')
echo "<hr/>";

// --- __clone ---
echo "<h3>__clone:</h3>";
$objClonado = clone $obj;
echo "Nombre del objeto original: " . $obj->nombrePublico . ", ID: " . $obj->id_interno . "<br/>"; // id_interno es __get()
echo "Nombre del objeto clonado: " . $objClonado->nombrePublico . ", ID: " . $objClonado->id_interno . "<br/>";
$objClonado->ciudad = "Barcelona"; // __set() en el clon
echo "Ciudad del original: " . $obj->ciudad . "<br/>"; // Madrid
echo "Ciudad del clonado: " . $objClonado->ciudad . "<br/>"; // Barcelona
echo "<hr/>";

// --- __debugInfo ---
echo "<h3>__debugInfo (con var_dump):</h3>";
var_dump($obj);
echo "<br/>";
var_dump($objClonado);
echo "<hr/>";


echo "<p>Los métodos mágicos ofrecen una gran flexibilidad, pero deben usarse con cuidado.
Un uso excesivo o incorrecto puede hacer que el código sea más difícil de entender y depurar.</p>";

// __destruct() se llamará para $obj y $objClonado al final del script o cuando se eliminen.
unset($obj); // Forzar la llamada a __destruct() para $obj ahora
echo "Objeto \$obj ha sido explícitamente destruido (unset).<br/>";
// $objClonado se destruirá al final del script.

echo "<br/><hr/>Fin del script de métodos mágicos.<br/>";
?>
