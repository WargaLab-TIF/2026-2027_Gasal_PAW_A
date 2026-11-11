<?php
// 1. Membuat array baru bernama $weight dengan tiga data
$weight = array("Andy"=>"70", "Barry"=>"65", "Charlie"=>"75");

// Menampilkan format array sesuai contoh
$arr_str = [];
foreach($weight as $key => $val) {
    $arr_str[] = '"' . $key . '"=>"' . $val . '"';
}
echo 'weight = (' . implode(', ', $arr_str) . ')<br><br>';

// 2. Menampilkan seluruh data menggunakan perulangan FOR
// Karena array asosiatif menggunakan kunci teks (string), kita ambil dulu kuncinya menggunakan array_keys()
$keys = array_keys($weight);
$length = count($weight);

for ($i = 0; $i < $length; $i++) {
    $key = $keys[$i];
    echo $key . " is " . $weight[$key] . " kg.<br>";
}
?>