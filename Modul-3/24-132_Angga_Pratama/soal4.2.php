
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

$nama = array("Andy", "Barry", "Charlie");

for ($i = 0; $i < count($nama); $i++) {
    echo $nama[$i] . ' is ' . $weight[$nama[$i]] . ' kg.<br>';
}
?>
