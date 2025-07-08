<?php
// TEMA: PROGRAMACIÓN ORIENTADA A OBJETOS (OOP) - Enlaces Estáticos Tardíos (Late Static Bindings)

echo "<h1>OOP: Enlaces Estáticos Tardíos (Late Static Bindings)</h1>";

// Los enlaces estáticos tardíos (PHP 5.3+) permiten que en los métodos estáticos
// y propiedades, la palabra clave `static::` se refiera a la clase
// a través de la cual se llamó realmente al método ("called class"),
// en lugar de a la clase donde se define el método (como en el caso de `self::`).

class LateStaticBindingBase {
    protected static $className = "LateStaticBindingBase";

    public static function getClassNameSelf() {
        // `self` siempre se refiere a la clase donde se define el método.
        return self::$className;
    }

    public static function getClassNameStatic() {
        // `static` se refiere a la clase a través de la cual se llamó al método.
        // Este es el "enlace tardío".
        return static::$className;
    }

    public static function printClassName() {
        echo "Resultado de self::getClassNameSelf(): " . self::getClassNameSelf() . "<br/>";
        echo "Resultado de static::getClassNameSelf(): " . static::getClassNameSelf() . " (aquí static es lo mismo que self, porque en getClassNameSelf() está self)<br/>";
        echo "Resultado de self::getClassNameStatic(): " . self::getClassNameStatic() . " (aquí self llama a getClassNameStatic() en el contexto de LateStaticBindingBase)<br/>";
        echo "Resultado de static::getClassNameStatic(): " . static::getClassNameStatic() . " (esto se referirá a la 'called class')<br/>";
        echo "<hr/>";
    }
}

class LateStaticBindingChild extends LateStaticBindingBase {
    protected static $className = "LateStaticBindingChild"; // Sobrescribe (override) la propiedad estática
}

class AnotherLateStaticBindingChild extends LateStaticBindingBase {
    // No sobrescribe la propiedad $className, por lo que heredará LateStaticBindingBase::$className,
    // pero en la llamada static::, la "called class" será AnotherLateStaticBindingChild.
    // Si un método hace referencia a static::$className, y no está definido aquí,
    // usará la propiedad $className de la clase padre,
    // pero static:: seguirá apuntando a AnotherLateStaticBindingChild como contexto.
}


echo "<h2>Comportamiento `self::` vs `static::`</h2>";

echo "<h3>Llamada directa a LateStaticBindingBase:</h3>";
echo "LateStaticBindingBase::getClassNameSelf(): " . LateStaticBindingBase::getClassNameSelf() . "<br/>";     // Resultado: LateStaticBindingBase
echo "LateStaticBindingBase::getClassNameStatic(): " . LateStaticBindingBase::getClassNameStatic() . "<br/>"; // Resultado: LateStaticBindingBase
LateStaticBindingBase::printClassName();
// Resultado de self::getClassNameSelf(): LateStaticBindingBase
// Resultado de static::getClassNameSelf(): LateStaticBindingBase
// Resultado de self::getClassNameStatic(): LateStaticBindingBase
// Resultado de static::getClassNameStatic(): LateStaticBindingBase

echo "<h3>Llamada a LateStaticBindingChild (donde \$className está sobrescrito):</h3>";
echo "LateStaticBindingChild::getClassNameSelf(): " . LateStaticBindingChild::getClassNameSelf() . "<br/>";     // Resultado: LateStaticBindingBase (porque getClassNameSelf está definido en LateStaticBindingBase con self::$className)
echo "LateStaticBindingChild::getClassNameStatic(): " . LateStaticBindingChild::getClassNameStatic() . "<br/>"; // Resultado: LateStaticBindingChild (porque static::$className se resuelve a $className de LateStaticBindingChild)
LateStaticBindingChild::printClassName(); // Se llama al método LateStaticBindingBase::printClassName(), pero en el contexto de LateStaticBindingChild
// Resultado de self::getClassNameSelf(): LateStaticBindingBase
// Resultado de static::getClassNameSelf(): LateStaticBindingBase (ya que getClassNameSelf() usa self)
// Resultado de self::getClassNameStatic(): LateStaticBindingBase (ya que en printClassName, self::getClassNameStatic() llama a getClassNameStatic en el contexto de LateStaticBindingBase, que usa static::$className, pero debido a self, $className es de LateStaticBindingBase)
// Resultado de static::getClassNameStatic(): LateStaticBindingChild (ya que en printClassName, static::getClassNameStatic() llama a getClassNameStatic en el contexto de LateStaticBindingChild, que usa static::$className, que se resuelve a $className de LateStaticBindingChild)


