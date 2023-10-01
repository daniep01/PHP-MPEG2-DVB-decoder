<?php
function decode_PID($value)
{
	//echo $value;
	switch ($value) {
		case 0:
			return "PAT";
		case 1:
			return "CAT";
		case 2:
			return "TSDT";
		case 16:
			return "NIT";
		case 17:
			return "SDT/BAT";
		case 18:
			return "EIT";
		case 19:
			return "RST";
		case 20:
			return "TDT/TOT";
		case 8191:
			return "null";
	}
}

function decode_descriptor_id($value)
{
	//echo $value;
	switch ($value) {
		case 0x40:
			return "Network name descriptor";
		case 0x41:
			return "Service list descriptor";
		case 0x42:
			return "Stuffing descriptor"; // 12/07/09
		case 0x48:
			return "Service descriptor";
		case 0x4a:
			return "Linkage descriptor"; // 12/07/09
		case 0x50:
			return "Component descriptor"; // 13/07/09
		case 0x52:
			return "Stream identifier descriptor";
		case 0x53:
			return "CA identifier descriptor";
		case 0x54:
			return "Content descriptor"; // 12/07/09
		case 0x58:
			return "Local time offset descriptor";
		case 0x5a:
			return "Terrestrial delivery descriptor";
		case 0x5f:
			return "Private data specifier";
		case 0x62:
			return "Frequency list descriptor";
		case 0x73:
			return "Default authority descriptor";
		case 0x74:
			return "Related content descriptor";
		case 0x76:
			return "Content identifier descriptor"; // 13/07/09
		case 0x7e:
			return "FTA content management descriptor"; // 12/07/09
		case 0x7f:
			return "Extension descriptor";
		case 0x83:
			return "DTG LCN descriptor";
		default:
			return "unknown descriptor tag";
	}
}

function decode_ext_descriptor_id($value)
{
	// echo $value;
	switch ($value) {
		case 0x07:
			return "Network change descriptor";
		case 0x08:
			return "Message descriptor";
		default:
			return "unknown descriptor tag";
	}
}

function decode_service_type($value)
{
	// EN 300 468 v 1.9.1
	switch ($value) {
		case 0x01:
			return "digital television service";
		case 0x02:
			return "digital radio sound service";
		case 0x03:
			return "Teletext service";
		case 0x04:
			return "NVOD reference service";
		case 0x05:
			return "NVOD time-shifted service";
		case 0x06:
			return "mosaic service";

		case 0x0a:
			return "advanced codec digital radio sound service";
		case 0x0b:
			return "advanced codec mosaic service";
		case 0x0c:
			return "data broadcast service";
		case 0x0d:
			return "reserved for Common Interface Usage";
		case 0x0e:
			return "RCS Map";
		case 0x0f:
			return "RCS FLS";
		case 0x10:
			return "DVB MHP service";
		case 0x11:
			return "MPEG-2 HD digital television service";

		case 0x16:
			return "advanced codec SD digital television service";
		case 0x17:
			return "advanced codec SD NVOD time-shifted service";
		case 0x18:
			return "advanced codec SD NVOD reference service";
		case 0x19:
			return "advanced codec HD digital television service";
		case 0x1a:
			return "advanced codec HD NVOD time-shifted service";
		case 0x1b:
			return "advanced codec HD NVOD reference service";

		default:
			return "reserved for future use/user defined";
	}
}

function decode_coderate($value)
{
	switch ($value) {
		case 0:
			return "1/2";
		case 1:
			return "2/3";
		case 2:
			return "3/4";
		case 3:
			return "5/6";
		case 4:
			return "7/8";
		default:
			return "reserved for future use";
	}
}

