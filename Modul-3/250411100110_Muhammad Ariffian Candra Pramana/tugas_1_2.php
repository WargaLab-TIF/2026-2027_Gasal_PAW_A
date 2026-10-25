<?php 

$fruits = array("Avocado","Blueberry","Cherry");
$fruits[] = "Durian";
$fruits[] = "Elderberry";
$fruits[] = "Fig";
$fruits[] = "Grape";
$fruits[] = "Honeydew";


unset($fruits[1]);
$idx_tertinggi = count($fruits) - 1;
print_r($fruits);
$fruits_baru = array_values($fruits);
echo "<br>";
echo "Nilai dengan indeks tertinggi adalah: " . $fruits_baru[$idx_tertinggi];

?>