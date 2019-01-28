<?php
/*
copyright @ medantechno.com
Modified @ Farzain - zFz
2017

*/

require_once('./line_class.php');
require_once('./unirest-php-master/src/Unirest.php');

$channelAccessToken = '2LWhp4bHVyq3KL0w1NoK2UPsOEwHZ+VFgL4XuWgA0c3zTFL7gK3jzZk1hXK2qU+TrEvppmeLN2FIO/vNALdhja3CnFGnQn+71mvgWN0yTcYz99odD5lTZiyeIrh5V/N0mzd7RC2xykuDiwPgIO5F6AdB04t89/1O/w1cDnyilFU='; //sesuaikan 
$channelSecret = '0f3ae164d04b5e3ffb106897a452d52a';//sesuaikan

$client = new LINEBotTiny($channelAccessToken, $channelSecret);

$userId 	= $client->parseEvents()[0]['source']['userId'];
$groupId 	= $client->parseEvents()[0]['source']['groupId'];
$replyToken = $client->parseEvents()[0]['replyToken'];
$timestamp	= $client->parseEvents()[0]['timestamp'];
$type 		= $client->parseEvents()[0]['type'];

$message 	= $client->parseEvents()[0]['message'];
$messageid 	= $client->parseEvents()[0]['message']['id'];

$profil = $client->profil($userId);

$pesan_datang = explode(" ", $message['text']);

$command = $pesan_datang[0];
$options = $pesan_datang[1];
if (count($pesan_datang) > 2) {
    for ($i = 2; $i < count($pesan_datang); $i++) {
        $options .= '+';
        $options .= $pesan_datang[$i];
    }
}

#-------------------------[Function]-------------------------#
function shalat($keyword) {
    $date = date("d M Y");
    $uri = "https://time.siswadi.com/pray/" . $keyword;

    $response = Unirest\Request::get("$uri");

    $json = json_decode($response->raw_body, true);
    $result = "Jadwal Shalat Kota $keyword Hari Ini";
        $result .= "\n" . $date;
        $result .= "\n\nSubuh " . ": " . $json['data']['Fajr'];
        $result .= "\nDzuhur " . ": " . $json['data']['Dhuhr'];
        $result .= "\nAshar " . ": " . $json['data']['Asr'];
        $result .= "\nMaghrib " . ": " . $json['data']['Maghrib'];
        $result .= "\nIsya " . ": " . $json['data']['Isha'];
    return $result;
}

function movie($keyword) {
    $date = date("d M Y");
    $uri = "http://www.omdbapi.com/?apikey=d6d953bf&s=" . $keyword;

    $response = Unirest\Request::get("$uri");

    $json = json_decode($response->raw_body, true);
    $json = $json['Search'];
    foreach ($json as $row) {
	$result = "Halo Kak ^_^ Ini ada Poster Untuk Film ";
	$result .= $row['Title'];
	$result .= "\n\nLink : ";
	$result .= $row['Poster'];
    return $result;
     }
}

function cuaca($keyword) {
    $uri = "http://api.openweathermap.org/data/2.5/weather?q=" . $keyword . ",ID&units=metric&appid=e172c2f3a3c620591582ab5242e0e6c4";
    $response = Unirest\Request::get("$uri");
    $json = json_decode($response->raw_body, true);
    $result = "Halo Kak ^_^ Ini ada Ramalan Cuaca Untuk Daerah ";
	$result .= $json['name'];
	$result .= " Dan Sekitarnya";
	$result .= "\n\nCuaca : ";
	$result .= $json['weather']['0']['main'];
	$result .= "\nDeskripsi : ";
	$result .= $json['weather']['0']['description'];
    return $result;
}

function igp($keyword) {
    $uri = "https://rest.farzain.com/api/ig_profile.php?id=" . $keyword . "&apikey=BzB3xLlQ0QP8VcRMLVTWZEryf";
;
    $response = Unirest\Request::get("$uri");
    $json = json_decode($response->raw_body, true);
    $result = "Halo Kak ^_^ Ini Info Dari @";
	$result .= $json['info']['username'];
	$result .= "\n\nUsername : @";
	$result .= $json['info']['username'];
	$result .= "\nFull Name : ";
	$result .= $json['info']['full_name'];
	$result .= "\nProfile Pict : ";
	$result .= $json['info']['profile_pict'];
    return $result;
}