function decode_linkage_type($value)
{
	// EN 300 468 v1.9.1
	switch ($value) {
		case 0:
			return "reserved for future use";
		case 1:
			return "information service";
		case 2:
			return "EPG service";
		case 3:
			return "CA replacement service";
		case 4:
			return "TS containing complete Network/Bouquet SI";
		case 5:
			return "service replacement service";
		case 6:
			return "data broadcast service";
		case 7:
			return "RCS map";
		case 8:
			return "mobile hand over";
		case 9:
			return "System Software Update Service";
		case 0x0a:
			return "TS containing SSU BAT or NIT";
		case 0x0b:
			return "IP/MAC Notification Service";
		case 0x0c:
			return "TS containing INT BAT or NIT";
		default:
			return "reserved for future use";
	}
}

function decode_handover_type($value)
{
	// EN 300 468 v1.9.1
	switch ($value) {
		case 0:
			return "reserved for future use";
		case 1:
			return "DVB hand-over to an identical service in a neighbouring country";
		case 2:
			return "DVB hand-over to a local variation of the same service";
		case 3:
			return "DVB hand-over to an associated service";
		default:
			return "reserved for future use";
	}
}

function decode_origin_type($value)
{
	// EN 300 468 v1.9.1
	switch ($value) {
		case 0:
			return "NIT";
		case 1:
			return "SDT";
	}
}

function decode_stream_content($value)
{
	// EN 300 468 v1.9.1
	switch ($value) {
		case 1:
			return "MPEG-2 video";
		case 2:
			return "MPEG-1 audio";
		case 3:
			return "Subtitles";
		case 4:
			return "AC-3 audio";
		case 5:
			return "H.264/AVC video";
		case 6:
			return "HE-AAC audio";
		case 7:
			return "DTS audio";
		default:
			return "reserved for future use";
	}
}

function decode_content_level1($value)
{
	// EN 300 468 v1.9.1
	switch ($value) {
		case 0:
			return "Undefined";
		case 1:
			return "Movie/Drama";
		case 2:
			return "News/Current affairs";
		case 3:
			return "Show/Game show";
		case 4:
			return "Sports";
		case 5:
			return "Children's/Youth programmes";
		case 6:
			return "Music/Ballet/Dance";
		case 7:
			return "Arts/Culture (without music)";
		case 8:
			return "Social/Political issues/Economics";
		case 9:
			return "Education/Science/Factual topics";
		case 0x0a:
			return "Leisure hobbies";
		case 0x0b:
			return "Special characteristics";
		default:
			return "reserved for future use";
	}
}

function decode_control_remote_access_internet($value)
{
	// EN 300 468 v1.9.1
	switch ($value) {
		case 0:
			return "Redistribution over the Internet is enabled.";
		case 1:
			return "Redistribution over the Internet is enabled but only within a managed 
domain.";
		case 2:
			return "Redistribution over the Internet is enabled but only within a managed 
domain and after a certain short period of time (e.g. 24 hours).";
		case 3:
			return "Redistribution over the Internet is not allowed with the following 
exception. Redistribution over the Internet within a managed domain is enabled after a specified long (possibly indefinite) period of time.";
		default:
			return "unknown";
	}
}

function decode_crid_type($value)
{
	// ETSI TS 102 323 V1.3.1
	switch ($value) {
		case 0:
			return "No type defined.";
		case 1:
			return "CRID references the item of content that this event is an instance of.";
		case 2:
			return "CRID references a series that this event belongs to.";
		case 3:
			return "CRID references a recommendation. This CRID can be a group or a single item of content.";

		// D-Book 6.0
		case 0x31:
			return "DTG programme CRID";
		case 0x32:
			return "DTG series CRID";
		case 0x33:
			return "DTG recommendation CRID";

		default:
			return "Reserved";
	}
}

function decode_crid_location($value)
{
	// ETSI TS 102 323 V1.3.1
	switch ($value) {
		case 0:
			return "Carried explicitly within descriptor.";
		case 1:
			return "Carried in Content Identifier Table (CIT).";
		case 2:
			return "DVB reserved";
		case 3:
			return "DVB reserved";

		default:
			return "Reserved";
	}
}

?>
