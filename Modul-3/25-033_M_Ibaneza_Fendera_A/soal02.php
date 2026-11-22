<?php
	
	// 2.1
	$fruits = array("Avocado","Blueberry","Cherry");

	for ($i=1; $i < 6; $i++) { 
		$fruits[] = "Buah Tambahan $i";
	}

	$arrlength = count($fruits);

	echo "Panjang array saat ini: $arrlength <br><br>";
	for($x = 0; $x < $arrlength; $x++) {
		echo $fruits[$x];
		echo "<br>";
	}

	// 2.2
	echo"<br>";
	$vegies = array("Carrot" , "Broccoli" , "Spinach");
	for ($i=0; $i < count($vegies); $i++) { 
		echo $vegies[$i] . "<br>";
	}
?>