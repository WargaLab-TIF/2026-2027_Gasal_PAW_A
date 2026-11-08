<?php
// Membuat array baru bernama $vegies dengan tiga data
$vegies = array("Carrot", "Broccoli", "Spinach");

// Menghitung panjang array $vegies
$arrlength = count($vegies);

// Menampilkan seluruh data dengan perulangan for
for($x = 0; $x < $arrlength; $x++) {
    echo $vegies[$x];
    echo "<br>";
}
?>