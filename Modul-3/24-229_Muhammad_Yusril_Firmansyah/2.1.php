<?php
// Mendeklarasikan array awal
$fruits = array("Avocado", "Blueberry", "Cherry");

// Menambahkan 5 data baru ke dalam array $fruits
array_push($fruits, "Buah Tambahan 1", "Buah Tambahan 2", "Buah Tambahan 3", "Buah Tambahan 4", "Buah Tambahan 5");

// Menghitung panjang array saat ini
$arrlength = count($fruits);

// Menampilkan panjang array saat ini
echo "Panjang array saat ini: " . $arrlength . "<br><br>";

// Perulangan untuk menampilkan seluruh isi array
for($x = 0; $x < $arrlength; $x++) {
    echo $fruits[$x];
    echo "<br>";
}
?>