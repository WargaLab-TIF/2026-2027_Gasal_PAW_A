<?php
// SOAL 2.2: Buat array baru bernama $vegies yang memiliki tiga data, 
// lalu tampilkan seluruh datanya dengan perulangan for.
$vegies = array("Carrot", "Broccoli", "Spinach");
$vegies_length = count($vegies);

/* 
  PENJELASAN (Jawaban atas pertanyaan soal 2.2):
  - Apakah membuat skrip baru atau cukup memodifikasi skrip yang sudah ada?
    Kita membuat skrip baru atau mendeklarasikan variabel baru.
  - Alasannya:
    Karena objek data yang digunakan berbeda dengan soal sebelumnya (sebelumnya 
    menggunakan variabel $fruits untuk data buah, sedangkan soal 2.2 meminta array 
    baru bernama $vegies untuk data sayuran), maka kita perlu mendefinisikan 
    variabel array baru beserta variabel panjang array-nya ($vegies_length).
    Hal ini memastikan perulangan for dapat mengakses elemen "Carrot", "Broccoli", 
    dan "Spinach" dengan akurat tanpa tercampur dengan data buah sebelumnya.
*/

// Menampilkan seluruh data array $vegies menggunakan perulangan for
for ($x = 0; $x < $vegies_length; $x++) {
    echo $vegies[$x];
    echo "<br>";
}
?>