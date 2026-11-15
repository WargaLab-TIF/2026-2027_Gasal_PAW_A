<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>GET Data</title>
</head>
<body>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET)) {
        echo "<h3>GET data:</h3>";

        foreach ($_GET as $key => $value) {
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
        echo "Belum ada data yang dikirim melalui metode GET.";
    }
    ?>

</body>
</html>