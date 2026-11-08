<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Posted Data</title>
</head>
<body>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        echo "<h3>Posted data:</h3>";

        foreach ($_POST as $key => $value) {
            $safeKey = htmlspecialchars(
                (string) $key,
                ENT_QUOTES,
                'UTF-8'
            );

            $safeValue = htmlspecialchars(
                (string) $value,
                ENT_QUOTES,
                'UTF-8'
            );

            echo "($safeKey) =&gt; ($safeValue)<br>";
        }
    } else {
        echo "Belum ada data yang dikirim melalui metode POST.";
    }
    ?>

</body>
</html>