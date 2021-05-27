<?PHP

function vision($image_path) {
	//$image_path = './vision/send_image.jpg';

	// APIキー
	// google api key
	require_once('google_api_key.php');

	// リクエスト用のJSONを作成
	$json = json_encode( array(
		"requests" => array(
			array(
				"image" => array(
					"content" => base64_encode($image_path) ,
				) ,
				"features" => array(
					array(
						"type" => "LABEL_DETECTION",
						//"type" => "TEXT_DETECTION" ,
						"maxResults" => 10,
					) ,
				) ,
			) ,
		) ,
	) ) ;

	// リクエストを実行
	$curl = curl_init() ;
	curl_setopt($curl, CURLOPT_URL, "https://vision.googleapis.com/v1/images:annotate?key=" . $api_key ) ;
	curl_setopt($curl, CURLOPT_HEADER, true) ;
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST") ;
	curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/json")) ;
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	if( isset($referer) && !empty($referer)) curl_setopt($curl, CURLOPT_REFERER, $referer ) ;
	curl_setopt($curl, CURLOPT_TIMEOUT, 15);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $json) ;
	$res1 = curl_exec($curl) ;
	$res2 = curl_getinfo($curl) ;
	curl_close($curl) ;

	// 取得したデータ
	$json = substr($res1, $res2["header_size"]) ;
	$labels = json_decode($json, true);
	$labels_array = [];
	for ($i = 0;$i <= 9; $i++) {
		if (!$labels["responses"]["0"]["labelAnnotations"][$i]['description']) {
			break;
		}
		array_push($labels_array, $labels["responses"]["0"]["labelAnnotations"][$i]['description']);
	}

	return $labels_array;
}
//$label = vision();
//var_dump($label);
?>
