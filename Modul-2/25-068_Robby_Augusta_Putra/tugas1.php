<?php

$matkul = ["PTI", "ALPRO", "DPW", "STRUKDAT", "JARKOM", "PAW", "PSBF", "RPL"];
$praktikum = ["JARKOM", "PAW"];

for ($i=0; $i < count($matkul); $i++) {
	for ($j=0; $j < count($praktikum); $j++) {
		if ($matkul[$i] == $praktikum[$j]) {
			echo "Saya sedang mengambil matkul $matkul[$i] termasuk praktikumnya<br>";
			break;
		}
		elseif ($i==6) {
			if ($j == count($praktikum) - 1)
			echo "Saya belum mengambil matkul $matkul[$i]<br>";
		}
		elseif ($i==7) {
			if ($j == count($praktikum) -1)
			echo "Saya belum mengambil matkul $matkul[$i]<br>";
		}else {
			if ($j == count($praktikum) -1)
			echo "Saya sudah mengambil matkul $matkul[$i] semester lalu<br>";
		}
	}
}

?>