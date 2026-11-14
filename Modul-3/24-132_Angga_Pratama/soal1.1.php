<?php
$fruits = array("Avocado", "Blueberry", "Cherry");

$fruits[] = "Durian";
$fruits[] = "Elderberry";
$fruits[] = "Fig";
$fruits[] = "Grape";
$fruits[] = "Honeydew";

$akhir = count($fruits);

echo "fruits = (";
foreach ($fruits as $buah) {
    echo $buah . ", ";
}
echo ")<br>";

echo "Nilai dengan indeks tertinggi: " . $fruits[$akhir - 1];
?>