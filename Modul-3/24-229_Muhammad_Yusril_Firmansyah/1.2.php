<?php
// Melanjutkan dari array sebelumnya atau mendefinisikan ulang jika terpisah
$fruits = array("Avocado", "Blueberry", "Cherry", "Durian", "Elderberry", "Fig", "Grape", "Honeydew");

// 1.2 Menghapus data "Blueberry"
$searchKey = array_search("Blueberry", $fruits);
if ($searchKey !== false) {
    unset($fruits[$searchKey]);
    // Merapikan kembali index array setelah unset
    $fruits = array_values($fruits);
    echo "Data Blueberry dihapus.<br>";
}

// Menampilkan isi array terbaru
echo "fruits = (\"" . implode('", "', $fruits) . "\")<br>";

// Menampilkan nilai dengan indeks tertinggi
$highestIndexValue = end($fruits);
echo "Nilai dengan indeks tertinggi: " . $highestIndexValue;
?>