<?php
	
	// 1.1
	$fruits = array("Avocado","Blueberry","Cherry");

	$fruits[] = "Durian";
	$fruits[] = "Eldenberry";
	$fruits[] = "Fig";
	$fruits[] = "Grape";
	$fruits[] = "Honeydew";

	echo "fruits = (";
	for ($i=0; $i < count($fruits); $i++) { 
		if ($i < count($fruits) - 1) {
			echo '"' . $fruits[$i] . '", ';
		}else{
			echo '"' . $fruits[$i] . '"';
		}
	}
	echo ")";
	echo "<br>Nilai dengan indeks tertinggi: ". $fruits[count($fruits) - 1] . "<br>";

	// 1.2
	unset($fruits[1]);
	$fruits = array_values($fruits);

	echo "<br>Data Blueberry dihapus.";
	echo "<br>fruits = (";

	for ($i = 0; $i < count($fruits); $i++) {
		if ($i < count($fruits) - 1) {
			echo '"' . $fruits[$i] . '", ';
		} else {
			echo '"' . $fruits[$i] . '"';
		}
	}

	echo ")";
	echo "<br>Nilai dengan indeks tertinggi: " . $fruits[count($fruits) - 1] . "<br>";
?>