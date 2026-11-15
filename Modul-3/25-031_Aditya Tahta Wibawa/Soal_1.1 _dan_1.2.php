<?php
//Soal 1.1
$fruits = array("Avocado","Blueberry","Cherry");
//array_push($fruits, "Durian", "Elderberry", "Fig", "Grape", "Honeydew"); bisa pakai ini
$fruits[] = "Durian";
$fruits[] = "Elderberry";
$fruits[] = "Fig";
$fruits[] = "Grape";
$fruits[] = "Honeydew";

echo 'fruits = (';
$jumlah_data = count($fruits);
$iterasi = 0;

foreach ($fruits as $buah) {
    echo '"' . $buah . '"';
    if ($iterasi < $jumlah_data - 1) {
        echo ', '; 
    }
    $iterasi++;
}
echo ')<br>';

$indeks_tertinggi = array_key_last($fruits);
echo "Nilai dengan indeks tertinggi: $fruits[$indeks_tertinggi]<br>";

echo "<br>";

//Soal 1.2
unset($fruits[1]);
echo "Data Blueberry dihapus.<br>";
echo 'fruits = (';
$jumlah_data = count($fruits);
$iterasi = 0; 

foreach ($fruits as $buah) {
    echo '"' . $buah . '"';
    if ($iterasi < $jumlah_data - 1) {
        echo ', '; 
    }
    $iterasi++;
}
echo ')<br>';

$indeks_tertinggi = array_key_last($fruits);
echo "Nilai dengan indeks tertinggi: $fruits[$indeks_tertinggi]<br>";

?>