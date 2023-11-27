<?php
/*
primer archivo 
realizado con php
*/

$mi_variable = "hola mundo \n";
$miVariable = "hello world";
echo $mi_variable;
$numero = 12;
$float = 12.5; // es de tipo entero
echo gettype($mi_variable) . "\n";
echo gettype($numero); 
echo "<hr>";
echo "<br/>";

$array = array();
$array = [1,2,6,6];
print_r($array);

if ($miVariable == "hello world"){
    echo "<br/>";
    echo $mi_variable;
}

foreach ($array as $key =>$value){
    echo $key."=>".$value."\n";
    echo "<br/>";
}

$cons = array();
$cons = [1,2,3,4,5];

?>