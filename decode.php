<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<link rel="stylesheet" href="mpeg.css" type="text/css" >
<link rel="stylesheet" href="form.css" type="text/css" >
<meta name="description" content="Use this simple online tool to decode MPEG2 TS packet headers and DVB/MPEG2 descriptors.">
<meta name="keywords" content="
Network name descriptor, Service list descriptor, Stuffing descriptor, Service descriptor, Linkage descriptor, Component descriptor, Stream identifier descriptor, CA identifier descriptor, Content descriptor, Local time offset descriptor, Terrestrial delivery descriptor, Private data specifier, Frequency list descriptor, Default authority descriptor, Content identifier descriptor, FTA content management descriptor, Extension descriptor, DTG LCN descriptor, mpeg, mpeg-2, dvb, avchd, bluray, m2t, m2ts, televsion, radio, digital televsion, set top box, dtt, dsat, cable, transport stream, ts, sync byte, 0x47, 47, transport error indicator, payload start indicator, transport priority, pid, transport scrambling control, adaption field control, continuity counter, pat, pmt, sdt, nit, sections, bat, eit, tdt, tot, rst, pes, peter daniel, pjdaniel, ts samples, anlyser, analyzer, mp2, m2v, video, audio, system, mpeg video, gop, bluray, m2t, m2ts">
<title>MPEG/DVB hex byte decoder</title>
</head>
<body>

<h1>Online MPEG2/DVB decoder</h1>

<div class="header">
<a href="status.php">About</a> | <a href="http://www.pjdaniel.org.uk/mpeg/">Download TS packet analyser for Windows</a>
</div>
<div class="header">Use this simple online tool to decode MPEG2 TS packet headers and DVB/MPEG2 descriptors. Type or paste hex bytes into the box below. Either put a space between each byte (1 2 A B), or enter 2 character bytes without spaces (01020A0B). For descriptors the length will be calculated automatically if you use FF as the length.
</div>
<?php 

$debug = false;
$version = "1.00";

$mode[1] = "TS packet header";
$mode[2] = "Descriptor";
$mode[3] = "Debug (hex)";
$mode[4] = "Debug (dec)";
//$mode[3] = "Table";

require("form.php");
require("lookup.php");
require("mjd.php");
require("descriptors.php");
require("print.php");

// get data from form
if(isset($_POST['rawhex'])) 
	{ $rawhex = $_POST['rawhex']; } 
else 
	{ print_footer(true); exit(); }

// get decoding type from form drop down
if(isset($_POST['type'])) { $type = $_POST['type']; }

// check input is not empty
if ($rawhex == "") { print_footer(true); exit(); }

// remove spaces at start or end
$rawhex = trim($rawhex,"\x20");

// are there commas between bytes?
if (substr_count($rawhex, ',') != 0) {
	// lets convert to spaces
	$rawhex = preg_replace('/[,]+/', ' ', trim($rawhex));
	}

// are there spaces between bytes?
if (substr_count($rawhex, ' ') == 0) {
	// lets add the spaces
	$rawhex = chunk_split($rawhex, 2, " ");
	$rawhex = trim($rawhex,"\x20");
	}
	
// are there commas between bytes?
if (substr_count($rawhex, ',') != 0) {
	// lets add the spaces
	$rawhex = chunk_split($rawhex, 2, " ");
	$rawhex = trim($rawhex,"\x20");
	}

// split into an array and convert to decimal
$hexbytes = explode(' ', $rawhex);

foreach ($hexbytes as $index => $value)
{
	$hexbytes[$index] = hexdec($value);
	$decbytes[$index] = $value;
}

source_print($hexbytes);
source_print($decbytes);

echo "<table id=\"decode\"><thead><tr>";
echo "<th colspan=5>$mode[$type]</th></tr></thead>";
echo "<tbody>";

reset($hexbytes);

switch ($type)
{
	case '4':
		//debug dec
		array_print($decbytes);
		break;
	case '3':
		//debug hex
		array_print($hexbytes);
		break;
    case '1': 
        //ts packet
		ts_packet($hexbytes);
        break;
    case '2':
    	//descriptor
    	descriptor($hexbytes);
    	break;
    default:
        //unknown
        break;
}

echo "</tbody></table>";
print_footer(false);

function ts_packet($hexbytes) {
		// decode
		$ts_header['Sync'] = $hexbytes[0];
		$ts_header['ts_error'] = ($hexbytes[1] & 128) >> 7;
		$ts_header['payload_start'] = ($hexbytes[1] & 64) >> 6;		
		$ts_header['ts_priority'] = ($hexbytes[1] & 32) >> 5;
		$ts_header['PID']['0'] = (($hexbytes[1] & 31) << 8) + $hexbytes[2];
		$ts_header['PID']['1'] = decode_PID($ts_header['PID']['0']);
		$ts_header['scrambling_control'] = ($hexbytes[3] & 192) >> 6;
		$ts_header['adaption_field_control'] = ($hexbytes[3] & 48) >> 4;
		$ts_header['continuity_counter'] = ($hexbytes[3] & 15);
		
		decode_print($ts_header,"header");
}

function table($hexbytes) 
{
		// decode
		$table['table_id'] = $hexbytes[0];
		//decode_print($table,"header");
}


?>

