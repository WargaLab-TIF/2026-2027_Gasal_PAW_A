<?php
// ini non-embedded style
echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";
echo "<title>Dasar PHP</title>";
echo "</head>";
echo "<body>";
echo "<h3>1. Non embedded Style</h3>";
echo "<p>Hello World</p>";
echo "</body>";
echo "</html>";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dasar PHP</title>
</head>

<body>
    <?php
    // ini embedded style
    echo "<h3>2. Embedded Style</h3>";
    echo "<p>Hello World</p>";

    // case sensitive
    echo "<h3>3. Case sensitive</h3>";
    $color = "silver";
    $COLOR = "white";
    echo "My car is $color";
    echo "<br>";
    echo "My House is $COLOR";

    // variabel greeting
    echo "<h3>4. Greeting variable</h3>";
    $greeting = "Hello World";
    echo $greeting;

    // w3shool txt
    echo "<h3>5. Variable echo format</h3>";
    $txt = "W3schools.com";
    echo "i love $txt !";

    // operator aritmatika
    echo "<h3>6. Aritmathic operator</h3>";
    $x = 5;
    $y = 7;
    echo $x + $y;

    // hitung karakter str
    echo "<h3>7. Number of string characters</h3>";
    $char = strlen($greeting);
    echo $char;

    // hitung kata
    echo "<h3>8. String word count</h3>";
    $word = str_word_count($greeting);
    echo $word;

    // karakter terbalik
    echo "<h3>9. String reverse</h3>";
    $reverse = strrev($greeting);
    echo $reverse;

    // posisi kata(indeks)
    echo "<h3>10. Word position</h3>";
    $posisi = strpos($greeting, "World");
    echo $posisi;

    //mengubah kata
    echo "<h3>11. Word replace</h3>";
    $str_baru = str_replace("World", "Dolly", $greeting);
    echo $str_baru;

    //fungsi tanpa parameter
    echo "<h3>12. Function without parameters</h3>";
    function writeMsg()
    {
        echo "Hello World";
    }
    writeMsg();

    //fungsi dengan parameter
    echo "<h3>13. Function with parameters</h3>";
    function familyName($fname)
    {
        echo $fname . "<br>";
    }
    familyName("Jani");
    familyName("Hege");
    familyName("Stale");
    familyName("Kai Jim");
    familyName("Borge");

    //fungsi dengan 2 parameter
    echo "<h3>14. Function with 2 parameters</h3>";
    function familyNameY($fname, $year)
    {
        echo $fname . "born in" . $year . "<br>";
    }
    familyNameY("Hege", 1975);
    familyNameY("Stale", 1978);
    familyNameY("Kai Jim", 1983);

    //fungsi dengan set nilai parameter default
    echo "<h3>15. Function with default parameter value set</h3>";
    function setHeight($minheight = 50)
    {
        echo "The height is : $minheight";
    }
    setHeight();

    //fungsi sum
    echo "<h3>16. Sum function</h3>";
    function sum($x, $y)
    {
        $z = $x + $y;
        echo "$x + $y = $z <br>";
    }
    sum(5, 10);
    sum(7, 13);
    sum(2, 4);
    ?>
</body>

</html>