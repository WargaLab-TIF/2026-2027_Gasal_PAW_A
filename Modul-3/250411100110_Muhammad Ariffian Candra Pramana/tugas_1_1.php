<?php 

$fruits = array("Avocado","Blueberry","Cherry");
$fruits[] = "Durian";
$fruits[] = "Elderberry";
$fruits[] = "Fig";
$fruits[] = "Grape";
$fruits[] = "Honeydew";

print_r($fruits);

$idx_tertinggi = count($fruits) - 1;
echo "<br>";
echo "Nilai dengan indeks tertinggi adalah: " . $fruits[$idx_tertinggi];

?>