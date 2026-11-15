<?php
$height = array("Andy"=>"176", "Barry"=>"165", "Charlie"=>"170");

// Menambahkan lima data baru
$height["David"] = "180";
$height["Ethan"] = "172";
$height["Frank"] = "168";
$height["George"] = "175";
$height["Harry"] = "182";

echo 'height = (';
$jumlah_data = count($height); 
$iterasi = 0; 

foreach ($height as $nama => $tinggi) {
    echo '"' . $nama . '"=>"' . $tinggi . '"';
    
    if ($iterasi < $jumlah_data - 1) {
        echo ', ';
    }
    $iterasi++; 
}
echo ')<br>';

echo "Nilai dengan indeks terakhir: " . $height[array_key_last($height)] . "<br><br>";

unset($height["Barry"]);

echo 'height = (';
$jumlah_data = count($height); 
$iterasi = 0; 
foreach ($height as $nama => $tinggi) {
    echo '"' . $nama . '"=>"' . $tinggi . '"';
    
    if ($iterasi < $jumlah_data - 1) {
        echo ', ';
    }
    $iterasi++;
}
echo ')<br>';

echo "Nilai dengan indeks terakhir setelah dihapus: " . $height[array_key_last($height)] . "<br>";
?>