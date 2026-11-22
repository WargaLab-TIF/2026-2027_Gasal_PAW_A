<?php
    // 2.1
    $fruits = array("Avocado", "Blueberry", "Cherry");

    for ($i=1; $i < 6; $i++) { 
        $fruits[] = "Buah tambahan $i";
    }

    $arrlength = count($fruits);

    echo "Panjang array saat ini: $arrlength <br><br>";
    for ($i=0; $i < $arrlength; $i++) { 
        echo $fruits[$i];
        echo "<br>";
    }

    // 2.2
    echo "<br>";
    $vegies = array("Carrot", "Broccoli", "Spinach");
    for ($i=0; $i < count($vegies); $i++) { 
        echo $vegies[$i] . "<br>";
    }
?>