<?php
$fruits = array("Avocado", "Blueberry", "Cherry");

for ($x = 0; $x < 5; $x++) {
    $fruits[] = "Buah Tambahan " . ($x + 1);
}

$arrlength = count($fruits);
echo "Panjang array: " . $arrlength . "<br>";
for ($x = 0; $x < $arrlength; $x++) {
    echo $fruits[$x];
    echo "<br>";
}