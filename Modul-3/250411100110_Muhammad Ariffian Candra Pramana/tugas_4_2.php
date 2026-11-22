<?php

$weight = array("Andy" => "70", "Barry" => "65", "Charlie" => "75");

print_r($weight);
echo "<br>";
echo "<br>";
$keys = array_keys($weight);
for($i=0; $i<count($weight); $i++) {
    echo $keys[$i] . " is " . $weight[$keys[$i]] . " kg." . "<br>";
}

?>