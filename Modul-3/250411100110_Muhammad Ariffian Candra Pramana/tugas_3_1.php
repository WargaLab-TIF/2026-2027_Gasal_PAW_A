<?php
$height = array("Andy" => "176", "Barry" => "165", "Charlie" => "170");

$height["David"] = "180";
$height["Ethan"] = "172";
$height["Frank"] = "168";
$height["George"] = "175";
$height["Harry"] = "182";

print_r($height);

$height_baru = end($height);
echo "<br>";
echo "Nilai dengan indeks terakhir: ". $height_baru;

echo "<br>";
echo "<br>";

unset($height["Barry"]);
$height_hapus = end($height);
print_r($height);
echo "<br>";
echo "Nilai dengan indeks terakhir setelah dihapus: ". $height_hapus;

?>