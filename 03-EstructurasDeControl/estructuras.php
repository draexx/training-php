<?php
// TEMA: ESTRUCTURAS DE CONTROL EN PHP

echo "<h1>Estructuras de Control en PHP</h1>";

// ========= CONDICIONALES =========
echo "<h2>Condicionales</h2>";

// --- if, else, elseif ---
echo "<h3>if, else, elseif</h3>";
$edad = 20;
$tieneLicencia = true;

echo "Edad: " . $edad . "<br/>";
echo "Tiene licencia: " . ($tieneLicencia ? 'Sí' : 'No') . "<br/>";

if ($edad >= 18 && $tieneLicencia) {
    echo "Puede conducir.<br/>";
} elseif ($edad >= 18 && !$tieneLicencia) {
    echo "Es mayor de edad, pero necesita una licencia para conducir.<br/>";
} else {
    echo "No puede conducir (es menor de edad).<br/>";
}

$nota = 75;
echo "<br/>Nota: " . $nota . "<br/>";
if ($nota >= 90) {
    echo "Calificación: A<br/>";
} elseif ($nota >= 80) {
    echo "Calificación: B<br/>";
} elseif ($nota >= 70) {
    echo "Calificación: C<br/>";
} elseif ($nota >= 60) {
    echo "Calificación: D<br/>";
} else {
    echo "Calificación: F (Reprobado)<br/>";
}

// --- switch ---
echo "<h3>switch</h3>";
$diaSemana = "martes";
echo "Día de la semana: " . ucfirst($diaSemana) . "<br/>"; // ucfirst pone la primera letra en mayúscula

switch (strtolower($diaSemana)) { // strtolower para comparar en minúsculas
    case "lunes":
        echo "Hoy es el inicio de la semana laboral.<br/>";
        break;
    case "martes":
    case "miércoles":
    case "jueves":
        echo "A mitad de semana, ¡ánimo!<br/>";
        break;
    case "viernes":
        echo "¡Casi fin de semana!<br/>";
        break;
    case "sábado":
    case "domingo":
        echo "¡Es fin de semana!<br/>";
        break;
    default:
        echo "Ese no es un día válido de la semana.<br/>";
        break;
}

$valor = 2;
switch ($valor) {
    case 1:
        echo "El valor es uno.<br/>";
        break;
    case 2:
        echo "El valor es dos.<br/>";
        // Sin break aquí, pasará al siguiente case si no hay un default que lo detenga antes.
    case 3:
        echo "El valor es dos o tres (cayó desde el case 2).<br/>";
        break;
    default:
        echo "Valor no reconocido.<br/>";
}


// ========= BUCLES =========
echo "<h2>Bucles</h2>";

// --- for ---
echo "<h3>for</h3>";
echo "Contando del 1 al 5:<br/>";
for ($i = 1; $i <= 5; $i++) {
    echo $i . " ";
}
echo "<br/>";

echo "Tabla de multiplicar del 3:<br/>";
for ($i = 1; $i <= 10; $i++) {
    echo "3 x " . $i . " = " . (3 * $i) . "<br/>";
}

// --- while ---
echo "<h3>while</h3>";
$contador = 1;
echo "Contando del 1 al 5 con while:<br/>";
while ($contador <= 5) {
    echo $contador . " ";
    $contador++;
}
echo "<br/>";

// --- do-while ---
// La diferencia principal es que el bloque de código se ejecuta al menos una vez.
echo "<h3>do-while</h3>";
$contadorDo = 6; // Incluso si la condición es falsa al inicio
echo "ContadorDo inicial: " . $contadorDo . "<br/>";
do {
    echo "Dentro del do-while. Contador: " . $contadorDo . "<br/>";
    $contadorDo++;
} while ($contadorDo <= 5);
echo "ContadorDo final: " . $contadorDo . "<br/>";

$contadorDo2 = 1;
echo "Contando del 1 al 3 con do-while:<br/>";
do {
    echo $contadorDo2 . " ";
    $contadorDo2++;
} while ($contadorDo2 <=3);
echo "<br/>";


// --- foreach ---
// Especialmente útil para recorrer arrays y objetos.
echo "<h3>foreach</h3>";
$colores = ["Rojo", "Verde", "Azul", "Amarillo"];
echo "Colores en el array:<br/>";
foreach ($colores as $color) {
    echo $color . "<br/>";
}

$estudiante = [
    "nombre" => "Laura",
    "edad" => 22,
    "curso" => "PHP Avanzado"
];
echo "<br/>Datos del estudiante (array asociativo):<br/>";
foreach ($estudiante as $clave => $valor) {
    echo ucfirst($clave) . ": " . $valor . "<br/>";
}


// --- break y continue ---
echo "<h3>break y continue</h3>";

echo "Uso de 'break' (detener el bucle cuando i es 5):<br/>";
for ($i = 1; $i <= 10; $i++) {
    if ($i == 5) {
        echo "Se encontró el 5, saliendo del bucle...<br/>";
        break; // Termina el bucle for actual
    }
    echo $i . " ";
}
echo "<br/>";

echo "<br/>Uso de 'continue' (saltar la iteración cuando i es 3):<br/>";
for ($i = 1; $i <= 5; $i++) {
    if ($i == 3) {
        echo "(Saltando el 3) ";
        continue; // Salta el resto del código en esta iteración y va a la siguiente
    }
    echo $i . " ";
}
echo "<br/>";

echo "<br/><hr/>Fin del script de estructuras de control.<br/>";
?>
