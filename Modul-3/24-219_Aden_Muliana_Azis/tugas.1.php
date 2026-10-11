
<?php
echo "<h2>1.1 Menambahkan Lima Data Baru</h2>";

$fruits = array("Avocado", "Blueberry", "Cherry");

// Menambahkan lima data baru
$fruits[] = "Durian";
$fruits[] = "Elderberry";
$fruits[] = "Fig";
$fruits[] = "Grape";
$fruits[] = "Honeydew";

// Menampilkan seluruh array
echo 'fruits = ("' . implode('", "', $fruits) . '")<br>';

// Menampilkan nilai dengan indeks tertinggi
$indeksTertinggi = count($fruits) - 1;

echo "Nilai dengan indeks tertinggi: "
    . $fruits[$indeksTertinggi];




echo "<h2>1.2 Menghapus Data Blueberry</h2>";

// Menghapus data Blueberry
$key = array_search("Blueberry", $fruits);

if ($key !== false) {
    unset($fruits[$key]);
}

// Menyusun ulang indeks array
$fruits = array_values($fruits);

// Menampilkan array setelah penghapusan
echo "Data Blueberry dihapus.<br>";
echo 'fruits = ("' . implode('", "', $fruits) . '")<br>';

// Menampilkan nilai dengan indeks tertinggi
$indeksTertinggi = count($fruits) - 1;

echo "Nilai dengan indeks tertinggi: "
    . $fruits[$indeksTertinggi];
?>

