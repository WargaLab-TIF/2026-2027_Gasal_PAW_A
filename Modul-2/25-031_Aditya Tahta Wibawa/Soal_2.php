<?php
$matkul = ["PTI","ALPRO","DPW","STRUKDAT","JARKOM","PAW","PSBF","RPL"];
foreach ($matkul as $key) {
	switch ($key) {
		case 'PTI':
			echo "Saya suka $key <br>";
			break;
		case 'ALPRO':
			echo "Saya suka $key <br>";
			break;
		case 'DPW':
			echo "Saya suka $key <br>";
			break;
		case 'STRUKDAT':
			echo "Saya suka $key <br>";
			break;
		case 'JARKOM':
			echo "Saya suka $key <br>";
			break;
		case 'PAW':
			echo "Saya suka $key <br>";
			break;
		
		default:
			echo "Saya tidak mengambil matkul $key <br>";
			break;
	}
}
?>