<?php

$weight = array(
    "Andy" => "70",
    "Barry" => "65",
    "Charlie" => "75"
);

$names = array_keys($weight);
$arrlength = count($weight);

echo 'weight = ( "Andy"=>"70", "Barry"=>"65", "Charlie"=>"75" )';
echo "<br><br>";

for ($i = 0; $i < $arrlength; $i++) {
    $name = $names[$i];

    echo $name . " is " . $weight[$name] . " kg.<br>";
}

?>
