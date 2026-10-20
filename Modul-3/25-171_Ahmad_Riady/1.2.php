<?php
$fruits = array("Avocado", "Blueberry", "Cherry");

$fruits[] = "Durian";
$fruits[] = "Elderberry";
$fruits[] = "Fig";
$fruits[] = "Grape";
$fruits[] = "Honeydew";

unset($fruits[1]);

echo "fruits = (";
$indeks = array_keys($fruits);
for ($i = 0; $i < count($indeks); $i++){
    echo '"'.$fruits[$indeks[$i]].'"';
    if ($i < count($indeks) - 1){
        echo ", ";
    }
}
echo ")<br>";
$tertinggi = 0;
for ($i = 0; $i < count($indeks); $i++){
    if ($indeks[$i] > $tertinggi){
        $tertinggi = $indeks[$i];
    }
}
echo "Indeks tertinggi = " . $fruits[$tertinggi];