<?php
// Membuat array baru dengan nama $weight yang memiliki tiga buah data
$weight = array("Andy"=>"70", "Barry"=>"65", "Charlie"=>"75");

// Menampilkan format deklarasi array weight
echo "weight = (";
$keys_w = array_keys($weight);
$last_key_w = end($keys_w);
foreach ($weight as $key => $value) {
    echo "\"$key\"=>\"$value\"";
    if ($key !== $last_key_w) {
        echo ", ";
    }
}
echo ")<br><br>";

// Mengambil keys dan values ke dalam array numerik agar bisa diloop dengan struktur FOR
$keys = array_keys($weight);
$values = array_values($weight);
$arrlength = count($weight);

// Menampilkan seluruh data dari array $weight menggunakan perulangan FOR
for ($x = 0; $x < $arrlength; $x++) {
    echo $keys[$x] . " is " . $values[$x] . " kg.<br>";
}
?>