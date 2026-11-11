<?php
// Membuat array baru bernama $weight dengan tiga data
$weight = array("Andy"=>"70", "Barry"=>"65", "Charlie"=>"75");

// Format tampilan array asosiatif
$arr_str = [];
foreach($weight as $key => $val) {
    $arr_str[] = '"' . $key . '"=>"' . $val . '"';
}
echo 'weight = (' . implode(', ', $arr_str) . ')<br>';

// Menampilkan data kedua dari array asosiatif menggunakan array_values
$weight_values = array_values($weight);
echo 'Data kedua: ' . $weight_values[1];
?>