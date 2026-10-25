<form name="myForm" action="processData_form.php" method="POST">
    <div class="field">
        <label for="surname">Surname</label>
        <input type="text" name="surname" size="31" value="<?php echo isset($_POST['surname']) ? htmlspecialchars($_POST['surname']) : ''; ?>">
        <?php if (isset($errors['surname'])) echo '<span style="color: red; font-size: 0.9em; margin-left: 10px;">* Surname ' . $errors['surname'] . '</span>'; ?>
    </div>
    <div class="field">
        <label for="email">Email address</label>
        <input type="text" name="email" size="31" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
        <?php if (isset($errors['email'])) echo '<span style="color: red; font-size: 0.9em; margin-left: 10px;">* Email ' . $errors['email'] . '</span>'; ?>
    </div>
    <div class="field">
        <label for="password">Password</label>
        <input type="text" name="password" size="31" value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>">
        <?php if (isset($errors['password'])) echo '<span style="color: red; font-size: 0.9em; margin-left: 10px;">* Password ' . $errors['password'] . '</span>'; ?>
    </div>
    <div class="field">
        <label for="address">Street address</label>
        <textarea name="address" rows="3" cols="22"><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
        <?php if (isset($errors['address'])) echo '<span style="color: red; font-size: 0.9em; margin-left: 10px;">* Address ' . $errors['address'] . '</span>'; ?>
    </div>
    <div class="field">
        <label for="state">State</label>
        <select name="state">
            <option value="-1" <?php echo (!isset($_POST['state']) || $_POST['state'] == '-1') ? 'selected' : ''; ?>>[choose your state]</option>
            <option value="1" <?php echo (isset($_POST['state']) && $_POST['state'] == '1') ? 'selected' : ''; ?>>Australian Capital Territory</option>
            <option value="2" <?php echo (isset($_POST['state']) && $_POST['state'] == '2') ? 'selected' : ''; ?>>Queensland</option>
            <option value="3" <?php echo (isset($_POST['state']) && $_POST['state'] == '3') ? 'selected' : ''; ?>>Victoria</option>
            <option value="4" <?php echo (isset($_POST['state']) && $_POST['state'] == '4') ? 'selected' : ''; ?>>New South Wales</option>
            <option value="5" <?php echo (isset($_POST['state']) && $_POST['state'] == '5') ? 'selected' : ''; ?>>Tasmania</option>
            <option value="6" <?php echo (isset($_POST['state']) && $_POST['state'] == '6') ? 'selected' : ''; ?>>South Australia</option>
            <option value="7" <?php echo (isset($_POST['state']) && $_POST['state'] == '7') ? 'selected' : ''; ?>>Western Australia</option>
            <option value="8" <?php echo (isset($_POST['state']) && $_POST['state'] == '8') ? 'selected' : ''; ?>>Northern Territory</option>
        </select>
        <?php if (isset($errors['state'])) echo '<span style="color: red; font-size: 0.9em; margin-left: 10px;">* State ' . $errors['state'] . '</span>'; ?>
    </div>
    <div class="field">
        <input type="hidden" name="country" value="Australia">
    </div>
    <div class="field">
        <label for="sex">Gender</label>
        <input type="radio" name="sex" value="male" <?php echo (isset($_POST['sex']) && $_POST['sex'] == 'male') ? 'checked' : ''; ?>>Male
        <input type="radio" name="sex" value="female" <?php echo (isset($_POST['sex']) && $_POST['sex'] == 'female') ? 'checked' : ''; ?>>Female
        <?php if (isset($errors['sex'])) echo '<span style="color: red; font-size: 0.9em; margin-left: 10px;">* Gender ' . $errors['sex'] . '</span>'; ?>
    </div>
    <div class="field">
        <label for="vegetarian">Vegetarian?</label>
        <input type="checkbox" name="vegetarian" <?php echo isset($_POST['vegetarian']) ? 'checked' : ''; ?>>
    </div>
    <div class="field">
        <label>&nbsp;</label>
        <input type="submit" value="submit" name="submit">
        <input type="reset" value="reset" name="reset">
    </div>
</form>