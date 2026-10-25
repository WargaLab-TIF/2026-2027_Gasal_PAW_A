<?php

// Tugas 2.1
$fruits = array("Avocado","Blueberry","Cherry");
for ($i = 1; $i < 6; $i++){
    $fruits[] = "Buah Tambahan $i";
}

$arrlength = count($fruits);
echo "Panjang array saat ini: " . $arrlength . "<br><br>";

for($x = 0; $x < $arrlength; $x++) {
    echo $fruits[$x];
    echo "<br>";
}
echo "<br><br>";

// Tugas 2.2

$vegies = array("Carrot", "Broccoli", "Spinach");
for($x = 0; $x < count($vegies); $x++) {
    echo $vegies[$x];
    echo "<br>";
}
?>