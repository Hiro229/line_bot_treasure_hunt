<?php

/**
 * Copyright 2016 LINE Corporation
 *
 * LINE Corporation licenses this file to you under the Apache License,
 * version 2.0 (the "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at:
 *
 *   https://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

require_once('./LINEBotTiny.php');
require_once('line_bot_token.php');

// 上野公園
//$park_lat = 35.71471094494001;
//$park_lon = 139.77328686105244;
$park_lat = 35.71366342333147;
$park_lon = 139.77249967040186;
// カエルの噴水
$lat1 = 35.711071729961574;
$lon1 = 139.773614139459;
// ボート乗り場
$lat2 = 35.71224204592365;
$lon2 = 139.77046627509878;
// 小松宮彰仁親王像
$lat3 = 35.71534127423365;
$lon3 = 139.77345964991682;
// シロナガスクジラ
$lat4 = 35.716787442702866;
$lon4 = 139.7765349188909;
// トーテムポール
$lat5 = 35.7147076694801;
$lon5 = 139.7732873474706;
//西郷さん
$lat6 = 35.71185308828583;
$lon6 = 139.77414985804634;
//ゴール
$lat_goal = 35.711917560754934;
$lon_goal = 139.77427845240496;

// 目的地までの直線距離
require_once('distance.php');

// 目的地までの徒歩最短距離
require_once('google_directions.php');

$client = new LINEBotTiny($channelAccessToken, $channelSecret);
foreach ($client->parseEvents() as $event) {
	switch ($event['type']) {
	case 'message':
		$message = $event['message'];
		switch ($message['type']) {
		case 'text':
			if ($message['text'] === 'Treasure Map') {
				$client->replyMessage([
					'replyToken' => $event['replyToken'],
					'messages' => [
						[
							'type' => 'location',
							'title' => 'Treasure Map',
							'address' => 'Treasure Map',
							'latitude' => $park_lat,
							'longitude' => $park_lon,
						]
					]
				]);
			} elseif ($message['text'] === '宝の地図') {
				$client->replyMessage([
					'replyToken' => $event['replyToken'],
					'messages' => [
						[
							'type' => 'image',
							'originalContentUrl' => 'https://scogineer.mydns.jp/line_bot/line_bot_treasure_hunt/treasure_map/1040',
							'previewImageUrl' => 'https://scogineer.mydns.jp/line_bot/line_bot_treasure_hunt/treasure_map/1040',
						],
						[
							'type' => 'text',
							'text' => 'これが今回の宝の地図だ！'
						],
						[
							'type' => 'text',
							'text' => 'ここの地図の範囲に入ったら下のボタンのアクションから『位置情報』を送信してくれ！'
						],
						[
							'type' => 'text',
							'text' => 'そこについたら私たちが集めた情報を渡そう'
						]
					]
				]);
			} elseif ($message['text'] === '小松宮彰仁親王像だね！') {
				$client->replyMessage([
					'replyToken' => $event['replyToken'],
					'messages' => [
						[
							'type' => 'text',
							'text' => "暗号1\nサル⇒た\nライオン⇒さ"
						],
					]
				]);
			} elseif ($message['text'] === 'カエルの噴水だね！') {
				$client->replyMessage([
					'replyToken' => $event['replyToken'],
					'messages' => [
						[
							'type' => 'text',
							'text' => "暗号2\nキリン⇒ご\nワニ⇒も"
						],
					]
				]);
			} elseif ($message['text'] === 'ボート場だね！') {
				$client->replyMessage([
					'replyToken' => $event['replyToken'],
					'messages' => [
						[
							'type' => 'text',
							'text' => "暗号3\n？⇒う\nフクロウ⇒い"
						],
					]
				]);
			} elseif ($message['text'] === 'シロナガスクジラだね！') {
				$client->replyMessage([
					'replyToken' => $event['replyToken'],
					'messages' => [
						[
							'type' => 'text',
							'text' => "暗号4\nゾウ⇒り\nトラ⇒か"
						],
					]
				]);
			}
			break;
		case 'location':
			// 公園までの距離
			$park_distance = distance($message['latitude'], $message['longitude'], $park_lat, $park_lon, false);
			$park_duration = directions($message['latitude'], $message['longitude'], $park_lat, $park_lon);

			// 西郷さんまでの距離
			$saigo_distance = distance($message['latitude'], $message['longitude'], $lat6, $lon6, false);
			$totem_distance = distance($message['latitude'], $message['longitude'], $lat5, $lon5, false);

			// 西郷さん15m以内
			if ($saigo_distance <= 15) {
				$goal_distance = distance($message['latitude'], $message['longitude'], $lat_goal, $lon_goal, false);
				if ($goal_distance > 1) {
					$client->replyMessage([
						'replyToken' => $event['replyToken'],
						'messages' => [
							[
								'type' => 'text',
								'text' => "おめでとう！大正解だ!!\n西郷隆盛銅像の周りを探してみよう\n位置情報をこまめに送信してお宝との距離を縮めて行こう！"
							],
							[
								'type' => 'text',
								'text' => 'お宝までの距離は ' . $goal_distance. ' m だよ'
							],
						]
					]);
				} else {
					$client->replyMessage([
						'replyToken' => $event['replyToken'],
						'messages' => [
							[
								'type' => 'text',
								'text' => "ついにここまでこれたんだね！\nお宝はこのあたりにあるみたいだ\n探してみよう！"
							],
							[
								'type' => 'sticker',
								'packageId' => '446',
								'stickerId' => '1989'
							]
						]
					]);
				}
			}
			// totem pole 15m以内
			if ($totem_distance <= 15) {
				$client->replyMessage([
					'replyToken' => $event['replyToken'],
					'messages' => [
						[
							'type' => 'text',
							'text' => "動物をかたどったオブジェが近くにあるね"
						],
					]
				]);
			}
			// 500m以内の場合
			if ($park_distance <= 500) {
				$client->replyMessage([
					'replyToken' => $event['replyToken'],
					'messages' => [
						[
							'type' => 'text',
							'text' => 'どうやら到着したようだな'
						],
						[
							'type' => 'text',
							'text' => '私たちが調べたところによると『お宝の場所』は誰にもわからないように、暗号化していくつかの場所にヒントを隠したみたいだ'
						],
						[
							'type' => 'text',
							'text' => '君にはいくつか場所を探してもらい、その場所の写真を撮って私たちに送ってもらいたい'
						],
						[
							'type' => 'text',
							'text' => "写真はその場所がなるべく大きく映るように撮ってくれ、それでは健闘を祈っている"
						],
						[
							'type' => 'text',
							'text' => "まず最初はここを探してくれ！\n『水を出し続ける生物』"
						],
					]
				]);
			} else {
				// 500m以上の場合
				$client->replyMessage([
					'replyToken' => $event['replyToken'],
					'messages' => [
						[
							'type' => 'text',
							'text' => 'まだ宝の地図の範囲に入ってないな'
						],
						[
							'type' => 'text',
							'text' => '地図の範囲までの距離は ' . $park_distance. " m\n徒歩で" . $park_duration . '分'
						],
						[
							'type' => 'text',
							'text' => '範囲がわからなくなったら下のアクションボタンから『宝の地図』をおしてくれ。'
						],
						[
							'type' => 'sticker',
							'packageId' => '1070',
							'stickerId' => '17839'
						]
					]
				]);
			}
			break;
		case 'image':
			require_once('./google_vision.php');
			$image_path = $client->replyImage($message['id']);
			$labels = vision($image_path);
			$search1 = ['Fountain', 'Water feature', 'Sculpture'];
			$search2 = ['Boat', 'Building', 'Watercraft', 'Skyscraper'];
			$search3 = ['Horse', 'Tree', 'Statue'];
			$search4 = ['Blue whale', 'Tree'];
			$search5 = ['Totem pole', 'Totem', 'Artifact'];
			if(count(array_intersect($search1, $labels)) >= 2) {
				$client->replyMessage([
					'replyToken' => $event['replyToken'],
					'messages' => [
						[
							'type' => 'text',
							'text' => "おめでとう正解だ！\nこれは『カエルの噴水』というみたいだね\n作成されたのは平成12年3月らしいが関連する資料は残っていないらしい、、、\nやはり怪しいな。\n暗号が解読できたみたいだ。"
						],
						[
							'type' => 'text',
							'text' => "暗号2\nキリン⇒ご\nワニ⇒も"
						],
						[
							'type' => 'text',
							'text' => "次は『二人で乗った思い出の風景』だ!\n私たちには何のことかわからないが、わかるかい？"
						],
					]
				]);
			} elseif(count(array_intersect($search2, $labels)) >= 2) {
				$client->replyMessage([
					'replyToken' => $event['replyToken'],
					'messages' => [
						[
							'type' => 'text',
							'text' => "おめでとう正解だ！\nこの暗号を作った人は昔乗ったことがあるみたいだね。\n一緒にボートに乗るとカップルは別れてしまうジンクスがあるみたいだ。\n一緒にペダルを漕いでの共同作業は仲が深まると思いきや別れるカップルが多いそうだ、、、\nでも安心してくれ一人でこぐローボートがあるみたいだ。"
						],
						[
							'type' => 'text',
							'text' => "暗号3\n？⇒う\nフクロウ⇒い"
						],
						[
							'type' => 'text',
							'text' => "次は『馬に乗った人』だ!\nこんな時代に馬に乗っている人がいるのかな。"
						]
					]
				]);
			} elseif(count(array_intersect($search3, $labels)) >= 3) {
				$client->replyMessage([
					'replyToken' => $event['replyToken'],
					'messages' => [
						[
							'type' => 'text',
							'text' => "おめでとう正解だ！\n銅像のことだったんだね。\n銅像のそばには堂々とした桜「コマツノオトメ」の原木がある。\nこの銅像の近くにあることからその名が付けられたそうだ。"
						],
						[
							'type' => 'text',
							'text' => "暗号1\nサル⇒た\nライオン⇒さ"
						],
						[
							'type' => 'text',
							'text' => "次で最後だ!最後は『地球上最大の動物』だ!\nこの公園のどこかにいるらしい。"
						]
					]
				]);
			} elseif(count(array_intersect($search4, $labels)) === 2) {
				$client->replyMessage([
					'replyToken' => $event['replyToken'],
					'messages' => [
						[
							'type' => 'text',
							'text' => "おめでとう正解だ！\n実際の大きさでリアルさを感じてほしいという思いで置いたそうだ。\nちなみに今のシロナガスクジラは3代目。"
						],
						[
							'type' => 'text',
							'text' => "暗号4\nゾウ⇒り\nトラ⇒か"
						],
						[
							'type' => 'text',
							'text' => "暗号が何を表しているかわかるかい？\n暗号の順番で場所に線を結んで行くと交差するところがあるね\nそこの近くにヒントがあるかもしれない"
						],
						[
							'type' => 'text',
							'text' => "解読したらそこで位置情報を送ってみて！"
						],
						[
							'type' => 'imagemap',
							'baseUrl' => 'https://scogineer.mydns.jp/line_bot/line_bot_treasure_hunt/point',
							'altText' => 'Treasure map',
							'baseSize' => [
								'width' => 1040,
								'height' => 1040,
							],
							'actions' => [
								[
									'type' => 'uri',
									'label' => 'treasuremap',
									'linkUri' => 'https://www.google.co.jp/maps/dir//35.7146169,139.7733232/@35.7146852,139.7733738,21z/data=!4m2!4m1!3e0?hl=ja',
									'area' => [
										'x' => 469,
										'y' => 456,
										'width' => 77,
										'height' => 74,
									]
								],
								[
									'type' => 'message',
									'label' => 'Flog',
									'text' => 'カエルの噴水だね！',
									'area' => [
										'x' => 502,
										'y' => 872,
										'width' => 69,
										'height' => 72,
									]
								],
								[
									'type' => 'message',
									'label' => 'Boat',
									'text' => 'ボート場だね！',
									'area' => [
										'x' => 209,
										'y' => 741,
										'width' => 71,
										'height' => 70,
									]
								],
								[
									'type' => 'message',
									'label' => 'Statue',
									'text' => '小松宮彰仁親王像だね！',
									'area' => [
										'x' => 496,
										'y' => 382,
										'width' => 60,
										'height' => 73,
									]
								],
								[
									'type' => 'message',
									'label' => 'Blue whale',
									'text' => 'シロナガスクジラだね！',
									'area' => [
										'x' => 776,
										'y' => 217,
										'width' => 70,
										'height' => 76,
									]
								],
							]
						],
					]
				]);
			} elseif(count(array_intersect($search5, $labels)) >= 2) {
				$client->replyMessage([
					'replyToken' => $event['replyToken'],
					'messages' => [
						[
							'type' => 'text',
							'text' => "よく見たら動物たちが縦に並んでるね"
						],
					]
				]);
			} else {
				$client->replyMessage([
					'replyToken' => $event['replyToken'],
					'messages' => [
						[
							'type' => 'text',
							'text' => 'どうやら違うようだ、、、'
						],
						[
							'type' => 'text',
							'text' => "画面いっぱいになるように撮影したり、\n角度を変えて撮影してみてくれ"
						],
					]
				]);
			}
			break;
		default:
			error_log('Unsupported message type: ' . $message['type']);
			break;
		}
		break;
	default:
		error_log('Unsupported event type: ' . $event['type']);
		break;
	}
};
