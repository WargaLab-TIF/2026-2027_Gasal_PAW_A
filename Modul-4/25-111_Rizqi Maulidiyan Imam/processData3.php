<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Posted Data</title>
</head>
<body>
    <h3>Posted Data</h3>
    <?php
    foreach ($_POST as $key => $value) {
        echo "($key)=> ($value)<br>";
    }
    ?>
</body>
</html>