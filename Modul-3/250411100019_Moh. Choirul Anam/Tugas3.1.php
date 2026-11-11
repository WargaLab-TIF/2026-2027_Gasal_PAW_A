<?php
// Mendeklarasikan array asosiatif awal
$height = array("Andy"=>"176", "Barry"=>"165", "Charlie"=>"170");

// Menambahkan 5 data baru ke dalam array $height
$height["David"] = "180";
$height["Ethan"] = "172";
$height["Frank"] = "168";
$height["George"] = "175";
$height["Harry"] = "182";

// Format tampilan array asosiatif
$arr_str = [];
foreach($height as $key => $val) {
    $arr_str[] = '"' . $key . '"=>"' . $val . '"';
}
echo 'height = (' . implode(', ', $arr_str) . ')<br>';
echo 'Nilai dengan indeks terakhir: ' . end($height) . '<br><br>';

// Menghapus satu data tertentu (misal: "Barry")
unset($height["Barry"]);

// Menampilkan kembali setelah dihapus
$arr_str = [];
foreach($height as $key => $val) {
    $arr_str[] = '"' . $key . '"=>"' . $val . '"';
}
echo 'height = (' . implode(', ', $arr_str) . ')<br>';
echo 'Nilai dengan indeks terakhir setelah dihapus: ' . end($height);
?>