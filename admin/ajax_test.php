<?php
include_once("../common.php");
	if(isset($_POST['pLat']) && isset($_POST['pLng']))
	{
		$pLat = $_POST['pLat'];
		$pLng = $_POST['pLng'];

		$prad = isset($_POST['prad']) ? $_POST['prad'] : '25';
		if(isset($prad) && $prad == '5')
		{
			$prad = '25';
		}

		$sql2 = "SELECT ROUND(( 3959 * acos( cos( radians($pLat) )
		* cos( radians( vLatitude ) )
		* cos( radians( vLongitude ) - radians($pLng) )
		+ sin( radians($pLat) )
		* sin( radians( vLatitude ) ) ) ),2) AS distance, register_driver.*  FROM register_driver
		WHERE (vLatitude != '' AND vLongitude != '' AND vAvailability = 'Available' AND vTripStatus != 'Active' AND eStatus='active')
		HAVING distance < $prad ORDER BY distance DESC";
	}
	else
	{
		$sql2 = "select * FROM register_driver WHERE 1 AND eStatus='active' ORDER BY vName ASC";
	}

	$db_records_online = $obj->MySQLSelect($sql2);
	$count = count($db_records_online);
	if($count > 0)
	{
		$test = array('success' => 'true', 'result' => $db_records_online);

	}
	else
	{
		$test = array('success' => 'false', 'result' => 'no results found');
	}
 echo  json_encode($test);
?>
