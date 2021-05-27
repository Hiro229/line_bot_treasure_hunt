<?php

function directions($org_lat, $org_lon, $db_lat, $db_lon) {
	// google map directions api url
	$url = "https://maps.googleapis.com/maps/api/directions/json";
	
	$curl = curl_init();
	if ($curl == FALSE) {
	    fputs(STDERR, "[ERR] curl_init(): " . curl_error($curl) . PHP_EOL);
	    die(1);
	}
	
	// 出発地点
	// line botからの位置情報を入れる
	$origin = $org_lat . ',' .  $org_lon;
	// 目的地点
	$destination = $db_lat . ',' . $db_lon;
	// google api key
	require_once('google_api_key.php');
	// 移動手段
	$mode = 'walking';
					
	curl_setopt($curl, CURLOPT_URL, $url . '?origin=' . $origin . '&destination=' . $destination . '&mode='. $mode .     '&key=' . $api_key);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	
	$response = curl_exec($curl);
	if ($response == FALSE) {
	    fputs(STDERR, "[ERR] curl_exec(): " . curl_error($curl) . PHP_EOL);
	    die(1);
	}
	curl_close($curl);
	
	// json decode
	$json_decode = json_decode($response, true);
	if ($json_decode == NULL) {
	    fputs(STDERR, "[ERR] json_decode(): " . json_last_error_msg() . PHP_EOL);
	    die(1);
	}
	// output json_decode
	if (!$json_decode['geocoded_waypoints'][0]['geocoder_status'] === 'OK') {
	    fputs(STDERR,"[ERROR] : " . $json_decode["error"] . PHP_EOL);
	    die(1);
	}
	$duration = $json_decode['routes'][0]['legs'][0]['duration']['value'];
	return $duration = round($duration / 60);
}

//$org_lat = 35.9062039;
//$org_lon = 139.6237359;
//$db_lat = 35.66092300754398;
//$db_lon = 139.73013236381234;
//$duration = directions($org_lat, $org_lon, $db_lat, $db_lon);
//var_dump(round($duration));
?>