echo "<h3>Llamada a AnotherLateStaticBindingChild (donde \$className NO está sobrescrito):</h3>";
echo "AnotherLateStaticBindingChild::getClassNameSelf(): " . AnotherLateStaticBindingChild::getClassNameSelf() . "<br/>";     // Resultado: LateStaticBindingBase
echo "AnotherLateStaticBindingChild::getClassNameStatic(): " . AnotherLateStaticBindingChild::getClassNameStatic() . "<br/>"; // Resultado: LateStaticBindingBase (porque static::$className busca $className en el contexto de AnotherLateStaticBindingChild, no lo encuentra, por lo que usa LateStaticBindingBase::$className del padre)
AnotherLateStaticBindingChild::printClassName();
// Resultado de self::getClassNameSelf(): LateStaticBindingBase
// Resultado de static::getClassNameSelf(): LateStaticBindingBase
// Resultado de self::getClassNameStatic(): LateStaticBindingBase
// Resultado de static::getClassNameStatic(): LateStaticBindingBase

echo "<hr/>";

echo "<h2>Ejemplo práctico: Método de fábrica estático (Static Factory Method)</h2>";
abstract class UserTypeLSB {
    // Propiedad estática que los descendientes pueden sobrescribir
    protected static $tableName = "user_types_lsb";

    public static function create() {
        // `static` aquí se referirá a la clase realmente llamada (AdminUserLSB, ModeratorUserLSB)
        // y no a UserTypeLSB.
        echo "Creando un nuevo registro en la tabla `" . static::$tableName . "`.<br/>";
        // `new static()` aquí crearía una nueva instancia de la clase llamada (p. ej., new AdminUserLSB()).
        return new static();
    }

    public function getTableName() {
        // Desde un método de instancia también podemos referirnos a la propiedad estática de enlace tardío
        return static::$tableName;
    }

    // Constructor para que new static() funcione
    public function __construct() {
        echo "Nueva instancia de " . get_class($this) . " creada.<br/>";
    }
}

class AdminUserLSB extends UserTypeLSB {
    protected static $tableName = "admins_lsb"; // Sobrescribe la propiedad estática del padre
    public $level = "Completo";
}

class ModeratorUserLSB extends UserTypeLSB {
    protected static $tableName = "moderators_lsb";
    public $level = "Limitado";
}

class GuestUserLSB extends UserTypeLSB {
    // No sobrescribe $tableName, por lo que será "user_types_lsb".
    public $level = "Lectura";
}

echo "<h3>Llamada a Métodos de Fábrica Estáticos:</h3>";
$admin = AdminUserLSB::create();
// Salida: Creando un nuevo registro en la tabla `admins_lsb`.
// Salida: Nueva instancia de AdminUserLSB creada.
echo "Tabla Admin (desde método de instancia): " . $admin->getTableName() . " (" . $admin->level . ")<br/><br/>";


$moderator = ModeratorUserLSB::create();
// Salida: Creando un nuevo registro en la tabla `moderators_lsb`.
// Salida: Nueva instancia de ModeratorUserLSB creada.
echo "Tabla Moderador (desde método de instancia): " . $moderator->getTableName() . " (" . $moderator->level . ")<br/><br/>";

$guest = GuestUserLSB::create();
// Salida: Creando un nuevo registro en la tabla `user_types_lsb`.
// Salida: Nueva instancia de GuestUserLSB creada.
echo "Tabla Invitado (desde método de instancia): " . $guest->getTableName() . " (" . $guest->level . ")<br/><br/>";


echo "<p>Los enlaces estáticos tardíos son particularmente útiles al implementar patrones de diseño como Active Record o, en general, en casos donde en una jerarquía de herencia queremos que las llamadas estáticas se resuelvan en el contexto de la clase que llama, y no en el de la clase definitoria.</p>";
echo "<p>Esto proporciona mayor flexibilidad en el uso de métodos y propiedades estáticas durante la herencia.</p>";


echo "<br/><hr/>Fin del script de enlaces estáticos tardíos.<br/>";
?>
