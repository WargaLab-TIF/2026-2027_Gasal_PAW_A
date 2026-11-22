<?php

$matkul = array("PTI","ALPRO","DPW","STRUKDAT","JARKOM","PAW","PSBF","RPL");
$praktikum = array("JARKOM","PAW");

for ($i = 0; $i < count($matkul); $i++) {
	$nama_matkul = $matkul[$i];
	if ($nama_matkul == "JARKOM" OR $nama_matkul == "PAW"){
		echo "Saya sedang mangambil matkul ".$nama_matkul. "Termasuk praktikumnya";
		echo "<br>";
	}
	elseif ($i == 6 OR $i == 7) {
		echo "Saya belum mengambil matkul ".$nama_matkul;
		echo "<br>";
	}
	else {
		echo "saya sudah mengambil matkul ".$nama_matkul."Semester lalu";
		echo "<br>";
	}
}
?>