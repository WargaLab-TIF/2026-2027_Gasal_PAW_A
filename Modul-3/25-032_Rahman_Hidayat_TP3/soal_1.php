<?php
// 1.1
$fruits = array("Avocado", "Blueberry", "Cherry");
$fruits[] = "Durian";
$fruits[] = "Eldenberry";
$fruits[] = "Fig";
$fruits[] = "Grape";
$fruits[] = "Honeydew";
echo "fruits = (";
for ($i = 0; $i < count($fruits); $i++) {
    if ($i < count($fruits) - 1) {
        echo '"' . $fruits[$i] . '", ';
    } else {
        echo '"' . $fruits[$i] . '"';
    }
}
echo ")";
echo "<br>Nilai dengan indeks tertinggi: " . $fruits[count($fruits) - 1] . "<br>";

// 1.2
unset($fruits[1]);
$fruits = array_values($fruits);
echo "<br>Data Blueberry dihapus.";
echo "<br>fruits = (";
for ($i = 0; $i < count($fruits); $i++) {
    if ($i < count($fruits) - 1) {
        echo '"' . $fruits[$i] . '", ';
    } else {
        echo '"' . $fruits[$i] . '"';
    }
}
echo ")";
echo "<br>Nilai dengan indeks tertinggi: " . $fruits[count($fruits) - 1] . "<br>";
?>

<?php
// 2.1
$fruits = array("Avocado", "Blueberry", "Cherry");
for ($i = 1; $i < 6; $i++) {
    $fruits[] = "Buah Tambahan $i";
}
$arrlength = count($fruits);
echo "Panjang array saat ini: $arrlength <br><br>";
for ($x = 0; $x < $arrlength; $x++) {
    echo $fruits[$x];
    echo "<br>";
}

// 2.2
echo "<br>";
$vegies = array("Carrot", "Broccoli", "Spinach");
for ($i = 0; $i < count($vegies); $i++) {
    echo $vegies[$i] . "<br>";
}
?>