<?php

echo "<b> 2.1 </b> <br>";

$fruits = array("Avocado","Blueberry","Cherry");
$tambahan = array("Apple","Banana","Mango","Grape","Durian");
$arrlenght = count($fruits);

for ($i = 0; $i < count($tambahan); $i++) {
    $fruits[] = $tambahan[$i];
}

$arrlenght = count($fruits);

echo "Panjang array saat ini : " . $arrlenght . "<br>"; 

for ($i = 0; $i < $arrlenght; $i++) {
    echo $fruits[$i] ;
    echo "<br>";
}

echo "<br>";
echo "<br>";
echo "<br>";

echo "<b> 2.2 </b> <br>";

$vegies = array("Carrot","Broccoli","Spinach");
$arraylenght = count($vegies);

for ($i = 0; $i < $arraylenght; $i++) {
    echo $vegies[$i] ;
    echo "<br>";
}