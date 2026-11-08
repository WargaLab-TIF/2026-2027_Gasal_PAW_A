<?php
// Mendeklarasikan array asosiatif awal[cite: 4]
$height = array("Andy"=>"176", "Barry"=>"165", "Charlie"=>"170");

// 3.1 Menambahkan lima data baru ke dalam array $height[cite: 4]
$height["David"] = "180";
$height["Ethan"] = "172";
$height["Frank"] = "168";
$height["George"] = "175";
$height["Harry"] = "182";

// Menampilkan format array height[cite: 4]
echo "height = (";
$keys = array_keys($height);
$last_key = end($keys);
foreach ($height as $key => $value) {
    echo "\"$key\"=>\"$value\"";
    if ($key !== $last_key) {
        echo ", ";
    }
}
echo ")<br>";

// Menampilkan nilai dengan indeks terakhir[cite: 4]
echo "Nilai dengan indeks terakhir: " . end($height) . "<br><br>";

// Menghapus satu data tertentu (misalnya "Barry")[cite: 4]
unset($height["Barry"]);

// Menampilkan kembali format array setelah dihapus[cite: 4]
echo "height = (";
$keys = array_keys($height);
$last_key = end($keys);
foreach ($height as $key => $value) {
    echo "\"$key\"=>\"$value\"";
    if ($key !== $last_key) {
        echo ", ";
    }
}
echo ")<br>";

// Menampilkan nilai dengan indeks terakhir setelah dihapus[cite: 4]
echo "Nilai dengan indeks terakhir setelah dihapus: " . end($height);
?>