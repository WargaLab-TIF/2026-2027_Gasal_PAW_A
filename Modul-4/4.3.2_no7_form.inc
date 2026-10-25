<?php
/* ==========================================================
 * 4.3.2 - NOMOR 7
 * form.inc versi baru: menampilkan kembali data yang sudah terisi.
 * Nilai diambil dari $_POST dan di-escape dengan htmlspecialchars().
 * ========================================================== */
if (!function_exists('old')) {
    function old($name) {                       // nilai lama untuk textbox/textarea
        return isset($_POST[$name]) ? htmlspecialchars($_POST[$name]) : '';
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
<input type="text" name="surname" size="31" value="<?php echo old('surname'); ?>"/> <!-- atribut value diisi data lama -->
</div>
<div class="field">
<label for="email">Email address</label>
<input type="text" name="email" size="31" value="<?php echo old('email'); ?>"/>
</div>
<div class="field">
<label for="passwd">Password</label>
<input type="password" name="passwd" size="31"/> <!-- password sengaja TIDAK diisi ulang (keamanan) -->
</div>
<div class="field">
<label for="address">Street Address</label>
<textarea name="address" rows="3" cols="22"><?php echo old('address'); ?></textarea> <!-- isi textarea di antara tag -->
</div>
<div class="field">
<label for="state">State</label>
<select name="state">
<option value="-1" <?php if ($oldState === '-1') echo 'selected'; ?>>[choose your state]</option>
<?php foreach ($states as $val => $name): ?>
<option value="<?php echo $val; ?>" <?php if ($oldState === (string)$val) echo 'selected'; ?>><?php echo $name; ?></option> <!-- selected = pilihan lama -->
<?php endforeach; ?>
</select>
</div>
<div class="field">
<input type="hidden" name="country" value="Australia"/>
</div>
<div class="field">
<label for="sex">Gender</label>
<input type="radio" name="sex" value="male" <?php if ($oldSex === 'male') echo 'checked'; ?>/>Male <!-- checked = pilihan lama -->
<input type="radio" name="sex" value="female" <?php if ($oldSex === 'female') echo 'checked'; ?>/>Female
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
