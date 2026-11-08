<?php
// Mendeklarasikan array awal
$fruits = array("Avocado", "Blueberry", "Cherry");

// 1.1 Menambahkan 5 data baru
array_push($fruits, "Durian", "Elderberry", "Fig", "Grape", "Honeydew");

// Menampilkan isi array (format seperti contoh)
echo "fruits = (\"" . implode('", "', $fruits) . "\")<br>";

// Menampilkan nilai dengan indeks tertinggi
$highestIndexValue = end($fruits);
echo "Nilai dengan indeks tertinggi: " . $highestIndexValue;
?>