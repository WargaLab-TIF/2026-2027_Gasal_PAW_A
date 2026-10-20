
<?php

$fruits = array(
    "Avocado",
    "Blueberry",
    "Cherry"
);

$fruits[] = "Durian";
$fruits[] = "Elderberry";
$fruits[] = "Fig";
$fruits[] = "Grape";
$fruits[] = "Honeydew";

echo 'fruits = ( "' . implode('", "', $fruits) . '" )';
echo "<br>";

echo "Nilai dengan indeks tertinggi: " . end($fruits);

?>
