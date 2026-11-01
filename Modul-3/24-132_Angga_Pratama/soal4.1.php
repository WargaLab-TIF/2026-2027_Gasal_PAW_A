
<?php
$height = array("Andy"=>"176", "Barry"=>"165", "Charlie"=>"170");

$height["David"] = "180";
$height["Ethan"] = "172";
$height["Frank"] = "168";
$height["George"] = "175";
$height["Harry"] = "182";

echo 'height = (';
$i = 0;
$akhir = count($height);

foreach ($height as $nama => $tinggi) {
    echo '"' . $nama . '"=>"' . $tinggi . '"';
    $i++;
    if ($i < $akhir) {
        echo ', ';
    }
}
echo ')<br>';

foreach ($height as $nama => $tinggi) {
    echo $nama . ' is ' . $tinggi . ' cm tall.<br>';
}
?>
