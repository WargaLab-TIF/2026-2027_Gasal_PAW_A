<?php
	$matkul=["PTI","ALPRO","DPW","STRUKDAT","JARKOM","PAW","PSBF","RPL"];
	$praktikum=["JARKOM","PAW"];

	for ($i=0; $i < count($matkul); $i++) { 
		//mengecek apakah matkul ada di dalam array praktikum
		if (in_array($matkul[$i], $praktikum)){
			echo "Saya sedang mengambil matkul $matkul[$i] termasuk praktikum nya<br>";
		//mengecek apakah indek bernilai 6 dan 7
		}elseif ($i == 6 || $i == 7) {
			echo "Saya mengambil matkul $matkul[$i] <br>";
		//mengambil kondisi atau nilai indeks selain diatas
		}else{
			echo "Saya sudah mengambil matkul $matkul[$i] semester lalu<br>";
		}
	}
?>