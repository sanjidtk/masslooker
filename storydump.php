<?php
require('lib/config.php');
$cookieData		= explode('|', file_get_contents('./data/'.$cookieFile));
$cookie 		= $cookieData[0]; // Cookie Instagram
$useragent 		= $cookieData[1]; // Useragent Instagram
//feed/user/{$userId}/story/
if($cookie){
	$getakun	= proccess(1, $useragent, 'accounts/current_user/', $cookie);
	$getakun	= json_decode($getakun[1], true);
	if($getakun['status'] == 'ok'){
		$getakunV2	= proccess(1, $useragent, 'users/'.$getakun['user']['pk'].'/info', $cookie);
		$getakunV2	= json_decode($getakunV2[1], true);
		$getstory   = proccess(1, $useragent, 'feed/user/'.getuid('cantwo.id').'/story/', $cookie);
		echo $getstory[1];
		//$getstory   = json_decode($getstory[1], true);
	} else {
		echo "[!] Error : ".json_encode($getakun)."\n";
	}
} else {
	echo "[!] Please login";
}
?>