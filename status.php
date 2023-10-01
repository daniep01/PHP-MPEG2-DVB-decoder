<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<link rel="stylesheet" href="mpeg.css" type="text/css" />
<link rel="stylesheet" href="form.css" type="text/css" />
<title>MPEG/DVB hex byte decoder</title>
</head>
<body>

<h1>MPEG/DVB decoder</h1>
<div id="footer"><a href="decode.php">Back</a></div>
<?php
require "lookup.php";
require "print.php";

echo "<table id=\"decode\"><thead><tr>";
echo "<th colspan=2>These descriptors can be decoded</th></tr></thead>";
echo "<tbody>";

for ($i = 1; $i < 256; $i++) {
	if (decode_descriptor_id($i) != "unknown descriptor tag") {
		printf("<tr><td>%X</td>", $i);
		echo "<td>";
		echo decode_descriptor_id($i);
		echo "</td></tr>";
	}
}

echo "<thead><tr>";
echo "<th colspan=2>These extended descriptors can be decoded</th></tr></thead>";
echo "<tbody>";

for ($i = 1; $i < 256; $i++) {
	if (decode_ext_descriptor_id($i) != "unknown descriptor tag") {
		printf("<tr><td>%X</td>", $i);
		echo "<td>";
		echo decode_ext_descriptor_id($i);
		echo "</td></tr>";
	}
}

echo "</tbody></table>";

print_footer();
?>

</body>
</html>
