<form name="myForm" action="processData_form.php" method="POST">
    <div class="field">
        <label for="surname">Surname</label>
      
        <input type="text" name="surname" id="surname" size="35" value="<?php if(isset($_POST['surname'])) echo $_POST['surname']; ?>" />
    </div>
    <div class="field">
        <label for="email">Email address</label>
   
        <input type="text" name="email" id="email" size="35" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>" />
    </div>
    <div class="field">
        <label for="passwd">Password</label>

        <input type="password" name="passwd" id="passwd" size="35" />
    </div>
    <div class="field">
        <label for="address">Street Address</label>
    
        <textarea name="address" id="address" rows="3" cols="22"><?php if(isset($_POST['address'])) echo $_POST['address']; ?></textarea>
    </div>
    <div class="field">
        <label for="state">State</label>
        <select name="state" id="state">
            <option value="1">Choose your state</option>
            <option value="2">Australian Capital Territory</option>
            <option value="3">Queensland</option>
            <option value="4">Victoria</option>
        </select>
    </div>
    <div class="field">
        <input type="hidden" name="country" value="Australia" />
    </div>
    <div class="field">
        <label>Gender</label>
        <input type="radio" name="sex" value="Male" id="male" /> <label for="male" style="float:none; width:auto; text-align:left;">Male</label>
        <input type="radio" name="sex" value="Female" id="female" /> <label for="female" style="float:none; width:auto; text-align:left;">Female</label>
    </div>
    <div class="field">
        <label for="vegetarian">Vegetarian?</label>
        <input type="checkbox" name="vegetarian" id="vegetarian" />
    </div>
    <div class="field">
        <label>&nbsp;</label>
        <input type="submit" value="Submit" name="submit" /> <input type="reset" value="Reset" name="reset" />
    </div>
</form>