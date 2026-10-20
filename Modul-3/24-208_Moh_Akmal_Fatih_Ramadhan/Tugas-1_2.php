<?php

$fruits = array(
    "Avocado",
    "Blueberry",
    "Cherry",
    "Durian",
    "Elderberry",
    "Fig",
    "Grape",
    "Honeydew"
);

unset($fruits[1]);

echo "Data Blueberry dihapus.<br>";

echo 'fruits = ( "' . implode('", "', $fruits) . '" )';
echo "<br>";

echo "Nilai dengan indeks tertinggi: " . end($fruits);

?>
