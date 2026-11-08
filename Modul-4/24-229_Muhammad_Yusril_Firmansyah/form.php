<?php
$errors = array();
$submitted = ($_SERVER['REQUEST_METHOD'] === 'POST');

if ($submitted) {
    require_once 'validate.inc';
    validateName($errors, $_POST, 'surname');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Data Pribadi</title>
</head>
<body>

<h2>Form Data Pribadi</h2>

<?php
if ($submitted && !empty($errors)) {
    echo '<h3>Invalid, correct the following errors:</h3>';

    foreach ($errors as $field => $error) {
        echo htmlspecialchars($field) . ' '
            . htmlspecialchars($error) . '<br>';
    }
} elseif ($submitted) {
    echo '<p>Form submitted successfully with no errors!</p>';
}
?>

<form action="form.php" method="POST">

    <label>Surname:</label><br>
    <input type="text" name="surname"
        value="<?php echo htmlspecialchars($_POST['surname'] ?? ''); ?>">
    <br><br>

    <label>Email address:</label><br>
    <input type="text" name="email"
        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
    <br><br>

    <label>Password:</label><br>
    <input type="password" name="password">
    <br><br>

    <label>Street Address:</label><br>
    <textarea name="street_address" rows="4" cols="30"><?php
        echo htmlspecialchars($_POST['street_address'] ?? '');
    ?></textarea>
    <br><br>

    <label>State:</label><br>
    <select name="state">
        <?php
        $states = array(
            'Jawa Timur', 'Jawa Barat', 'Jawa Tengah',
            'DKI Jakarta', 'Bali'
        );
        foreach ($states as $state) {
            $selected = (($_POST['state'] ?? 'Jawa Timur') === $state)
                ? 'selected' : '';
            echo '<option value="' . htmlspecialchars($state) . '" '
                . $selected . '>' . htmlspecialchars($state)
                . '</option>';
        }
        ?>
    </select>
    <br><br>

    <label>Country:</label><br>
    <select name="country">
        <?php
        $countries = array(
            'Australia', 'Indonesia', 'Malaysia', 'Singapore'
        );
        foreach ($countries as $country) {
            $selected = (($_POST['country'] ?? 'Australia') === $country)
                ? 'selected' : '';
            echo '<option value="' . htmlspecialchars($country) . '" '
                . $selected . '>' . htmlspecialchars($country)
                . '</option>';
        }
        ?>
    </select>
    <br><br>

    <label>Gender:</label><br>
    <input type="radio" name="gender" value="Male"
        <?php echo (($_POST['gender'] ?? '') === 'Male') ? 'checked' : ''; ?>>
    Male

    <input type="radio" name="gender" value="Female"
        <?php echo (($_POST['gender'] ?? '') === 'Female') ? 'checked' : ''; ?>>
    Female
    <br><br>

    <label>Vegetarian?</label><br>
    <input type="checkbox" name="vegetarian" value="Yes"
        <?php echo isset($_POST['vegetarian']) ? 'checked' : ''; ?>>
    Yes
    <input type="checkbox" name="vegetarian" value="No"
        <?php echo (($_POST['vegetarian'] ?? '') === 'No') ? 'checked' : ''; ?>>   
     No
    <br><br>

    <input type="submit" value="Submit">
    <input type="reset" value="Reset">

</form>

<?php
if ($submitted && empty($errors)) {
    echo '<h2>Personal Data</h2>';
    echo '<table border="1" cellpadding="8" cellspacing="0">';

    $data = array(
        'Surname' => $_POST['surname'] ?? '',
        'Email address' => $_POST['email'] ?? '',
        'Password' => $_POST['password'] ?? '',
        'Street Address' => $_POST['street_address'] ?? '',
        'State' => $_POST['state'] ?? '',
        'Country' => $_POST['country'] ?? '',
        'Gender' => $_POST['gender'] ?? '',
        'Vegetarian' => $_POST['vegetarian'] ?? '??'
    );

    foreach ($data as $key => $value) {
        echo '<tr><td><b>' . htmlspecialchars($key)
            . '</b></td><td>' . htmlspecialchars($value)
            . '</td></tr>';
    }

    echo '</table>';
}
?>

</body>
</html>