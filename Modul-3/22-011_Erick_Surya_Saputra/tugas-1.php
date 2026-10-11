<?php
//Deklarasi array awal
$fruits = array("Avocado", "Blueberry", "Cherry");

array_push($fruits, "Durian", "Elderberry", "Fig", "Grape", "Honeydew");

//Memformat array untuk ditampilkan sesuai contoh soal
$formatted_fruits = array_map(function($item) {
    return '"' . $item . '"';
}, $fruits);


echo "Ini jawaban soal 1.1";
echo "<br>";
echo "fruits = ( " . implode(", ", $formatted_fruits) . " )\n";

//Mencari nilai dengan indeks tertinggi
$max_index = max(array_keys($fruits));
echo "Nilai dengan indeks tertinggi: " . $fruits[$max_index] . "\n\n";

echo "<br>";
echo "Ini jawaban soal 1.2";
echo "<br>";

//Mencari dan menghapus data "Blueberry"
$target_hapus = "Blueberry";
$index = array_search($target_hapus, $fruits);

if ($index !== false) {
    unset($fruits[$index]);
}

echo "Data " . $target_hapus . " dihapus.\n";

$formatted_fruits_baru = array_map(function($item) {
    return '"' . $item . '"';
}, $fruits);

echo "fruits = ( " . implode(", ", $formatted_fruits_baru) . " )\n";

//Mencari nilai dengan indeks tertinggi setelah penghapusan
$max_index_baru = max(array_keys($fruits));
echo "Nilai dengan indeks tertinggi: " . $fruits[$max_index_baru] . "\n";
?>
