<?php
$height = array("Andy"=>"176", "Barry"=>"165", "Charlie"=>"170");

$height["David"] = "180";
$height["Ethan"] = "172";
$height["Frank"] = "168";
$height["George"] = "175";
$height["Harry"] = "182";

echo 'height = (';
$jumlah = count($height);
$i = 0;
foreach ($height as $nama => $tinggi) {
    $i++;
    echo '"' . $nama . '"=>"' . $tinggi . '"';
    if ($i < $jumlah) {
        echo ', ';
    }
}
echo ')<br>';

foreach ($height as $nama => $tinggi) {
    $terakhir = $tinggi;
}
echo "Nilai dengan indeks terakhir: " . $terakhir . "<br><br>";

unset($height["Barry"]);

echo 'height = (';
$jumlah = count($height);
$i = 0;
foreach ($height as $nama => $tinggi) {
    $i++;
    echo '"' . $nama . '"=>"' . $tinggi . '"';
    if ($i < $jumlah) {
        echo ', ';
    }
}
echo ')<br>';

foreach ($height as $nama => $tinggi) {
    $terakhir = $tinggi;
}
echo "Nilai dengan indeks terakhir setelah dihapus: " . $terakhir;