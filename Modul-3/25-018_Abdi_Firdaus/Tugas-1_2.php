<?php
$fruits = array("Avocado", "Blueberry", "Cherry");

array_push($fruits, "Durian", "Elderberry", "Fig", "Grape", "Honeydew");

$target = "Blueberry";
$key = array_search($target, $fruits);
if ($key !== false) {
	unset($fruits[$key]);
	$fruits = array_values($fruits);
	echo "Data $target dihapus. <br>";
}

echo "fruits = (\"" . implode('", "', $fruits) . "\") <br>";
echo "Nilai dengan indeks tertinggi: " . end($fruits);
?>