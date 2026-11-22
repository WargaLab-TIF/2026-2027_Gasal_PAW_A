<?php
	//1.1
	$fruits = array("Avocado","Blueberry","Cherry");
	$fruits[] = "Durian";
	$fruits[] = "Elderberry";
	$fruits[] = "Fig";
	$fruits[] = "Grape";
	$fruits[] = "Honeydew";

	print_r($fruits);
	echo "<br>"."Nilai dengan indeks tertinggi: ".$fruits[count($fruits)-1];

	//1.2
	echo "<br>";

	unset($fruits[1]);
	$fruits = array_values($fruits);

	echo "Data Blueberry Dihapus";
	echo "<br>";
	print_r($fruits);
	echo "<br>"."Nilai dengan indeks tertinggi: ".$fruits[count($fruits)-1];
	
?>