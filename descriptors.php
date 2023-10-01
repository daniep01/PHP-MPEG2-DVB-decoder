<?php
function descriptor($hexbytes) {
		
		// if there are less than 2 bytes then there's no point
		if (count($hexbytes) < 2)
		{ print_error("Not enough data bytes"); return; }
		
	    $descriptor_header['descriptor_tag']['0'] = current($hexbytes);
		$descriptor_header['descriptor_tag']['1'] = decode_descriptor_id($descriptor_header['descriptor_tag']['0']);
		$descriptor_header['descriptor_length']['0'] = next($hexbytes);
		
		// now a fudge, to make testing life easier
		// if length byte is 255, then just count number of bytes and use that
		if($descriptor_header['descriptor_length']['0'] == 255)
			{
			$descriptor_header['descriptor_length']['0'] = count($hexbytes) - 2;
			$descriptor_header['descriptor_length']['1'] = "bytes (implicit)";
			}
		else
			{	
			$descriptor_header['descriptor_length']['1'] = "bytes (explicit)";
			}
			
		decode_print($descriptor_header,"header");	
		separator_print();
		
		// check length field matches number of bytes in array. why not.
		if ($descriptor_header['descriptor_length']['0'] != (count($hexbytes) - 2))
		{ print_error("Length field does not match number of data bytes"); return; }
		
switch ($descriptor_header['descriptor_tag']['0'])
{
	case 0x40:
		//network name
		$_40['network_name'] = "'";
		for ($i = 0; $i < $descriptor_header['descriptor_length']['0']; $i++)
		{
		$_40['network_name'] .= chr(next($hexbytes));
		}
		$_40['network_name'] .= "'";
		
		decode_print($_40,"header");
		separator_print();
	break;
		
	case 0x41:
		//service list 
		for ($i = 0; $i < $descriptor_header['descriptor_length']['0']; $i += 3)
		{
			$_41['service_id']   = (next($hexbytes) << 8) + next($hexbytes);
			$_41['service_type']['0'] = next($hexbytes);
			$_41['service_type']['1'] = decode_service_type($_41['service_type']['0']);
			decode_print($_41,"loop1");
			separator_print();
		}
	break;
		
	case 0x42:
		// stuffing
		for ($i = 0; $i < $descriptor_header['descriptor_length']['0']; $i += 1)
		{
			$_42['stuffing_byte']   = next($hexbytes);
			decode_print($_42,"loop1");
		}
			separator_print();
	break;		
		
	case 0x4A:
		//linkage
		$_4A['transport_stream_id']   = (next($hexbytes) << 8) + next($hexbytes);
		$_4A['original_network_id']   = (next($hexbytes) << 8) + next($hexbytes);
		$_4A['service_id']            = (next($hexbytes) << 8) + next($hexbytes);
		$_4A['linkage_type']['0']     = next($hexbytes);
		$_4A['linkage_type']['1']     =	decode_linkage_type($_4A['linkage_type']['0']);
		
		decode_print($_4A,"header");	
		separator_print();			
		
		if ($descriptor_header['descriptor_length']['0'] == 7)
		{
			$_4A_1['private_data_byte'] = "no private data";
			decode_print($_4A_1,"loop1");
		}
		
		if($_4A['linkage_type']['0'] != 8)
		for ($i = 0; $i < ($descriptor_header['descriptor_length']['0'] - 7); $i += 1)
		{
			$_4A_1['private_data_byte']   = next($hexbytes);
			decode_print($_4A_1,"loop1");
		}
		
		if($_4A['linkage_type']['0'] == 8)
		{
			$_4A_2['hand-over_type']['0'] = (next($hexbytes) & 240) >> 4;
			$_4A_2['hand-over_type']['1'] = decode_handover_type($_4A_2['hand-over_type']['0']);
			$_4A_2['reserved']            = (current($hexbytes) & 14) >> 1;
			$_4A_2['origin_type']['0']    = (current($hexbytes) & 1);
			$_4A_2['origin_type']['1']    = decode_origin_type($_4A_2['origin_type']['0']);
			decode_print($_4A_2,"loop1");
		}
		
		separator_print();			
		break;
			
			
	case 0x50:
		// component
		$_50['reserved'] = (next($hexbytes) & 240) >> 4;
		$_50['stream_content']['0'] = current($hexbytes) & 15;
		$_50['stream_content']['1'] = decode_stream_content($_50['stream_content']['0']);
		$_50['component_type'] = next($hexbytes);
		$_50['component_tag'] = next($hexbytes);
		
		$_50['language_code'] = null;
		$_50['text'] = "'";
		for ($i = 0; $i < 3; $i ++) {
			$_50['language_code'] .= chr(next($hexbytes)); 
		}
		for ($i = 0; $i < ($descriptor_header['descriptor_length']['0'] - 6); $i ++) {
			$_50['text'] .= chr(next($hexbytes)); 
		}
		$_50['text'] .= "'";
		
		decode_print($_50,"header");	
		separator_print();		
		
	break;
		
	case 0x52:
		//stream identifier
		$_52['component_tag'] = next($hexbytes);
		
		decode_print($_52,"header");	
		separator_print();		
		break;
		
	case 0x53:
		//CA identifier
		for ($i = 0; $i < $descriptor_header['descriptor_length']['0']; $i += 2)
		{
			$_53['CA_system_id']   = (next($hexbytes) << 8) + next($hexbytes);
			decode_print($_53,"loop1");
			separator_print();
		}
	break;
		
	case 0x54:
		// content
		for ($i = 0; $i < $descriptor_header['descriptor_length']['0']; $i += 2)
		{		
		$_54['content_nibble_level1']['0'] = (next($hexbytes) & 240) >> 4;
		$_54['content_nibble_level2'] = (current($hexbytes) & 15) ;
		
		$_54['user_nibble_1'] = (next($hexbytes) & 240) >> 4;
		$_54['user_nibble_2'] = (current($hexbytes) & 15) ;		
		
		$_54['content_nibble_level1']['1'] = decode_content_level1($_54['content_nibble_level1']['0']);
		
			decode_print($_54,"loop1");
			separator_print();
		}
				
	break;
		
	case 0x58:
		//local time offset
		for ($i = 0; $i < $descriptor_header['descriptor_length']['0']; $i += 13)
		{
			debug_print($i);
			$_58['country_code'] = null;
			for ($j = 0; $j < 3; $j ++) {
				$_58['country_code'] .= chr(next($hexbytes)); 
			}
			$_58['country_region_id'] = (next($hexbytes) & 252) >> 2;
			$_58['local_time_offset_polarity']['0'] = (current($hexbytes) & 1);
			if ($_58['local_time_offset_polarity']['0'] == 0) 
				{ $_58['local_time_offset_polarity']['1'] = "positive"; }
			if ($_58['local_time_offset_polarity']['0'] == 1) 
				{ $_58['local_time_offset_polarity']['1'] = "negative"; }
			$_58['local_time_offset'] = sprintf("%02x:",next($hexbytes)).sprintf("%02x",next($hexbytes));
	
			$start_date = (next($hexbytes) << 8) + next($hexbytes);
			$_58['date_of_change'] = decode_mjd($start_date);	
			$_58['time_of_change'] = sprintf("%02x:",next($hexbytes)).sprintf("%02x:",next($hexbytes)).sprintf("%02x",next($hexbytes));
			$_58['next_offset'] = sprintf("%02x:",next($hexbytes)).sprintf("%02x",next($hexbytes));			

			decode_print($_58,"loop1");
			separator_print();
			debug_print($i);
		}
		break;		
		
    case 0x48:
        //service descriptor
        $_48['service_type']['0'] = next($hexbytes);
        $_48['service_type']['1'] = decode_service_type($_48['service_type']['0']);
        $_48['provider_name_length'] = next($hexbytes);
        
 		$_48['provider_name'] = "'";       
 		for ($i = 0; $i < $_48['provider_name_length']; $i++)
		{
		$_48['provider_name'] .= chr(next($hexbytes));

		}
		$_48['provider_name'] .= "'";		
		
        $_48['service_name_length'] = next($hexbytes);
        
        $_48['service_name'] = "'";
 		for ($i = 0; $i < $_48['service_name_length']; $i++)
		{
		$_48['service_name'] .= chr(next($hexbytes));
		}
        $_48['service_name'] .= "'";
                
        decode_print($_48,"header");
		separator_print();
        break;
        
    case 0x5A:
    	//terrestrial delivery system
    	$f1 = (next($hexbytes)) << 24;
    	$f2 = (next($hexbytes)) << 16;
    	$f3 = (next($hexbytes)) << 8;
    	$f4 = (next($hexbytes));
    	
    	$_5A['centre_frequency']['0'] = (($f1 + $f2 + $f3 + $f4) * 10);
    	$_5A['centre_frequency']['1'] = "Hz";
    	$_5A['bandwidth']['0'] = (next($hexbytes) & 224) >> 5;
		switch ($_5A['bandwidth']['0'])
		{
			case 0:  $_5A['bandwidth']['1'] = "8Mhz"; break;
			case 1:  $_5A['bandwidth']['1'] = "7Mhz"; break;
			case 2:  $_5A['bandwidth']['1'] = "6Mhz"; break;
			case 3:  $_5A['bandwidth']['1'] = "5Mhz"; break;
			default: $_5A['bandwidth']['1'] = "reserved"; break;
		}
    	$_5A['priority']['0']  = (current($hexbytes) & 16) >> 4;
		if($_5A['priority']['0'] == 1) { $_5A['priority']['1'] = "High priority"; }
		if($_5A['priority']['0'] == 0) { $_5A['priority']['1'] = "Low priority"; }
		
    	$_5A['time_slicing_indicator']['0'] = (current($hexbytes) & 8) >> 3;
		if($_5A['time_slicing_indicator']['0'] == 1) { $_5A['time_slicing_indicator']['1'] = "Not used"; }
		if($_5A['time_slicing_indicator']['0'] == 0) { $_5A['time_slicing_indicator']['1'] = "Used"; }
				
    	$_5A['MPE-FEC_indicator']['0'] = (current($hexbytes) & 4) >> 2;
		if($_5A['MPE-FEC_indicator']['0'] == 1) { $_5A['MPE-FEC_indicator']['1'] = "Not used"; }
		if($_5A['MPE-FEC_indicator']['0'] == 0) { $_5A['MPE-FEC_indicator']['1'] = "Used"; }
		
    	// reserved 2 bits
    	$_5A['constellation']['0'] = (next($hexbytes) & 192) >> 6;
		switch($_5A['constellation']['0']) 
		{
			case 0:  $_5A['constellation']['1'] = "QPSK"; break;
			case 1:  $_5A['constellation']['1'] = "16-QAM"; break;
			case 2:  $_5A['constellation']['1'] = "64-QAM"; break;
			default: $_5A['constellation']['1'] = "reserved"; break;
		}
    	$_5A['hierarchy_information']['0'] = (current($hexbytes) & 56) >> 3;
		switch($_5A['hierarchy_information']['0'])
		{
			case 0:   $_5A['hierarchy_information']['1'] = "non-hierarchial, native interleaver"; break;
			case 1:   $_5A['hierarchy_information']['1'] = "a=1, native interleaver"; break;
			case 2:   $_5A['hierarchy_information']['1'] = "a=2, native interleaver"; break;
			case 3:   $_5A['hierarchy_information']['1'] = "a=4, native interleaver"; break;	
			case 4:   $_5A['hierarchy_information']['1'] = "non-hierarchial, id-depth interleaver"; break;	
			case 5:   $_5A['hierarchy_information']['1'] = "a=1, in-depth interleaver"; break;		
			case 6:   $_5A['hierarchy_information']['1'] = "a=2, in-depth interleaver"; break;	
			case 7:   $_5A['hierarchy_information']['1'] = "a=4, in-depth interleaver"; break;		
			default:  $_5A['hierarchy_information']['1'] = "unknown"; break;
		}
    	$_5A['code_rate-HP_stream']['0'] = (current($hexbytes) & 7);
		$_5A['code_rate-HP_stream']['1'] = decode_coderate($_5A['code_rate-HP_stream']['0']);
    	// next byte
    	$_5A['code_rate-LP_stream']['0'] = (next($hexbytes) & 224) >> 5;
		$_5A['code_rate-LP_stream']['1'] = decode_coderate($_5A['code_rate-LP_stream']['0']);
		
    	$_5A['guard_interval']['0'] = (current($hexbytes) & 24) >> 3;
		switch($_5A['guard_interval']['0'])
		{
			case 0:   $_5A['guard_interval']['1'] = "1/32"; break;
			case 1:   $_5A['guard_interval']['1'] = "1/16"; break;
			case 2:   $_5A['guard_interval']['1'] = "1/8"; break;
			case 3:   $_5A['guard_interval']['1'] = "1/4"; break;
			default:  $_5A['guard_interval']['1'] = "unknown"; break;
		}
    	$_5A['transmission_mode']['0'] = (current($hexbytes) & 6) >> 1;
		switch($_5A['transmission_mode']['0']) 
		{
			case 0:   $_5A['transmission_mode']['1'] = "2k mode"; break;
			case 1:   $_5A['transmission_mode']['1'] = "8k mode"; break;
			case 2:   $_5A['transmission_mode']['1'] = "4k mode"; break;
			case 3:   $_5A['transmission_mode']['1'] = "reserved for future use"; break;		
			default:  $_5A['transmission_mode']['1'] = "unknown"; break;
		}
    	$_5A['other_frequency_flag'] = (current($hexbytes) & 1);
    	//
    	$_5A['reserved 1'] = next($hexbytes);
    	$_5A['reserved 2'] = next($hexbytes);
    	$_5A['reserved 3'] = next($hexbytes);
    	$_5A['reserved 4'] = next($hexbytes);
    	
    	decode_print($_5A,"header");
		separator_print();
    	break;
		
	case 0x5F:
		//private data specifier
    	$pds1 = (next($hexbytes)) << 24;
    	$pds2 = (next($hexbytes)) << 16;
    	$pds3 = (next($hexbytes)) << 8;
    	$pds4 = (next($hexbytes));
		$pds  = $pds1 + $pds2 + $pds3 + $pds4;
		
		$_5F['private_data_specifier']['0'] = $pds;
		$_5F['private_data_specifier']['1'] = sprintf("%02X",$pds);
	
		decode_print($_5F,"header");
		separator_print();
		break;
		
	case 0x62:
		//frequency list
		//reserved 6 bits
		$_62['coding_type']['0'] = (next($hexbytes) & 3);
		switch($_62['coding_type']['0'])
		{
			case 0:   $_62['coding_type']['1'] = "not defined"; break;
			case 1:	  $_62['coding_type']['1'] = "satellite"; break;
			case 2:   $_62['coding_type']['1'] = "cable"; break;
			case 3:   $_62['coding_type']['1'] = "terrestrial"; break;
			default:  $_62['coding_type']['1'] = "unknown"; break;
		}
		decode_print($_62,"header");
		
		for ($i = 0; $i < (($descriptor_header['descriptor_length']['0'] - 1) / 4); $i ++)
		{
		    $f1 = (next($hexbytes)) << 24;
    		$f2 = (next($hexbytes)) << 16;
    		$f3 = (next($hexbytes)) << 8;
    		$f4 = (next($hexbytes));

			//$field_name = "centre_frequency_".($i+1);

			$_62_1['centre_frequency']['0'] = (($f1 + $f2 + $f3 + $f4) * 10);
			$_62_1['centre_frequency']['1'] = "Hz";
			decode_print($_62_1,"loop1");
			separator_print();
		}
		break;
		
	case 0x73:
		//default authority
		$_73['default_authority'] = "'";
		for ($i = 0; $i < $descriptor_header['descriptor_length']['0']; $i++)
		{
		$_73['default_authority'] .= chr(next($hexbytes));
		}
		$_73['default_authority'] .= "'";
		
		decode_print($_73,"header");
		separator_print();
	break;	
		
	case 0x47;
		// related content
		decode_print($_74,"header");
		separator_print();
	break;				
		
	case 0x76:
		// Content identifier descriptor
		// defined in ETSI TS 102 323 V1.3.1
		$_76['crid_type']['0'] = (next($hexbytes) & 252) >> 2;
		$_76['crid_location']['0'] = (current($hexbytes) & 3);
		
		$_76['crid_type']['1'] = decode_crid_type($_76['crid_type']['0']);
		$_76['crid_location']['1'] =decode_crid_location($_76['crid_location']['0']);
		
		switch($_76['crid_location']['0'])
		{
		case 0:
			$_76['crid_length'] = next($hexbytes);
			$_76['crid'] = "'";
			for ($i = 0; $i < $_76['crid_length']; $i ++)
			{
			$_76['crid'] .= chr(next($hexbytes));
			}
			$_76['crid'] .= "'";
			break;
		
		case 1:
			$_76['crid_ref'] = (next($hexbytes) << 8) + next($hexbytes);
			break;
		}
		
		decode_print($_76,"header");
		separator_print();		
		
	break;
		
	case 0x7E:
		// FTA_content_management_descriptor
		$_7E['reserved'] = (next($hexbytes) & 240) >> 4;
		$_7E['do_not_scramble'] = (current($hexbytes) & 8) >> 3;
		$_7E['control_remote_access_over_internet']['0'] = (current($hexbytes) & 6) >> 1;
		$_7E['control_remote_access_over_internet']['1'] = decode_control_remote_access_internet($_7E['control_remote_access_over_internet']['0']);
		$_7E['do_not_apply_revocation'] = (current($hexbytes) & 1);
		
		decode_print($_7E,"header");
		separator_print();		
				
	break;
	
	case 0x7F:
		//extension descriptor
		extension_descriptor($hexbytes,$descriptor_header['descriptor_length']['0']);
	break;
    
	case 0x83:
		//LCN DTG
		for ($i = 0; $i < $descriptor_header['descriptor_length']['0']; $i += 4)
		{
			$_83['service_id']   = (next($hexbytes) << 8) + next($hexbytes);
			$_83['logical_channel_number'] = ((next($hexbytes) & 3) << 8) + next($hexbytes);
			decode_print($_83,"loop1");
			separator_print();
		}
	break;
		
    default: break;
} // end longest ever switch


// all this next bit was used for multiple descriptors
//	separator_print();

// have we run out of data?
// this is dangerous if the current or next descriptor is not complete, we can
// get into a nasty loop from which there is no escape. Otherwise seems to work.
//if (key($hexbytes) != (count($hexbytes) - 1)) 
//{   // do it again
	//echo "<br>repeat";
	//next($hexbytes);
	//descriptor($hexbytes); 
//} 
}

