<?php
/**
 * ２地点間の距離(m)を求める
 * ヒュベニの公式から求めるバージョン
 *
 * @param float $lat1 緯度１
 * @param float $lon1 経度１
 * @param float $lat2 緯度２
 * @param float $lon2 経度２
 * @param boolean $mode 測地系 true:世界(default) false:日本
 * @return float 距離(m)
 */
function distance($lat1, $lon1, $lat2, $lon2, $mode=true)
{
    // 緯度経度をラジアンに変換
    $radLat1 = deg2rad($lat1); // 緯度１
    $radLon1 = deg2rad($lon1); // 経度１
    $radLat2 = deg2rad($lat2); // 緯度２
    $radLon2 = deg2rad($lon2); // 経度２

    // 緯度差
    $radLatDiff = $radLat1 - $radLat2;

    // 経度差算
    $radLonDiff = $radLon1 - $radLon2;

    // 平均緯度
    $radLatAve = ($radLat1 + $radLat2) / 2.0;

    // 測地系による値の違い
    $a = $mode ? 6378137.0 : 6377397.155; // 赤道半径
    $b = $mode ? 6356752.314140356 : 6356078.963; // 極半径
    //$e2 = ($a*$a - $b*$b) / ($a*$a);
    $e2 = $mode ? 0.00669438002301188 : 0.00667436061028297; // 第一離心率^2
    //$a1e2 = $a * (1 - $e2);
    $a1e2 = $mode ? 6335439.32708317 : 6334832.10663254; // 赤道上の子午線曲率半径

    $sinLat = sin($radLatAve);
    $W2 = 1.0 - $e2 * ($sinLat*$sinLat);
    $M = $a1e2 / (sqrt($W2)*$W2); // 子午線曲率半径M
    $N = $a / sqrt($W2); // 卯酉線曲率半径

    $t1 = $M * $radLatDiff;
    $t2 = $N * cos($radLatAve) * $radLonDiff;
    $dist = sqrt(($t1 * $t1) + ($t2 * $t2));
	$dist = intval(round($dist));

    return $dist;
}

// 東京駅　大宮駅
//$distance = distance(35.6809591, 139.7673068, 35.9062039, 139.6237359, false);
//var_dump($distance);
?>
