<?php
/* ==========================================================
 * 4.3.2 - NOMOR 9
 * form.inc dengan pesan error di samping setiap field.
 * Variabel $errors berasal dari file PHP yang meng-include file ini.
 * ========================================================== */
if (!function_exists('old')) {
    function old($name) { return isset($_POST[$name]) ? htmlspecialchars($_POST[$name]) : ''; }
}
if (!function_exists('err')) {          // pesan error untuk satu field (kosong jika tidak ada error)
    function err($errors, $field) {
        return isset($errors[$field])
            ? '<span class="error">' . htmlspecialchars(errorMessage($field, $errors[$field])) . '</span>'
            : '';
    }
}
$oldState = isset($_POST['state']) ? $_POST['state'] : '-1';
$oldSex   = isset($_POST['sex'])   ? $_POST['sex']   : '';
$states   = array(1 => 'Australian Capital Territory', 2 => 'Queensland', 3 => 'Victoria',
                  4 => 'New South Wales', 5 => 'Tasmania', 6 => 'South Australia',
                  7 => 'Western Australia', 8 => 'Northern Territory');
?>
<fieldset>
<legend>Person Details</legend>
<form name="myForm" action="<?php echo basename($_SERVER['PHP_SELF']); ?>" method="POST">
<div class="field">
<label for="surname">Surname</label>
<input type="text" name="surname" size="31" value="<?php echo old('surname'); ?>"/> <?php echo err($errors, 'surname'); ?>
</div>
<div class="field">
<label for="email">Email address</label>
<input type="text" name="email" size="31" value="<?php echo old('email'); ?>"/> <?php echo err($errors, 'email'); ?>
</div>
<div class="field">
<label for="passwd">Password</label>
<input type="password" name="passwd" size="31"/> <?php echo err($errors, 'passwd'); ?>
</div>
<div class="field">
<label for="address">Street Address</label>
<textarea name="address" rows="3" cols="22"><?php echo old('address'); ?></textarea> <?php echo err($errors, 'address'); ?>
</div>
<div class="field">
<label for="state">State</label>
<select name="state">
<option value="-1" <?php if ($oldState === '-1') echo 'selected'; ?>>[choose your state]</option>
<?php foreach ($states as $val => $name): ?>
<option value="<?php echo $val; ?>" <?php if ($oldState === (string)$val) echo 'selected'; ?>><?php echo $name; ?></option>
<?php endforeach; ?>
</select> <?php echo err($errors, 'state'); ?>
</div>
<div class="field">
<input type="hidden" name="country" value="Australia"/>
</div>
<div class="field">
<label for="sex">Gender</label>
<input type="radio" name="sex" value="male" <?php if ($oldSex === 'male') echo 'checked'; ?>/>Male
<input type="radio" name="sex" value="female" <?php if ($oldSex === 'female') echo 'checked'; ?>/>Female <?php echo err($errors, 'sex'); ?>
</div>
<div class="field">
<label for="vegetarian">Vegetarian?</label>
<input type="checkbox" name="vegetarian" <?php if (isset($_POST['vegetarian'])) echo 'checked'; ?>/>
</div>
<div class="field">
<label>&nbsp;</label>
<input type="submit" value="Submit" name="submit"/><input type="reset" value="Reset" name="reset"/>
</div>
</form>
</fieldset>
