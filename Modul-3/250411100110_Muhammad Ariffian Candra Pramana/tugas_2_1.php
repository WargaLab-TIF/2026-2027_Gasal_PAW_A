<?php

$fruits = array("Avocado","Blueberry","Cherry");

for($j=1; $j<=5; $j++) {
    $fruits[] = "Buah Tambahan" . $j;
}

$arrlength = count($fruits);
echo "Panjang array saat ini: " . $arrlength;
echo "<br>";
echo "<br>";

for($i=0; $i<$arrlength; $i++) {
    echo $fruits[$i];
    echo "<br>";
}

/* 
Karena saya sudah memindahkan $arrlength = count($fruits); ke setelah penambahan data, jadi nilainya otomatis 8. 
Dengan begitu, loop for yang menampilkan data tidak perlu diubah sama sekali.
*/

?>