<?php
$fruits = array("Avocado", "Blueberry", "Cherry");

//Menambahkan lima data baru ke dalam array $fruits dengan perulangan for
for ($i = 1; $i <= 5; $i++) {
    $fruits[] = "Buah Tambahan " . $i; 
}

//Menghitung ulang panjang array saat ini
$arrlength = count($fruits);

echo "Ini jawaban soal 2.1";
echo "<br>";
echo "Panjang array saat ini: " . $arrlength . "<br><br>";

//Skrip perulangan for baris #5-#8
for($x = 0; $x < $arrlength; $x++) {
    echo $fruits[$x];
    echo "<br>";
}

echo "<br>";
echo "Ini jawaban soal 2.2";
echo "<br>";
//Membuat array baru bernama $vegies dengan tiga data
$vegies = array("Carrot", "Broccoli", "Spinach");
$arrlength = count($vegies);

//Menampilkan seluruh datanya dengan perulangan for
for($x = 0; $x < $arrlength; $x++) {
    echo $vegies[$x];
    echo "<br>";
}
?>