<?php
$weight = array("Andy"=>"70","Barry"=>"65","Charlie"=>"75");

$out = [];
foreach ($weight as $x => $y) {
	$out[] = "\"$x\"=>\"$y\"";
}
echo "weight = (" . implode(", ", $out) . ") <br>";
echo "Data kedua: " . array_values($weight)[1];
?>