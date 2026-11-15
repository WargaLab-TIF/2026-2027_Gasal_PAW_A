<?php
$weight = array("Andy"=>"70","Barry"=>"65","Charlie"=>"75");

$out = [];
foreach ($weight as $x => $y) {
	$out[] = "\"$x\"=>\"$y\"";
}
echo "weight = (" . implode(", ", $out) . ") <br><br>";

$names = array_keys($weight);
$values = array_values($weight);
$arrlength = count($weight);

for ($i = 0; $i < $arrlength; $i++) {
    echo $names[$i] . " is " . $values[$i] . " kg. <br>";
}
?>