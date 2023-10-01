<?php

function decode_print($source,$class) {
	
	reset($source);
	// build table from array 
	while (list($key, $value) = each($source)) {
		// first column key is the field name
		echo "<tr><td class=\"$class\">$key</td>";
		// check for extra data
		if (is_array($value)) {
			foreach ($value as $key2 => $value2) {
			// third column
			echo "<td>$value2</td>";  
			}
		} 
		else 
		{	// second column
			echo "<td>$value</td>";
			//printf("<td>0x%02X</td>", $value);
			echo "<td></td>";
		}
		
	} // end while
	echo "</tr>";
}

function source_print($hexbytes) {
	echo "<div id=\"source\"><div id=\"fixed\">";
	$i = 0;
	foreach ($hexbytes as $index => $byte)
	{

		// new line after 16
		if(($i % 16) == 0 && ($i != 0)) {
			echo "<br/>";
		}
		$i ++;
		
		printf("%02X ", $byte);
	}

	echo "<br/>";
	echo count($hexbytes);
	echo " bytes.";
	echo "</div></div>";
}

function array_print($hexbytes) {

	echo "<tr><td>Index</td><td>Dec</td><td>Hex</td><td>Binary</td><td>ASCII</td>";
	separator_print();
foreach ($hexbytes as $index => $byte)
	{
	//hexdec($byte);
	//settype($byte, "integer");
	echo "<tr><td>$index</td>";
	printf("<td>%d</td>", $byte);	    // decimal
	printf("<td>0x%02X</td>", $byte);	// hex
	printf("<td>%08b</td>", $byte);       // binary
	printf("<td>%c</td>", $byte);       // ascii
	echo "</tr>"; 
	}
}

function separator_print() 
{
	echo "<tr><td colspan=5 class=\"sep\"> </td></tr>";
}

function debug_print($value) 
{
	global $debug;
	if($debug == true)
	{
		echo "<tr><td colspan=3 class=\"debug\">$value</td></tr>";
	}
}

function print_error($message) 
{
	echo "<tr><td colspan=3 class=\"error\">Error in data, cannot decode! $message";
	echo "</td></tr>";
	separator_print();
}
 
function print_footer($linkedin) 
{
	echo "<div id=\"footer\">ISO/IEC 13818, DVB ETSI EN 300 468, DTG D-Book";
	echo "<br>&copy; Peter Daniel. ";
	echo date ("d F Y H:m", getlastmod());
	echo "</div>";
	if($linkedin == true)
	{
	?>
	<script src="//platform.linkedin.com/in.js" type="text/javascript"></script>
	<script type="IN/Share" data-counter="right"></script>
	<?php
	}
	echo "</body></html>";
}




?>