function igd($keyword) {
    $uri = "https://rest.farzain.com/api/ig_post.php?id=" . $keyword . "&apikey=BzB3xLlQ0QP8VcRMLVTWZEryf";
;
    $response = Unirest\Request::get("$uri");
    $json = json_decode($response->raw_body, true);
	$result = $json['first_pict'];
    return $result;
}

function jooxid($keyword) {
    $uri = "https://rest.farzain.com/api/joox/search.php?apikey=BzB3xLlQ0QP8VcRMLVTWZEryf&id=" . $keyword;
;
    $response = Unirest\Request::get("$uri");
    $json = json_decode($response->raw_body, true);
	$result .= "ID : ";
	$result .= $json['0']['songid'];
	$result .= "\nJudul : ";
	$result .= $json['0']['judul'];
	$result .= "\nPenyanyi : ";
	$result .= $json['0']['penyanyi'];
    return $result;
}

function jooxmp3($keyword) {
    $uri = "https://rest.farzain.com/api/joox/info.php?apikey=BzB3xLlQ0QP8VcRMLVTWZEryf&id=" . $keyword;
;
    $response = Unirest\Request::get("$uri");
    $json = json_decode($response->raw_body, true);
	$result = $json['audio']['mp3'];
    return $result;
}
#-------------------------[Function]-------------------------#

# require_once('./src/function/search-1.php');
# require_once('./src/function/download.php');
# require_once('./src/function/random.php');
# require_once('./src/function/search-2.php');
# require_once('./src/function/hard.php');

//show menu, saat join dan command /menu
if ($type == 'join' || $command == '/menu') {
    $text = "Halo Kak ^_^\nAku Bot Prediksi Cuaca, Kamu bisa mengetahui prediksi cuaca di daerah kamu sesuai dengan sumber BMKG";
    $balas = array(
        'replyToken' => $replyToken,
        'messages' => array(
            array(
                'type' => 'text',
                'text' => $text
            )
        )
    );
}

if ($message['type']=='text') {
	    if ($command == '/movie') {

        $result = movie($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array(
                    'type' => 'text',
                    'text' => $result
                )
            )
        );
    }
						
}

if ($message['type']=='text') {
	    if ($command == '/igp') {

        $result = igp($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array(
                    'type' => 'text',
                    'text' => $result
                )
            )
        );
    }
						
}

if ($message['type']=='text') {
	    if ($command == '/igd') {

        $result = igd($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array(
                    'type' => 'image',
	            'originalContentUrl' => $result,
	            'previewImageUrl' => $result
                )
            )
        );
    }
						
}

if ($message['type']=='text') {
	    if ($command == '/jooxid') {

        $result = jooxid($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array(
                    'type' => 'text',
                    'text' => $result
                )
            )
        );
    }
						
}

if ($message['type']=='text') {
	    if ($command == '/jooxmp3') {

        $result = jooxmp3($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array(
                    'type': 'audio',
    		    'originalContentUrl': $result,
    		    'duration': 240000
                )
            )
        );
    }
						
}

if ($message['type']=='text') {
	    if ($command == '/cuaca') {

        $result = cuaca($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array(
                    'type' => 'text',
                    'text' => $result
                )
            )
        );
    }
						
}

//pesan bergambar
if($message['type']=='text') {
	    if ($command == '/shalat') {

        $result = shalat($options);
        $balas = array(
            'replyToken' => $replyToken,
            'messages' => array(
                array(
                    'type' => 'text',
                    'text' => $result
                )
            )
        );
    }

} else if($message['type']=='sticker')
{	
	$balas = array(
		'replyToken' => $replyToken,														
		'messages' => array(
			array(
					'type' => 'text',									
					'text' => 'Makasih Kak Stikernya ^_^'										

				)
		)
	);
						
}
if (isset($balas)) {
    $result = json_encode($balas);
//$result = ob_get_clean();

    file_put_contents('./balasan.json', $result);


    $client->replyMessage($balas);
}
?>
