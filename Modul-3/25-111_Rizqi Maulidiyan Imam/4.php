<?php
	//4.1
	$height = array("Andy"=>"176","Barry"=>"165","Charlie"=>"170");

	$height["David"] = "180";
	$height["Ethan"] = "172";
	$height["Frank"] = "168";
	$height["George"] = "175";
	$height["Harry"] = "182";

	foreach ($height as $nama => $tinggi) {
    echo "$nama is $tinggi cm tall.<br>";
}

	//4.2
	echo "<br>";
	$weight = array("Andy"=>"70", "Barry"=>"65", "Charlie"=>"75");
	$key = array_keys($weight);

	for ($i = 0; $i < count($weight); $i++){
		$nama = $key[$i];
		echo "$nama is " . $weight[$nama] . " kg.<br>";
	}
?>