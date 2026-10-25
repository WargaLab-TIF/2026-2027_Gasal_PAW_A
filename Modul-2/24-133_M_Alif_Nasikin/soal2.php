<?php
// Soal 2: Foreach & Switch
$matkul = ["PTI", "ALPRO", "DPW", "STRUKDAT", "JARKOM", "PAW", "PSBF", "RPL"];

foreach ($matkul as $m) { // $m = alias dari tiap value array
    switch ($m) {
        case "PTI":
            echo "Saya suka $m<br>";
            break;
        case "ALPRO":
            echo "Saya suka $m<br>";
            break;
        case "DPW":
            echo "Saya suka $m<br>";
            break;
        case "STRUKDAT":
            echo "Saya suka $m<br>";
            break;
        case "JARKOM":
            echo "Saya suka $m<br>";
            break;
        case "PAW":
            echo "Saya suka $m<br>";
            break;
        default:
            echo "Saya tidak mengambil matkul $m<br>";
    }
}
?>
