<?php
// TEMA: ARRAYS EN PHP

echo "<h1>Arrays en PHP</h1>";

// ========= CREACIÓN DE ARRAYS =========
echo "<h2>Creación de Arrays</h2>";

// --- Arrays Indexados (Índices numéricos) ---
echo "<h3>Arrays Indexados</h3>";
// Forma tradicional (antes de PHP 5.4)
$frutasVersionAntigua = array("Manzana", "Banana", "Naranja");

// Forma corta (desde PHP 5.4) - Recomendada
$frutas = ["Manzana", "Banana", "Naranja", "Pera"];
echo "Array de frutas (forma corta): ";
print_r($frutas);
echo "<br/>";

// Acceso a elementos por índice (base 0)
echo "La primera fruta es: " . $frutas[0] . "<br/>"; // Manzana
echo "La tercera fruta es: " . $frutas[2] . "<br/>"; // Naranja

// Modificar un elemento
$frutas[1] = "Plátano"; // Banana -> Plátano
echo "Array de frutas modificado: ";
print_r($frutas);
echo "<br/>";

// Agregar un elemento al final (índice automático)
$frutas[] = "Mango";
echo "Array con 'Mango' agregado: ";
print_r($frutas);
echo "<br/>";

// --- Arrays Asociativos (Índices con nombre/string) ---
echo "<h3>Arrays Asociativos</h3>";
// Forma tradicional
$personaVersionAntigua = array(
    "nombre" => "Juan",
    "edad" => 30,
    "ciudad" => "Madrid"
);

// Forma corta
$datosPersona = [
    "nombre" => "Ana",
    "edad" => 28,
    "profesion" => "Desarrolladora",
    "ciudad" => "Barcelona"
];
echo "Array asociativo de persona: ";
print_r($datosPersona);
echo "<br/>";

// Acceso a elementos por clave
echo "Nombre: " . $datosPersona["nombre"] . "<br/>";
echo "Profesión: " . $datosPersona["profesion"] . "<br/>";

// Modificar un elemento
$datosPersona["edad"] = 29;
echo "Edad modificada: " . $datosPersona["edad"] . "<br/>";

// Agregar un nuevo elemento
$datosPersona["pais"] = "España";
echo "Array de persona con país agregado: ";
print_r($datosPersona);
echo "<br/>";

// --- Arrays Multidimensionales ---
// Arrays que contienen otros arrays.
echo "<h3>Arrays Multidimensionales</h3>";
$matriz = [
    [1, 2, 3],
    [4, 5, 6],
    [7, 8, 9]
];
echo "Elemento [1][1] de la matriz: " . $matriz[1][1] . "<br/>"; // 5

$estudiantes = [
    [
        "nombre" => "Carlos",
        "notas" => ["matematicas" => 8, "historia" => 7]
    ],
    [
        "nombre" => "Laura",
        "notas" => ["matematicas" => 9, "historia" => 8]
    ]
];
echo "Nota de matemáticas de Laura: " . $estudiantes[1]["notas"]["matematicas"] . "<br/>"; // 9
echo "Nombre del primer estudiante: " . $estudiantes[0]["nombre"] . "<br/>"; // Carlos


// ========= RECORRER ARRAYS =========
echo "<h2>Recorrer Arrays</h2>";

// --- foreach (la forma más común y recomendada) ---
echo "<h3>foreach</h3>";
echo "Recorriendo \$frutas:<br/>";
foreach ($frutas as $fruta) {
    echo htmlspecialchars($fruta) . "<br/>";
}

echo "<br/>Recorriendo \$datosPersona (clave y valor):<br/>";
foreach ($datosPersona as $clave => $valor) {
    echo htmlspecialchars(ucfirst($clave)) . ": " . htmlspecialchars($valor) . "<br/>";
}

echo "<br/>Recorriendo \$estudiantes (multidimensional):<br/>";
foreach ($estudiantes as $indiceEstudiante => $datosEst) {
    echo "Estudiante " . ($indiceEstudiante + 1) . ": " . htmlspecialchars($datosEst['nombre']) . "<br/>";
    foreach ($datosEst['notas'] as $materia => $nota) {
        echo " - " . htmlspecialchars(ucfirst($materia)) . ": " . htmlspecialchars($nota) . "<br/>";
    }
}

