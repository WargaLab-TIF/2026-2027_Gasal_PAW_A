<?php
$fruits = array("Avocado","Blueberry","Cherry");

$additional = array("Buah Tambahan 1", "Buah Tambahan 2", "Buah Tambahan 3", "Buah Tambahan 4", "Buah Tambahan 5");
for ($i=0; $i < count($additional); $i++) { 
	$fruits[] = $additional[$i];
}

$arrlength = count($fruits);
echo "Panjang array saat ini: $arrlength <br>";
for ($x = 0; $x < $arrlength; $x++) {
	echo $fruits[$x];
	echo "<br>";
}

?>