<?php
// Deklarasi array awal
$height = array("Andy"=>"176", "Barry"=>"165", "Charlie"=>"170");

echo "Ini jawaban soal 4.1";
echo "<br>";

//Menambahkan lima data baru
$height["David"] = "180";
$height["Ethan"] = "172";
$height["Frank"] = "168";
$height["George"] = "175";
$height["Harry"] = "182";

//Mencetak format array
$formatted_height = [];
foreach($height as $key => $value) {
    $formatted_height[] = '"' . $key . '"=>"' . $value . '"';
}
echo "height = (" . implode(", ", $formatted_height) . ")<br><br>";

//Menampilkan seluruh data dengan perulangan foreach
foreach ($height as $nama => $tinggi) {
    echo $nama . " is " . $tinggi . " cm tall.<br>";
}

echo "<br>"; 
echo "Ini jawaban soal 4.2";
echo "<br>";

// Membuat array baru bernama $weight dengan tiga data
$weight = array("Andy"=>"70", "Barry"=>"65", "Charlie"=>"75");

// Mencetak format array
$formatted_weight = [];
foreach($weight as $key => $value) {
    $formatted_weight[] = '"' . $key . '"=>"' . $value . '"';
}
echo "weight = (" . implode(", ", $formatted_weight) . ")<br><br>";

// Menampilkan seluruh data menggunakan struktur perulangan FOR
$keys = array_keys($weight); // Mengambil semua 'key' (nama) ke dalam array terindeks
$arrlength = count($keys); // Menghitung panjang array

for ($i = 0; $i < $arrlength; $i++) {
    $nama = $keys[$i];            // Mengambil key pada indeks ke-$i
    $berat = $weight[$nama];      // Mengambil value berdasarkan key tersebut
    
    echo $nama . " is " . $berat . " kg.<br>";
}
?>