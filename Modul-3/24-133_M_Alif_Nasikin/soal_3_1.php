<?php
function tampilkan($nama, $array) {
    $pasangan = array();
    foreach ($array as $key => $value) {
        $pasangan[] = '"' . $key . '"=>"' . $value . '"';
    }
    echo $nama . " = (" . implode(", ", $pasangan) . ")<br>";
}

$height = array("Andy" => "176", "Barry" => "165", "Charlie" => "170");

$height["David"]  = "180";
$height["Ethan"]  = "172";
$height["Frank"]  = "168";
$height["George"] = "175";
$height["Harry"]  = "182";

tampilkan("height", $height);

$keyTerakhir = array_key_last($height);
echo "Nilai dengan indeks terakhir: " . $height[$keyTerakhir] . "<br><br>";

unset($height["Barry"]);

tampilkan("height", $height);
$keyTerakhir = array_key_last($height);
echo "Nilai dengan indeks terakhir setelah dihapus: " . $height[$keyTerakhir];
?>
