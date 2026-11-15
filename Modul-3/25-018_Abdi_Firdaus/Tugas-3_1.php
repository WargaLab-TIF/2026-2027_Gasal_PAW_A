<?php
$height = array("Andy"=>"176","Barry"=>"165","Charlie"=>"170");
$height += ["David" => "180", "Ethan" => "172", "Frank" => "168", "George" => "175", "Harry" => "182"];

function printHeight($arr) {
	$out = [];
	foreach ($arr as $x => $y) {
		$out[] = "\"$x\"=>\"$y\"";
	}
	return "height = (" . implode(", ", $out) . ")";
}

echo printHeight($height) . "<br>";
echo "Nilai dengan indeks terakhir: " . end($height) . "<br><br>";

unset($height["Barry"]);
echo printHeight($height) . "<br>";
echo "Nilai dengan indeks terakhir setelah dihapus: " . end($height);
?>