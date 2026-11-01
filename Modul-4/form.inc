<style>
    body { font-family: sans-serif; }
    fieldset { border: 1px solid #c0b6d8; width: 450px; background-color: #efebff; padding: 10px; }
    legend { font-weight: bold; color: #5a4b8c; }
    .field { margin-bottom: 5px; clear: both; }
    label { float: left; width: 130px; text-align: right; margin-right: 10px; color: #5a4b8c; font-size: 13px; }
    input[type="text"], input[type="password"], textarea, select { width: 150px; }
</style>

<h2>Register</h2>
<fieldset>
    <legend>Person Details</legend>

    <!-- Perhatikan bahwa action dikosongkan atau diisi nama file PHP utamanya (Self-Submission) -->
    <form action="" method="POST">
        <div class="field">
            <label for="surname">Surname</label>
            <!-- Menambahkan PHP untuk menampilkan kembali isi surname jika sudah pernah diisi -->
            <input type="text" name="surname" id="surname" value="<?php if(isset($_POST['surname'])) echo $_POST['surname']; ?>">
        </div>
        
        <div class="field">
            <label for="email">Email address</label>
            <input type="text" name="email" id="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>">
        </div>
        
        <div class="field">
            <label for="password">Password</label>
            <input type="password" name="password" id="password">
        </div>
        
        <div class="field">
            <label for="address">Street Address</label>
            <!-- Untuk textarea, PHP diletakkan di antara tag pembuka dan penutup -->
            <textarea name="address" id="address" rows="3"><?php if(isset($_POST['address'])) echo $_POST['address']; ?></textarea>
        </div>
        
        <div class="field">
            <label for="state">State</label>
            <select name="state" id="state">
                <option value="ACT">Australian Capital Territory</option>
                <option value="NSW">New South Wales</option>
                <option value="NT">Northern Territory</option>
                <option value="QLD">Queensland</option>
                <option value="SA">South Australia</option>
                <option value="TAS">Tasmania</option>
                <option value="VIC">Victoria</option>
                <option value="WA">Western Australia</option>
            </select>
        </div>
        
        <input type="hidden" name="country" value="Australia">
        
        <div class="field">
            <label>Gender</label>
            <input type="radio" name="gender" value="Male" checked> Male 
            <input type="radio" name="gender" value="Female"> Female
        </div>
        
        <div class="field">
            <label for="vegetarian">Vegetarian?</label>
            <input type="checkbox" name="vegetarian" id="vegetarian" value="Yes">
        </div>
        
        <div class="field" style="margin-left: 140px; margin-top: 10px;">
            <input type="submit" value="Submit">
            <input type="reset" value="Reset">
        </div>
    </form>
</fieldset>