// --- for (generalmente para arrays indexados con índices secuenciales) ---
echo "<h3>for (para arrays indexados)</h3>";
$listaNumeros = [10, 20, 30, 40, 50];
$cantidadNumeros = count($listaNumeros); // Es más eficiente obtener la cuenta una vez
echo "Recorriendo \$listaNumeros con for:<br/>";
for ($i = 0; $i < $cantidadNumeros; $i++) {
    echo "Índice " . $i . ": " . $listaNumeros[$i] . "<br/>";
}


// ========= FUNCIONES COMUNES PARA ARRAYS =========
echo "<h2>Funciones Comunes para Arrays</h2>";

// --- count() / sizeof() ---
echo "<h3>count() / sizeof()</h3>";
echo "Número de frutas: " . count($frutas) . "<br/>";
echo "Número de propiedades en datosPersona: " . sizeof($datosPersona) . "<br/>"; // sizeof es un alias de count

// --- print_r() y var_dump() ---
// Ya se han usado, son para mostrar información legible del array.
// var_dump() da más información, incluyendo tipos de datos.
echo "<h3>var_dump() de \$frutas</h3>";
var_dump($frutas);
echo "<br/>";

// --- array_push() y array_pop() ---
echo "<h3>array_push() y array_pop()</h3>";
$pila = ["A", "B", "C"];
echo "Pila inicial: " . implode(", ", $pila) . "<br/>";
array_push($pila, "D", "E"); // Agrega elementos al final
echo "Pila después de push: " . implode(", ", $pila) . "<br/>";
$elementoExtraido = array_pop($pila); // Extrae y retorna el último elemento
echo "Elemento extraído con pop: " . $elementoExtraido . "<br/>";
echo "Pila después de pop: " . implode(", ", $pila) . "<br/>";

// --- array_unshift() y array_shift() ---
echo "<h3>array_unshift() y array_shift()</h3>";
$cola = ["Primero", "Segundo"];
echo "Cola inicial: " . implode(", ", $cola) . "<br/>";
array_unshift($cola, "Nuevo Primero", "Otro Nuevo Primero"); // Agrega elementos al inicio
echo "Cola después de unshift: " . implode(", ", $cola) . "<br/>";
$elementoQuitado = array_shift($cola); // Quita y retorna el primer elemento
echo "Elemento quitado con shift: " . $elementoQuitado . "<br/>";
echo "Cola después de shift: " . implode(", ", $cola) . "<br/>";

// --- in_array() ---
echo "<h3>in_array()</h3>";
if (in_array("Manzana", $frutas)) {
    echo "Manzana está en el array de frutas.<br/>";
} else {
    echo "Manzana NO está en el array de frutas.<br/>";
}
// El tercer parámetro opcional activa la comparación estricta (valor y tipo)
$mix = [1, "2", 3];
echo "Buscando '1' (string) en \$mix (sin strict): " . (in_array("1", $mix) ? 'Encontrado' : 'No encontrado') . "<br/>";
echo "Buscando '1' (string) en \$mix (con strict): " . (in_array("1", $mix, true) ? 'Encontrado' : 'No encontrado') . "<br/>";

// --- array_key_exists() / key_exists() ---
echo "<h3>array_key_exists()</h3>";
if (array_key_exists("profesion", $datosPersona)) {
    echo "La clave 'profesion' existe en el array datosPersona.<br/>";
}
// También se puede usar isset() para verificar si una clave existe Y no es null.
// if (isset($datosPersona['profesion'])) { ... }

// --- array_keys() y array_values() ---
echo "<h3>array_keys() y array_values()</h3>";
$clavesPersona = array_keys($datosPersona);
echo "Claves del array datosPersona: ";
print_r($clavesPersona);
echo "<br/>";

$valoresPersona = array_values($datosPersona);
echo "Valores del array datosPersona: ";
print_r($valoresPersona);
echo "<br/>";

// --- sort(), rsort(), asort(), ksort(), arsort(), krsort() ---
echo "<h3>Funciones de Ordenamiento</h3>";
$numerosDesordenados = [5, 1, 8, 2, 4];
echo "Números desordenados: " . implode(", ", $numerosDesordenados) . "<br/>";
sort($numerosDesordenados); // Ordena ascendentemente, reindexa
echo "Números ordenados con sort(): " . implode(", ", $numerosDesordenados) . "<br/>";
print_r($numerosDesordenados); // Muestra los nuevos índices
echo "<br/>";

