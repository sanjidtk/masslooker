<?php
require('lib/config.php');
echo " 

 ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
        🅼🅰🆂🆂 🅻🅾🅾🅺🅴🆁 
	
	$---------$--------$
	1.Mass story views without action block
	2.Reacts to stories
	3.Auto Poll reactions
	4.Auto Question Ansewring
	$---------$--------$
	
	\n";
echo " $$$$$$$$$$ Auto  Story Viewer by Photolooz $$$$$$$$$$$$$$$$$$   \n";
echo "  $-----$----$ Made by 🤑 @mohsanjid 🤑 $----$---$  \n\n";
echo " Subscribe my Youtube channel $ PhotoLooz $ for more videos--😍
 \n\n";
echo "[?] Input your instagram username : ";
$userig    = trim(fgets(STDIN, 1024));
echo "[?] Input your instagram password : ";
$passig    = trim(fgets(STDIN, 1024));
//
$useragent = generate_useragent();
$device_id = generate_device_id();
$user      = $userig;
$pass      = $passig;
$login     = proccess(1, $useragent, 'accounts/login/', 0, hook('{"device_id":"' . $device_id . '","guid":"' . generate_guid() . '","username":"' . $userig . '","password":"' . $passig . '","Content-Type":"application/x-www-form-urlencoded; charset=UTF-8"}'), array(
    'Accept-Language: id-ID, en-US',
    'X-IG-Connection-Type: WIFI'
));
$ext		= json_decode($login[1]);
preg_match('#set-cookie: csrftoken=([^;]+)#i', str_replace('Set-Cookie:', 'set-cookie:', $login[0]), $token);
preg_match_all('%set-cookie: (.*?);%', str_replace('Set-Cookie:', 'set-cookie:', $login[0]), $d);
$cookie 	= '';
for($o = 0; $o < count($d[0]); $o++){
    $cookie .= $d[1][$o] . ";";
}
if($ext->status == 'ok'){
    $uname       = $ext->logged_in_user->username;
    $uid         = $ext->logged_in_user->pk;
    saveCookie('./data/'.$cookieFile, $cookie."|".$useragent);
    echo "[+] Login success....\n";
    echo "[+] Data saved\n";
} elseif($ext->error_type == 'checkpoint_challenge_required'){
	$_SESSION['c_cookie']       = $cookie;
    $_SESSION['c_ua']           = $useragent;
    $_SESSION['c_token']        = $token[1];
    $_SESSION['c_url']          = $ext->challenge->url;
    $_SESSION['c_username']     = $user;
    $_SESSION['c_password']     = $pass;
    echo "[!] Verification required\n";
    echo "[!] ==============================\n\n";
    sleep(2);
    echo "[1] Phone number\n[2] Email\n[?] Enter number verification method : ";
    $verifikasi				    = trim(fgets(STDIN, 1024));
    if($verifikasi == 1){
    	$verifikasi = 0;
    } elseif($verifikasi == 2){
    	$verifikasi = 1;
    } else {
    	echo "[+] Invalid input\n";
    	echo "[+] Exit...\n";
    	exit();
    }
    $challenge_csrf     		= $_SESSION['c_token'];
    $challenge_url      		= $_SESSION['c_url'];
    $challenge_ua       		= $_SESSION['c_ua'];
    $challenge_cookie   		= $_SESSION['c_cookie'];
    $challenge_pw       		= $_SESSION['c_password'];
    $data               		= 'choice='.$verifikasi;
    $cekpoint           		= cekpoint($challenge_url, $data, $challenge_csrf, $challenge_cookie, $challenge_ua);
    if(strpos($cekpoint, 'status": "ok"') !== false){
    	echo "[+] Verification code has been sent\n";
    	echo "[+] ==============================\n\n";
    	sleep(2);
    	echo "[?] Enter verification code : ";
    	$kode   			= trim(fgets(STDIN, 1024));
    	$data               = 'security_code='.$kode;
    	$cekpoint           = cekpoint($challenge_url, $data, $challenge_csrf, $challenge_cookie, $challenge_ua);
    	if(strpos($cekpoint, 'status": "ok"') !== false){
	        preg_match_all('%set-cookie: (.*?);%', str_replace('Set-Cookie:', 'set-cookie:', $cekpoint), $d);
	        $cookie     = '';
	        for($o = 0; $o < count($d[0]); $o++){
	        	$cookie .= $d[1][$o] . ";";
	        }
	        $req        = proccess(1, $challenge_ua, 'accounts/current_user/', $cookie);
	        $reqx       = json_decode($req[1]);
	        if($reqx->status == 'ok'){
	            $cookie                 = $cookie;
	            $useragent              = $challenge_ua;
	            saveCookie('./data/'.$cookieFile, $cookie."|".$useragent);
    			echo "[+] Login success....\n";
    			echo "[+] Data saved\n";
	        } else {
	            echo "[!] Cookie die\n";
	            echo "[!] Exit...\n";
	        }
	    }
    } else {
    	echo "[!] Failed sent verification code ".$cekpoint." - ".var_dump($_SESSION)."\n";
    	echo "[!] Exit...\n";
    	exit();
    }
} elseif($ext->error_type == 'bad_password'){
	echo "[!] Invalid password\n";
    echo "[!] Exit...\n";
} else {
    echo "[!] Unknown error : ".$ext->message."\n";
    echo "[!] Exit...\n";
}
?>
