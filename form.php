<form method="post" action="decode.php" class="cmxform">
<fieldset>

<ol>

<li>
<label for="rawhex">Hex bytes:</label>
<textarea name="rawhex" id="rawhex" rows="6" cols="60" title="Enter hex bytes.">
<?php if (isset($_POST["rawhex"])) {
	echo $_POST["rawhex"];
	echo "</textarea>";
} else {
	echo "</textarea>";
} ?>

</li>

<li>
<label for="type">Decode as:</label>
<select name="type" id="type">

<?php foreach ($mode as $key => $value) {
	echo "<option ";

	if (isset($_POST["rawhex"]) && $_POST["type"] == $key) {
		echo "selected ";
	}

	echo "value=\"$key\">";
	echo $value;
	echo "</option>";
} ?>

</select>

<input type="submit" name="decode" value="decode">
</li>

</ol>
</fieldset>
</form>

