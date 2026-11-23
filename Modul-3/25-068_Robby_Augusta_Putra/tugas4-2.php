<?php

$weight = array("Andy"=>"70", "Barry"=>"65", "Charlie"=>"75");

$jumlahW = count($weight);
$i = 0;

echo 'weight = (';
foreach ($weight as $nama => $berat) {
    $i++;
    echo '"' . $nama . '"=>"' . $berat . '"';
    if ($i < $jumlahW) {
        echo ', ';
    }
}
echo ')<br><br>';

foreach ($weight as $nama => $berat) {
    echo $nama . " is " . $berat . " kg.";
    echo "<br>";
}