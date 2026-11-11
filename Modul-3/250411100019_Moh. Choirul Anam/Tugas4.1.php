<?php
// 1. Mendeklarasikan array asosiatif $height awal
$height = array("Andy"=>"176", "Barry"=>"165", "Charlie"=>"170");

// Menambahkan lima data baru ke dalam array asosiatif
$height["David"] = "180";
$height["Ethan"] = "172";
$height["Frank"] = "168";
$height["George"] = "175";
$height["Harry"] = "182";

// Menampilkan format array sesuai contoh
$arr_str = [];
foreach($height as $key => $val) {
    $arr_str[] = '"' . $key . '"=>"' . $val . '"';
}
echo 'height = (' . implode(', ', $arr_str) . ')<br><br>';

// Menampilkan seluruh data dengan perulangan
foreach($height as $key => $val) {
    echo $key . " is " . $val . " cm tall.<br>";
}
?>