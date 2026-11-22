<?php

	$matkul = array("PTI", "ALPRO", "DPW", "STRUKDAT", "JARKOM", "PAW", "PSBF", "RPL");

	foreach ($matkul as $key => $value) {
		switch ($value) {
			case 'PTI':
				echo "Saya suka PTI";
				echo "<br>";
				break;
			case 'ALPRO':
				echo "Saya suka ALPRO";
				echo "<br>";
				break;
			case 'DPW':
				echo "Saya suka DPW";
				echo "<br>";				
				break;
			case 'STRUKDAT':
				echo "Saya suka STRUKDAT";
				echo "<br>";				
				break;
			case 'JARKOM':
				echo "Saya suka JARKOM";
				echo "<br>";				
				break;
			case 'PAW':
				echo "Saya suka PAW";
				echo "<br>";				
				break;				
			default:
				echo "Saya tidak mengambil matkul $value";
				echo "<br>";				
				break;
		}
	}

?>