$frutasParaOrdenar = ["c" => "Cereza", "a" => "Albaricoque", "b" => "Banana"];
echo "Frutas (asociativo) desordenadas: "; print_r($frutasParaOrdenar); echo "<br/>";
asort($frutasParaOrdenar); // Ordena por valor, mantiene asociación de claves
echo "Frutas ordenadas por valor con asort(): "; print_r($frutasParaOrdenar); echo "<br/>";
ksort($frutasParaOrdenar); // Ordena por clave, mantiene asociación
echo "Frutas ordenadas por clave con ksort(): "; print_r($frutasParaOrdenar); echo "<br/>";
// rsort (descendente), arsort (descendente por valor), krsort (descendente por clave)

// --- array_merge() ---
echo "<h3>array_merge()</h3>";
$array1 = ["a" => "rojo", "b" => "verde"];
$array2 = ["b" => "azul", "c" => "amarillo", "d" => "negro"];
$fusionado = array_merge($array1, $array2);
echo "Array1: "; print_r($array1); echo "<br/>";
echo "Array2: "; print_r($array2); echo "<br/>";
echo "Fusión (array_merge): "; print_r($fusionado); echo "<br/>";
// Para claves numéricas, reindexa. Para claves string, la última aparición sobreescribe.

$arrNum1 = [1, 2, 3];
$arrNum2 = [3, 4, 5];
$fusionNum = array_merge($arrNum1, $arrNum2);
echo "Fusión numérica: "; print_r($fusionNum); echo "<br/>"; // [1, 2, 3, 3, 4, 5]

// --- array_slice() ---
echo "<h3>array_slice()</h3>";
$letras = ['a', 'b', 'c', 'd', 'e', 'f'];
$porcion = array_slice($letras, 2, 3); // Desde índice 2, tomar 3 elementos
echo "Porción de letras (índice 2, longitud 3): " . implode(", ", $porcion) . "<br/>"; // c, d, e
$porcionHastaFinal = array_slice($letras, 3); // Desde índice 3 hasta el final
echo "Porción de letras (índice 3 hasta final): " . implode(", ", $porcionHastaFinal) . "<br/>"; // d, e, f

// --- array_splice() ---
// Elimina una porción de un array y opcionalmente la reemplaza con otros elementos. Modifica el array original.
echo "<h3>array_splice()</h3>";
$coloresOriginales = ["rojo", "verde", "azul", "amarillo", "morado"];
echo "Colores originales: " . implode(", ", $coloresOriginales) . "<br/>";
// Eliminar 2 elementos desde el índice 1
$eliminados = array_splice($coloresOriginales, 1, 2);
echo "Colores después de eliminar (índice 1, 2 elem.): " . implode(", ", $coloresOriginales) . "<br/>";
echo "Elementos eliminados: " . implode(", ", $eliminados) . "<br/>";

$coloresOriginales2 = ["rojo", "verde", "azul", "amarillo", "morado"];
// Eliminar 1 elemento desde el índice 2 y reemplazarlo con "naranja" y "rosa"
$eliminados2 = array_splice($coloresOriginales2, 2, 1, ["naranja", "rosa"]);
echo "Colores después de reemplazar (índice 2, 1 elem. por 'naranja', 'rosa'): " . implode(", ", $coloresOriginales2) . "<br/>";
echo "Elemento eliminado: " . implode(", ", $eliminados2) . "<br/>";


// --- implode() y explode() ---
echo "<h3>implode() y explode()</h3>";
// implode: Une elementos de un array en un string
$palabras = ["Hola", "mundo", "desde", "PHP"];
$frase = implode(" ", $palabras); // Une con un espacio
echo "Frase desde array (implode): " . $frase . "<br/>";

// explode: Divide un string en un array basado en un delimitador
$csvString = "manzana,banana,naranja,uva";
$frutasDesdeString = explode(",", $csvString);
echo "Array desde string CSV (explode): ";
print_r($frutasDesdeString);
echo "<br/>";


