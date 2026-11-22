<?php
	
	// 4.1
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

	echo "<br><br>";
	foreach ($height as $key => $value) {
		echo $key . " is " . $value . " cm tall. <br>";
	}

	// 4.2
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

	$key = array_keys($weight);
	$value = array_values($weight);

	echo"<br>";
	for ($i=0; $i < count($weight); $i++) { 
		echo $key[$i] . " is " . $value[$i] . " kg. <br>"; 
	}

?>