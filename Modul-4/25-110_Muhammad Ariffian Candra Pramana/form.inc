<fieldset>
    <legend>Detail Identitas</legend>
    <form action="processData_form.php" method="post">
        <table>
            <tr>
                <td><label for="nama">Nama Lengkap:</label></td>
                <td><input type="text" name="nama" id="nama" size="31"></td>
            </tr>
            <tr>
                <td><label for="email">Email:</label></td>
                <td><input type="text" name="email" id="email" size="31"></td>
            </tr>
            <tr>
                <td><label for="password">Password:</label></td>
                <td><input type="password" name="password" id="password" size="31"></td>
            </tr>
            <tr>
                <td><label for="alamat">Alamat:</label></td>
                <td><textarea name="alamat" id="alamat" rows="2" cols="25"></textarea></td>
            </tr>
            <tr>
                <td><label for="kewarganegaraan">Kewarganegaraan:</label></td>
                <td><select name="kewarganegaraan" id="kewarganegaraan">
                        <option value="-1" selected disabled hidden>[pilih negara anda]</option>
                        <option value="1">Indonesia</option>
                        <option value="2">Malaysia</option>
                        <option value="3">Singapura</option>
                        <option value="4">Jerman</option>
                        <option value="5">Rusia</option>
                    </select></td>
            </tr>
            <tr>
                <td><input type="hidden" name="negara" id="negara" value="Australia"></td>
            </tr>
            <tr>
                <td><label for="jns_kelamin">Jenis Kelamin:</label></td>
                <td><input type="radio" name="jns_kelamin" value="L"> Laki-laki <br>
                    <input type="radio" name="jns_kelamin" value="P"> Perempuan
                </td>
            </tr>
            <tr>
                <td><label for="vegan">Vegan ?</label></td>
                <td><input type="checkbox" name="vegan" id="vegan"></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><button type="submit" value="submit" name="submit">Submit</button> <button type="reset" value="reset" name="reset">Reset</button></td>
            </tr>
        </table>
    </form>
</fieldset>