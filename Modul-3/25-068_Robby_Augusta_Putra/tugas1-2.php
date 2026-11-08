<?php

$fruits = array("Avocado","Blueberry","Cherry");

$fruits[] = "Durian";
$fruits[] = "Elderberry";
$fruits[] = "Fig";
$fruits[] = "Grape";
$fruits[] = "Honeydew";

echo 'Data '. $fruits[1]. ' dihapus<br>';
unset($fruits[1]);

$fruits = array_values($fruits);
echo 'fruits = ( ';
for ($i = 0; $i < count($fruits); $i++) {
    echo '"' . $fruits[$i] . '"';
    if ($i < count($fruits) - 1) {
        echo ', ';
    }
}
echo ')<br>';

echo '<br>Nilai dengan indeks tertinggi : '. $fruits[count($fruits)-1];		