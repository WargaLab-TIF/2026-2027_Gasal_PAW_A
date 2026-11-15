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

echo "Data kedua: " . $weight["Barry"] . "<br>"; 
?>