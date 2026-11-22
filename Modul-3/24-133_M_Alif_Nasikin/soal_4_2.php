<?php
$weight = array("Andy" => "70", "Barry" => "65", "Charlie" => "75");

$pasangan = array();
foreach ($weight as $key => $value) {
    $pasangan[] = '"' . $key . '"=>"' . $value . '"';
}
echo "weight = (" . implode(", ", $pasangan) . ")<br><br>";

$daftarKey = array_keys($weight);  
$jumlah = count($weight);

for ($i = 0; $i < $jumlah; $i++) {
    $nama = $daftarKey[$i];       
    echo $nama . " is " . $weight[$nama] . " kg.<br>";
}
?>
