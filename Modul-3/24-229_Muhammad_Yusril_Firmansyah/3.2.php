<?php
// Membuat array baru bernama $weight yang memiliki tiga data[cite: 4]
$weight = array("Andy"=>"70", "Barry"=>"65", "Charlie"=>"75");

// Menampilkan format array weight[cite: 4]
echo "weight = (";
$keys = array_keys($weight);
$last_key = end($keys);
foreach ($weight as $key => $value) {
    echo "\"$key\"=>\"$value\"";
    if ($key !== $last_key) {
        echo ", ";
    }
}
echo ")<br>";

// Menampilkan data kedua dari array (menggunakan array_values atau mengakses key ke-1)[cite: 4]
$weight_values = array_values($weight);
echo "Data kedua: " . $weight_values[1];
?>