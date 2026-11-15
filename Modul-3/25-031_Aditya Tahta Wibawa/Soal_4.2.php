<?php
$weight = array("Andy"=>"70", "Barry"=>"65", "Charlie"=>"75");

echo 'weight = (';
$jumlah_data = count($weight); 
$iterasi = 0; 

foreach ($weight as $nama => $tinggi) {
    echo '"' . $nama . '"=>"' . $tinggi . '"';
    
    if ($iterasi < $jumlah_data - 1) {
        echo ', ';
    }
    $iterasi++; 
}
echo ')<br>';

$keys = array_keys($weight);
$jumlah = count($keys);

for ($i = 0; $i < $jumlah; $i++) {
    $nama = $keys[$i];
    echo $nama . " is " . $weight[$nama] . " kg.<br>";
}

//foreach ($weight as $nama => $berat) {
//    echo $nama . " is " . $berat . " kg.<br>";
//}
?>