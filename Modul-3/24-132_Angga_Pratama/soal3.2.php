
<?php
$weight = array("Andy"=>"70", "Barry"=>"65", "Charlie"=>"75");

echo 'weight = (';
$i = 0;
$akhir = count($weight);

foreach ($weight as $nama => $berat) {
    echo '"' . $nama . '"=>"' . $berat . '"';
    $i++;
    if ($i < $akhir) {
        echo ', ';
    }
}
echo ')<br>';

echo "Data kedua: " . $weight["Barry"];
?>
