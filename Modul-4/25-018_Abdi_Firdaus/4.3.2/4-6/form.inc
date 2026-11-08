<form name="myForm" action="processData_form.php" method="POST">
    <fieldset>
        <legend>Person Details</legend>

        <p>
            <label for="surname">Surname:</label>
            <input type="text" id="surname" name="surname">
        </p>

        <p>
            <label for="email">Email address:</label>
            <input type="text" id="email" name="email">
        </p>

        <p>
            <label for="passwd">Password:</label>
            <input type="password" id="passwd" name="passwd">
        </p>

        <p>
            <label for="address">Street Address:</label>
            <textarea id="address" name="address"
                      rows="3" cols="22"></textarea>
        </p>

        <p>
            <label for="state">State:</label>
            <select id="state" name="state">
                <option value="-1" selected>[choose your state]</option>
                <option value="1">Australian Capital Territory</option>
                <option value="2">Queensland</option>
                <option value="3">Victoria</option>
                <option value="4">New South Wales</option>
                <option value="5">Tasmania</option>
                <option value="6">South Australia</option>
                <option value="7">Western Australia</option>
                <option value="8">Northern Territory</option>
            </select>
        </p>

        <input type="hidden" name="country" value="Australia">

        <p>
            Gender:
            <input type="radio" name="sex" value="male"> Male
            <input type="radio" name="sex" value="female"> Female
        </p>

        <p>
            <label for="vegetarian">Vegetarian?</label>
            <input type="checkbox" id="vegetarian" name="vegetarian">
        </p>

        <p>
            <input type="submit" name="submit" value="Submit">
            <input type="reset" name="reset" value="Reset">
        </p>

    </fieldset>
</form>