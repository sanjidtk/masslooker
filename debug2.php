<?php
require('lib/config.php');
$cookieData		= explode('|', file_get_contents('./data/'.$cookieFile));
$cookie 		= $cookieData[0]; // Cookie Instagram
$useragent 		= $cookieData[1]; // Useragent Instagram
$username		= 'cantwo.id';
$userid			= getuid($username);
//feed/user/{$userId}/story/
if($cookie){
	$targets	= file_get_contents('./data/targetcok.txt');
	$targets 	= explode("\n", str_replace("\r", "", $targets));
	$targets 	= array($targets)[0];
	foreach($targets as $target){
		$targetid	= json_decode(request(1, $useragent, 'users/'.$target.'/usernameinfo/', $cookie)[1], 1)['user']['pk'];
		$gettarget	= proccess(1, $useragent, 'users/'.$targetid.'/info', $cookie);
		$gettarget	= json_decode($gettarget[1], true);
		$private	= $gettarget['user']['is_private'];
		$follower	= $gettarget['user']['follower_count'];
		if($private == false){
			if($follower > 50000){
				echo $target."\n";
			}
		}
		sleep(1);
	}
} else {
	echo "[!] Please login";
}
?>