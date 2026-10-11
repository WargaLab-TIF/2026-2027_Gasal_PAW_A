<?php
// ========================================
// 2.1 Menambahkan Lima Data Baru
// ========================================

echo "<h2>2.1 Menambahkan Lima Data Baru</h2>";

$fruits = array("Avocado", "Blueberry", "Cherry");

for ($i = 1; $i <= 5; $i++) {
    $fruits[] = "Buah Tambahan " . $i;
}

echo "Panjang array saat ini: " . count($fruits) . "<br><br>";

for ($i = 0; $i < count($fruits); $i++) {
    echo $fruits[$i] . "<br>";
}

echo "<hr>";

// ========================================
// 2.2 Membuat Array Sayuran
// ========================================

echo "<h2>2.2 Array Sayuran</h2>";

$veggies = array("Carrot", "Broccoli", "Spinach");

for ($i = 0; $i < count($veggies); $i++) {
    echo $veggies[$i] . "<br>";
}
?>
