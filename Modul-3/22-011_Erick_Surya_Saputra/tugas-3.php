<?php
//Deklarasi array awal
$height = array("Andy"=>"176", "Barry"=>"165", "Charlie"=>"170");

//Menambahkan lima data baru
$height["David"] = "180";
$height["Ethan"] = "172";
$height["Frank"] = "168";
$height["George"] = "175";
$height["Harry"] = "182";

echo "Ini jawaban soal 3.1";
echo "<br>";

//Memformat array agar tampilannya sesuai dengan contoh di soal
$formatted_height = [];
foreach($height as $key => $value) {
    $formatted_height[] = '"' . $key . '"=>"' . $value . '"';
}
echo "height = (" . implode(", ", $formatted_height) . ")\n";

//Mengambil dan menampilkan nilai dari elemen terakhir
echo "Nilai dengan indeks terakhir: " . end($height) . "\n\n";

//Menghapus satu data tertentu
unset($height["Barry"]);

//Memformat array kembali setelah penghapusan
$formatted_height_baru = [];
foreach($height as $key => $value) {
    $formatted_height_baru[] = '"' . $key . '"=>"' . $value . '"';
}
echo "height = (" . implode(", ", $formatted_height_baru) . ")\n";

//Mengambil dan menampilkan nilai dari elemen terakhir setelah penghapusan
echo "Nilai dengan indeks terakhir setelah dihapus: " . end($height) . "\n\n";


echo "<br>";
echo "Ini jawaban soal 3.2";
echo "<br>";
//Buat array baru bernama $weight dengan tiga data
$weight = array("Andy"=>"70", "Barry"=>"65", "Charlie"=>"75");

//Memformat array agar tampilannya sesuai dengan contoh
$formatted_weight = [];
foreach($weight as $key => $value) {
    $formatted_weight[] = '"' . $key . '"=>"' . $value . '"';
}
echo "weight = (" . implode(", ", $formatted_weight) . ")\n";

//Mengambil data kedua dari array asosiatif
$keys = array_keys($weight);
$data_kedua = $weight[$keys[1]]; 

echo "Data kedua: " . $data_kedua . "\n";
?>