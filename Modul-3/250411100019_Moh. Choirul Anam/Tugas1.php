<?php
//1.1
//array terindeksi
 $fruits= array("Avocado","Blueberry","Cherry");
  //menambahkan lima data baru

array_push($fruits, "Durian","Elderberry","fig","Grape","Honeydew");

echo 'fruits=("'.implode('","' ,$fruits).'")<br>';

$akhir = count($fruits)-1;
echo 'Nilai dengan indeks tertinggi:'. $fruits[$akhir]. '<br><br>';
//1.2
//menghapus satu data tertentu.
unset($fruits[1]);

//Mereset indeks array agar kembali urut 0 setelah dihapus
$fruits=array_values($fruits);

echo 'data Blueberry dihapus.<br>';
echo 'fruits("'.implode('","',$fruits).'")<br>';

$akhir = count($fruits)-1;
echo 'Nilai dengan indeks tertinggi:'. $fruits[$akhir];
?>
