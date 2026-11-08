<?php

$weight = array("Andy"=>"70", "Barry"=>"65", "Charlie"=>"75");

echo 'weight = (';
$jumlah = count($weight);
$i = 0;
foreach ($weight as $nama => $berat) {
    $i++;
    echo '"' . $nama . '"=>"' . $berat . '"';
    if ($i < $jumlah) {
        echo ', ';
    }
}
echo ')<br>';

echo "Data kedua: " . $weight["Barry"];