function extension_descriptor($hexbytes,$length)
{	
	$E['descriptor_tag_extension']['0'] = next($hexbytes);
	$E['descriptor_tag_extension']['1'] = decode_ext_descriptor_id($E['descriptor_tag_extension']['0']);
	decode_print($E,"header");
	//echo $ext_tag;
	switch($E['descriptor_tag_extension']['0'])
	{
	case 7:
	
	$loop1_bytes_read = 1; // count the extension tag here
	while ($loop1_bytes_read < ($length )) :
	
	$E_07_1['cell_id'] = (next($hexbytes) << 8) + next($hexbytes);
	$E_07_1['loop_length'] = next($hexbytes);
	decode_print($E_07_1,"loop1");
	separator_print();
	$loop1_bytes_read += 3; // cell id and loop length
	$loop2_bytes_read = 0;  // reset 

	while ($loop2_bytes_read < $E_07_1['loop_length']) :
	$E_07_2['network_change_id'] = next($hexbytes);
	$E_07_2['network_change_version'] = next($hexbytes);	
	
	$start_date = (next($hexbytes) << 8) + next($hexbytes);
	
	$E_07_2['start_date'] = decode_mjd($start_date);
	$E_07_2['start_time'] = sprintf("%02x:",next($hexbytes)).sprintf("%02x:",next($hexbytes)).sprintf("%02x",next($hexbytes));
	$E_07_2['change_duration'] = sprintf("%02x:",next($hexbytes)).sprintf("%02x:",next($hexbytes)).sprintf("%02x",next($hexbytes));
	$E_07_2['receiver_category']['0']   = (next($hexbytes) & 224) >> 5;
	switch ($E_07_2['receiver_category']['0']) 
	{
		case 0: $E_07_2['receiver_category']['1'] = "all receivers"; break;
		case 1: $E_07_2['receiver_category']['1'] = "DVB-T2 or DVB-S2 or DVB-C2 capable receivers only"; break;
		default:$E_07_2['receiver_category']['1'] = "reserved for future use"; break;
	}
	$E_07_2['invariant_tsid_flag'] = (current($hexbytes) & 16) >> 4;
	$E_07_2['change_type']['0']    = (current($hexbytes) & 15);
	switch($E_07_2['change_type']['0'])
	{
		case 0x0: $E_07_2['change_type']['1'] = "Message only"; break;
		case 0x1: $E_07_2['change_type']['1'] = "Minor - default"; break;
		case 0x2: $E_07_2['change_type']['1'] = "Minor - multiplex removed"; break;
		case 0x3: $E_07_2['change_type']['1'] = "Minor - service changed"; break;
		case 0x8: $E_07_2['change_type']['1'] = "Major - default"; break;
		case 0x9: $E_07_2['change_type']['1'] = "Major - multiplex frequency changed"; break;
		case 0xA: $E_07_2['change_type']['1'] = "Major - multiplex coverage changed"; break;
		case 0xB: $E_07_2['change_type']['1'] = "Major - multiplex added"; break;
		default : $E_07_2['change_type']['1'] = "Reserved for other changes"; break;
	}
	$E_07_2['message_id']          = next($hexbytes);
	
	$loop2_bytes_read += 12;
	
	if($E_07_2['invariant_tsid_flag'] == 1)
	{
		$E_07_2['invariant_tsid'] = (next($hexbytes) << 8) + next($hexbytes);
		$E_07_2['invariant_onid'] = (next($hexbytes) << 8) + next($hexbytes);
		$loop2_bytes_read += 4;
	}
	decode_print($E_07_2,"loop2");
	separator_print();
	debug_print("loop2: ".$loop2_bytes_read);	
	endwhile; // end loop 2 - cells
	debug_print("endwhile loop 2");
	$loop1_bytes_read += $loop2_bytes_read; // add loop2 to loop1 total
	debug_print("loop1: ".$loop1_bytes_read);	
	endwhile; // end loop 1 - network change events
	debug_print("endwhile loop 1");
	break;
	
	case 8:
	$E_08['message_id'] = next($hexbytes);
	$E_08['language_code'] = null;
	$E_08['message'] = "'";
	for ($i = 0; $i < 3; $i ++) {
			$E_08['language_code'] .= chr(next($hexbytes)); 
	}
	for ($i = 0; $i < ($length - 5); $i ++) {
			$E_08['message'] .= chr(next($hexbytes)); 
	}
	$E_08['message'] .= "'";
	decode_print($E_08,"header");
	separator_print();
	break;

	default: break;
	}
}

?>