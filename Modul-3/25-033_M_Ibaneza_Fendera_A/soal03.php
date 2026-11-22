<?php
	
	// 3.1
	$height = array("Andy"=>"176","Barry"=>"165","Charlie"=>"170");
	
	$height["David"] = "180";
	$height["Ethan"] = "172";
	$height["Frank"] = "168";
	$height["George"] = "175";
	$height["Harry"] = "182";

	echo "height = (";
	foreach ($height as $key => $value) {
		if ($key == "Harry") {
			echo '"' . $key . '"' . '=>' . '"' . $value . '"';
		}else{
			echo '"' . $key . '"' . '=>' . '"' . $value . '", ';			
		}
	}
	echo ")";
	echo "<br>Nilai dengan indeks terakhir: " . end($height);

	unset($height["Barry"]);
	echo "<br><br>height = (";
	foreach ($height as $key => $value) {
		if ($key == "Harry") {
			echo '"' . $key . '"' . '=>' . '"' . $value . '"';
		}else{
			echo '"' . $key . '"' . '=>' . '"' . $value . '", ';			
		}
	}
	echo ")";
	echo "<br>Nilai dengan indeks terakhir setelah dihapus: " . end($height);

	// 3.2
	echo "<br><br>weight = (";
	$weight = array("Andy"=>"70", "Barry"=>"65", "Charlie"=>"75");
	foreach ($weight as $key => $value) {
		if ($key == "Charlie") {
			echo '"' . $key . '"' . '=>' . '"' . $value . '"';
		}else{
			echo '"' . $key . '"' . '=>' . '"' . $value . '", ';			
		}
	}
	echo ")";
	$indeks = array_values($weight);
	echo "<br>Data kedua: " . $indeks[1];
?>