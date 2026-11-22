<?php
$old = $_POST ?? [];

function oldValue($name, $old) {
    return htmlspecialchars(
        $old[$name] ?? '',
        ENT_QUOTES,
        'UTF-8'
    );
}
?>
<form name="myForm" action = "processData_form.php" method="post">
        <div class="field">
            <label for="surname">Surname:</label>
            <input type="text" name="surname"  size="31" />
        </div>
        <div class="field">
            <label for="email">Email:</label>
            <input type="email" name="email"  size="31" value="<?= oldValue('email', $old) ?>"/>
        </div>
        <div class="field">
            <label for="password">Password:</label>
            <input type="password" name="password"  size="31"/>
        </div>
        <div class="field">
            <label for="address" > Street Address:</label>
            <textarea name="address"  rows="3" cols="22"><?= oldValue('address', $old) ?></textarea>
        </div>
        <div class="field">
            <label for="state">State:</label>
            <select name="state">
                <option value="-1" selected>[Choose your state]</option>
                <option value="1" <?= (($old['state'] ?? '') === '1') ? 'selected' : '' ?>>Australian Capital Territory</option>
                <option value="2" <?= (($old['state'] ?? '') === '2') ? 'selected' : '' ?>>Queensland</option>
                <option value="3" <?= (($old['state'] ?? '') === '3') ? 'selected' : '' ?>>Victoria</option>
                <option value="4" <?= (($old['state'] ?? '') === '4') ? 'selected' : '' ?>>New South Wales</option>
                <option value="5" <?= (($old['state'] ?? '') === '5') ? 'selected' : '' ?>>Tasmania</option>
                <option value="6" <?= (($old['state'] ?? '') === '6') ? 'selected' : '' ?>>South Australia</option>
                <option value="7" <?= (($old['state'] ?? '') === '7') ? 'selected' : '' ?>>Western Australia</option>
                <option value="8" <?= (($old['state'] ?? '') === '8') ? 'selected' : '' ?>>Northern Territory</option>
            </select>
            </div>
            <div class="field">
                <label for="sex">Gender:</label>
                <input type="radio" name="sex" value="male" <?= (($old['sex'] ?? '') === 'male') ? 'checked' : '' ?>/>Male
                <input type="radio" name="sex" value="female" <?= (($old['sex'] ?? '') === 'female') ? 'checked' : '' ?>/>Female
            </div>
            <div class="field">
                <label for="vegetarian">Vegetarian:</label>
                <input type="checkbox" name="vegetarian" <?= (($old['vegetarian'] ?? '') === 'on') ? 'checked' : '' ?>/>
            </div>
            <div class="field">
                <input type="submit" value="Submit" name="submit"/> <input type="reset" value="Reset" name="reset"/>
            </div>
    </form>