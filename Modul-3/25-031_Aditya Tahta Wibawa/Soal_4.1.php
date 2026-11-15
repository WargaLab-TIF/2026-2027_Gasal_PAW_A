<?php
$height = array("Andy" => "176","Barry" => "165","Charlie" => "170" );
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

foreach($height as $nama => $tinggi) {
    echo $nama . " is " . $tinggi . " cm tall.<br>";
}
?>