<?php 
    echo "<h3>1. If, elseif, else & For</h3>";
    $matkul = array(
        "PTI", "ALPRO", "DPW", "STRUKDAT", "JARKOM", "PAW", "PSBF", "RPL"
    ); 
    $praktikum = array(
        "JARKOM","PAW"
    );

    for ($i=0; $i<count($matkul); $i++) {
        if($matkul[$i] == $praktikum[0] or $matkul[$i] == $praktikum[1]) {
            echo "Saya sedang mengambil matkul $matkul[$i] termasuk praktikumnya <br>";
        } elseif ($i == 6 or $i == 7) {
            echo "Saya belum mengambil matkul $matkul[$i] <br>";
        } else {
            echo "Saya sudah mengambil matkul $matkul[$i] semester lalu <br>";
        }
    }

    echo "<h3>2. Foreach & Switch</h3>";
    foreach ($matkul as $kalimat) {
        switch ($kalimat) {
            case "PTI":
                echo "SayasukaPTI <br>";
                break;
            case "ALPRO":
                echo "SayasukaALPRO <br>";
                break;
            case "DPW":
                echo "SayasukaDPW <br>";
                break;
            case "STRUKDAT":
                echo "SayasukaSTRUKDAT <br>";
                break;
            case "JARKOM":
                echo "SayasukaJARKOM <br>";
                break;
            case "PAW":
                echo "SayasukaPAW <br>";
                break;
            default:
                echo "Sayatidakmengambilmatkul$kalimat <br>";
                break;
        }
    }

    echo "<h3>3. Do While</h3>";
    $angka = 0;
    do {
        echo "$angka <br>";
        $angka+=4;
    } while ($angka <= 20); 
?>