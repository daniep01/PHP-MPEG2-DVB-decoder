<?php

function decode_mjd($MJD)
{
	//echo $MJD;

	$Y1 = intval(($MJD - 15078.2) / 365.25);
	$M1 = intval(($MJD - 14956.1 - intval($Y1 * 365.25)) / 30.6001);
	$D = $MJD - 14956 - intval($Y1 * 365.25) - intval($M1 * 30.6001);

	if (($M1 == 14) | ($M1 == 15)) {
		$K = 1;
	} else {
		$K = 0;
	}

	$Y = $Y1 + $K + 1900;
	$M = $M1 - 1 - $K * 12;

	$datestring =
		sprintf("%02d/", $D) . sprintf("%02d/", $M) . sprintf("%02d", $Y);

	return $datestring;
}

?>
