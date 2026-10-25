<?php
// Langkah 7: menampilkan kembali data yang sudah diisi (sticky form)
if (!isset($errors)) $errors = array();

function old($key, $default = '')
{
    return isset($_POST[$key]) ? htmlspecialchars($_POST[$key], ENT_QUOTES) : $default;
}
function err($key)
{
    global $errors;
    return isset($errors[$key]) ? '<span class="err">' . htmlspecialchars($errors[$key]) . '</span>' : '';
}

$states = array(
    '1' => 'Australian Capital Territory', '2' => 'Queensland',
    '3' => 'Victoria',                     '4' => 'New South Wales',
    '5' => 'Tasmania',                     '6' => 'South Australia',
    '7' => 'Western Australia',            '8' => 'Northern Territory'
);
$selState = old('state', '-1');
$sex      = old('sex');
?>
<fieldset>
<legend>Person Details</legend>
<form name="myForm" action="register.php" method="POST">
  <div class="field">
    <label for="surname">Surname</label>
    <input type="text" id="surname" name="surname" size="31" value="<?php echo old('surname'); ?>"/><?php echo err('surname'); ?>
  </div>
  <div class="field">
    <label for="email">Email address</label>
    <input type="text" id="email" name="email" size="31" value="<?php echo old('email'); ?>"/><?php echo err('email'); ?>
  </div>
  <div class="field">
    <label for="passwd">Password</label>
    <input type="password" id="passwd" name="passwd" size="31"/><?php echo err('passwd'); ?>
  </div>
  <div class="field">
    <label for="address">Street Address</label>
    <textarea id="address" name="address" rows="3" cols="22"><?php echo old('address'); ?></textarea><?php echo err('address'); ?>
  </div>
  <div class="field">
    <label for="state">State</label>
    <select id="state" name="state">
      <option value="-1" <?php if ($selState === '-1') echo 'selected'; ?>>[choose your state]</option>
      <?php foreach ($states as $val => $name): ?>
      <option value="<?php echo $val; ?>" <?php if ($selState === (string)$val) echo 'selected'; ?>><?php echo $name; ?></option>
      <?php endforeach; ?>
    </select><?php echo err('state'); ?>
  </div>
  <div class="field">
    <input type="hidden" name="country" value="Australia"/>
  </div>
  <div class="field">
    <label>Gender</label>
    <span>
      <input type="radio" name="sex" value="male" <?php if ($sex === 'male') echo 'checked'; ?>/>Male
      <input type="radio" name="sex" value="female" <?php if ($sex === 'female') echo 'checked'; ?>/>Female
    </span><?php echo err('sex'); ?>
  </div>
  <div class="field">
    <label for="vegetarian">Vegetarian?</label>
    <input type="checkbox" id="vegetarian" name="vegetarian" <?php if (isset($_POST['vegetarian'])) echo 'checked'; ?>/>
  </div>
  <div class="field">
    <label>&nbsp;</label>
    <span><input type="submit" value="Submit" name="submit"/><input type="reset" value="Reset" name="reset"/></span>
  </div>
</form>
</fieldset>
