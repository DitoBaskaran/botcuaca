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
function cuaca($keyword) {
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

function moviePoster($keyword) {
    $date = date("d M Y");
    $uri = "http://www.omdbapi.com/?apikey=d6d953bf&s=$keyword";

    $response = Unirest\Request::get("$uri");

    $json = json_decode($response->raw_body, true);
    $result = "Hasil Poster $keyowrd";
    $result .= $json['Search']['Poster'];
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

//pesan bergambar
if($message['type']=='text') {
	    if ($command == '/shalat') {

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
						
} else if ($message['type']=='text') {
	    if ($command == '/moviep') {

        $result = moviePoster($options);
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
if (isset($balas)) {
    $result = json_encode($balas);
//$result = ob_get_clean();

    file_put_contents('./balasan.json', $result);


    $client->replyMessage($balas);
}
?>
