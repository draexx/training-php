<?php
/*
Archivo de introducción a PHP.
Muestra la declaración de variables, tipos de datos básicos,
estructuras de control simples y cómo imprimir en pantalla.
*/

// Declaración de variables de tipo string
$saludoEspanol = "Hola Mundo \n"; // String con salto de línea al final
$saludoIngles = 'Hello World';   // String con comillas simples

// Imprimir una variable
echo $saludoEspanol;

// Declaración de variables numéricas
$numeroEntero = 12;
$numeroFlotante = 12.5; // PHP maneja esto como float o double

// Obtener y mostrar el tipo de una variable
echo 'Tipo de $saludoEspanol: ' . gettype($saludoEspanol) . "<br/>\n";
echo 'Tipo de $numeroEntero: ' . gettype($numeroEntero) . "<br/>\n";
echo 'Tipo de $numeroFlotante: ' . gettype($numeroFlotante) . "<br/>\n";

echo "<hr>"; // Separador HTML

// Declaración de un array (forma corta)
$miArray = [1, 2, 6, 6, 'texto'];
echo "Contenido del array: <br/>\n";
print_r($miArray); // print_r es útil para mostrar información legible sobre una variable, especialmente arrays
echo "<br/>";

// Estructura condicional if
if ($saludoIngles == 'Hello World') {
    echo "<br/>El saludo en inglés es correcto: " . $saludoIngles . "<br/>\n";
} else {
    echo "<br/>El saludo en inglés no es 'Hello World'.<br/>\n";
}

// Bucle foreach para recorrer un array
echo "<br/>Recorriendo el array con foreach:<br/>\n";
foreach ($miArray as $indice => $valor) {
    echo "Índice: " . $indice . " => Valor: " . $valor . "<br/>\n";
}

// Ejemplo de una variable que se declara pero no se utiliza (para demostrar)
// $variable_no_utilizada = [1, 2, 3, 4, 5];
// Es una buena práctica eliminar variables no utilizadas para mantener el código limpio.

echo "<br/>Fin del script de introducción.<br/>\n";

?>