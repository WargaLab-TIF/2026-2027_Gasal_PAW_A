<?php
$fruits=array("avocado","Blueberry","Cherry");
//Menambah lima data baru ke dalam array fruits
for ($i=1; $i <=5 ; $i++) { 
	$fruits[]="Buah Tambahan".$i;

	}
//Memperbarui nilai variabel $arrlenght setelah data bertambah

$arrlength=count($fruits);
	echo "panjan array saat ini:". $arrlength. "<br><br>";
//Menampilkan seluruh isi array menggunakan perulangan for
for ($a=0; $a <$arrlength; $a++) { 
	echo $fruits[$a];
	echo "<br>";
}
?>