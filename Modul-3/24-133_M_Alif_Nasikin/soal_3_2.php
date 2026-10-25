<?php

$weight = array("Andy" => "70", "Barry" => "65", "Charlie" => "75");

$pasangan = array();
foreach ($weight as $key => $value) {
    $pasangan[] = '"' . $key . '"=>"' . $value . '"';
}
echo "weight = (" . implode(", ", $pasangan) . ")<br>";

$nilai = array_values($weight);
echo "Data kedua: " . $nilai[1];
?>
