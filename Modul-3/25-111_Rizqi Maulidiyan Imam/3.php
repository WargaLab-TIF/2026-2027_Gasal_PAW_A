<?php
	//3.1
	$height = array("Andy"=>"176","Barry"=>"165","Charlie"=>"170");

	$height["David"] = "180";
	$height["Ethan"] = "172";
	$height["Frank"] = "168";
	$height["George"] = "175";
	$height["Harry"] = "182";
	print_r($height);
	
	echo "<br>Nilai dengan indeks terakhir: " . $height['Harry'];

	unset($height['Charlie']);

	print_r($height);

	echo "<br>Nilai dengan indeks terakhir setelah dihapus: " . $height['Harry'];

	//3.2
	echo "<br>";
	$weight = array("Andy"=>"70", "Barry"=>"65", "Charlie"=>"75");
	print_r($weight);
	
	$nilai = array_values($weight);
	echo "<br>Data kedua: " . $nilai[1];
?>