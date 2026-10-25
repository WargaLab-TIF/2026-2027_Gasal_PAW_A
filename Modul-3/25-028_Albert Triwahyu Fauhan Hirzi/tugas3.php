<?php
//Tugas 3.1
$height = array("Andy" => "176","Barry" => "165","Charlie" => "170");
echo "Andy is " . $height["Andy"] . " cm tall";
$height += array("David" => "180","Ethan"=> "172","Frank" => "168","George" =>"175","Harry" => "182");

echo "<br>" . "height: (";
foreach($height as $key => $value){
    if ($key == "Andy"){
        echo '"' . $key . '"' . "=>" . '"' . $value . '"';
    }
    elseif ($key == "Harry"){
        echo ",". '"' . $key . '"' . "=>" . '"' . $value . '"';
    } else {
        echo ',' . '"' . $key . '"' . "=>" . '"' . $value . '"';
    }
}
echo ")" . "<br>";
echo "Nilai dengan indeks terakhir: " . end($height) . "<br><br>";

unset($height["Barry"]);
echo "<br>" . "height: (";
foreach($height as $key => $value){
    if ($key == "Andy"){
        echo '"' . $key . '"' . "=>" . '"' . $value . '"';
    }
    elseif ($key == "Harry"){
        echo ",". '"' . $key . '"' . "=>" . '"' . $value . '"';
    } else {
        echo ',' . '"' . $key . '"' . "=>" . '"' . $value . '"';
    }
}
echo ")" . "<br>";
echo "Nilai dengan indeks terakhir: " . end($height) . "<br><br>";

echo "<br>";

// Tugas 3.2
echo "weight = (";
$weight = array("Andy" => "70" , "Barry" => "65" , "Charlie" => "75");
foreach($weight as $key => $value){
    if ($key == "Barry"){
    echo ','.'"' . $key . '"' . "=>" . '"' . $value . '"' . ',';
    }else{
        echo '"' . $key . '"' . "=>" . '"' . $value . '"';
    }
}

echo ")";
$indeks = array_values($weight);
echo "<br>"."Data Kedua: " . $indeks[1] ;
?>