// --- array_map() ---
// Aplica una función callback a cada elemento de un array.
echo "<h3>array_map()</h3>";
$numerosParaMap = [1, 2, 3, 4, 5];
function cuadrado($n) {
    return $n * $n;
}
$cuadrados = array_map("cuadrado", $numerosParaMap);
echo "Cuadrados (con función nombrada): " . implode(", ", $cuadrados) . "<br/>";

$mayusculas = array_map('strtoupper', $frutas);
echo "Frutas en mayúsculas (con 'strtoupper'): " . implode(", ", $mayusculas) . "<br/>";

$precios = [10.5, 20.0, 5.75];
$preciosConIVA = array_map(function($precio) {
    return $precio * 1.21; // Suponiendo IVA del 21%
}, $precios);
echo "Precios con IVA (con closure): ";
foreach($preciosConIVA as $p) { printf("%.2f ", $p); } // Formatear a 2 decimales
echo "<br/>";


// --- array_filter() ---
// Filtra elementos de un array usando una función callback.
echo "<h3>array_filter()</h3>";
$numerosParaFilter = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
function esPar($n) {
    return $n % 2 == 0;
}
$pares = array_filter($numerosParaFilter, "esPar");
echo "Números pares (con función nombrada): " . implode(", ", $pares) . "<br/>";

$impares = array_filter($numerosParaFilter, function($n) {
    return $n % 2 != 0;
});
echo "Números impares (con closure): " . implode(", ", $impares) . "<br/>";
// Nota: array_filter preserva las claves originales. Usar array_values($pares) si se necesita reindexar.

// --- array_reduce() ---
// Reduce iterativamente un array a un solo valor usando una función callback.
echo "<h3>array_reduce()</h3>";
$items = [1, 2, 3, 4, 5];
function sumar($acarreo, $item) {
    $acarreo += $item;
    return $acarreo;
}
$sumaTotal = array_reduce($items, "sumar", 0); // 0 es el valor inicial del acarreo
echo "Suma total de items (con función nombrada): " . $sumaTotal . "<br/>";

$strings = ["Hola", " ", "Mundo", "!"];
$concatenado = array_reduce($strings, function($carry, $item) {
    return $carry . $item;
}, "");
echo "Strings concatenados (con closure): " . $concatenado . "<br/>";


// --- Desestructuración de arrays (desde PHP 7.1) ---
echo "<h3>Desestructuración de Arrays (list() y [])</h3>";
$coordenadas = [10, 20, 30];

// Usando list()
list($x, $y, $z) = $coordenadas;
echo "Usando list(): x=$x, y=$y, z=$z<br/>";

// Usando la sintaxis corta []
[$lat, $lon, $alt] = $coordenadas;
echo "Usando []: lat=$lat, lon=$lon, alt=$alt<br/>";

// Para arrays asociativos
$usuario = ["id" => 1, "nombre" => "David", "email" => "david@example.com"];
['nombre' => $nombreUsuario, 'email' => $emailUsuario] = $usuario;
echo "Desestructuración asociativa: Nombre=$nombreUsuario, Email=$emailUsuario<br/>";

// Omitir elementos
[$primero, , $tercero] = $coordenadas;
echo "Omitiendo el segundo: primero=$primero, tercero=$tercero<br/>";


// --- Operador Spread (...) en arrays (desde PHP 7.4) ---
echo "<h3>Operador Spread (...)</h3>";
$parte1 = ["a", "b"];
$parte2 = ["c", "d"];
$combinadoSpread = [...$parte1, ...$parte2, "e"];
echo "Combinado con spread: "; print_r($combinadoSpread); echo "<br/>"; // ['a', 'b', 'c', 'd', 'e']

function obtenerArgumentos(...$args) { // Argumentos variables (variadics)
    echo "Argumentos con spread en función: ";
    print_r($args);
    echo "<br/>";
}
obtenerArgumentos(1, 2, 3, "hola");

$numerosParaSumar = [10, 20, 30];
// Desempaquetar un array en argumentos de una función
$sumaConSpread = array_sum([...$numerosParaSumar]); // array_sum espera múltiples argumentos o un array
echo "Suma con spread: " . $sumaConSpread . "<br/>";


echo "<br/><hr/>Fin del script de arrays.<br/>";
?>
