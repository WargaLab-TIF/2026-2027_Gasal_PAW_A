<?php
function old($name) {
    return isset($_POST[$name]) ? htmlspecialchars($_POST[$name], ENT_QUOTES) : '';
}
$st = old('state');
?>
<fieldset>
<legend>Person Details</legend>
<form name="myForm" action="processData_form.php" method="POST">
  <div class="field">
    <label for="surname">Surname</label>
    <input type="text" name="surname" size="31" value="<?= old('surname') ?>"/>
  </div>
  <div class="field">
    <label for="email">Email address</label>
    <input type="text" name="email" size="31" value="<?= old('email') ?>"/>
  </div>
  <div class="field">
    <label for="passwd">Password</label>
    <input type="password" name="passwd" size="31"/>
  </div>
  <div class="field">
    <label for="address">Street Address</label>
    <textarea name="address" rows="3" cols="22"><?= old('address') ?></textarea>
  </div>
  <div class="field">
    <label for="state">State</label>
    <select name="state">
      <option value="-1" <?= ($st === '' || $st === '-1') ? 'selected' : '' ?>>[choose your state]</option>
      <?php
      $states = array(1=>'Australian Capital Territory', 2=>'Queensland', 3=>'Victoria',
                      4=>'New South Wales', 5=>'Tasmania', 6=>'South Australia',
                      7=>'Western Australia', 8=>'Northern Territory');
      foreach ($states as $val => $label)
          echo '<option value="'.$val.'"'.($st == (string)$val ? ' selected' : '').">$label</option>\n      ";
      ?>
    </select>
  </div>
  <div class="field">
    <input type="hidden" name="country" value="Australia">
  </div>
  <div class="field">
    <label for="sex">Gender</label>
    <input type="radio" name="sex" value="male" <?= old('sex') === 'male' ? 'checked' : '' ?>/>Male
    <input type="radio" name="sex" value="female" <?= old('sex') === 'female' ? 'checked' : '' ?>/>Female
  </div>
  <div class="field">
    <label for="vegetarian">Vegetarian?</label>
    <input type="checkbox" name="vegetarian" <?= isset($_POST['vegetarian']) ? 'checked' : '' ?> />
  </div>
  <div class="field">
    <label>&nbsp;</label>
    <input type="submit" value="Submit" name="submit" /><input type="reset" value="Reset" name="reset" />
  </div>
</form>
</fieldset>
