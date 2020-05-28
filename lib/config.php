<?php
include('func.php');
include('igfunc.php');
date_default_timezone_set('Asia/Jakarta');
error_reporting(0);
/*
Jika akun terkena feedback_required tenang, tinggal tunggu 24 jam ntar pulih lagi
Cronjob 10 Menit aja atau berapa terserah, biar ga kedetek BOT
Tau sendiri IG sekarang kan, kek memek
*/

// UBAH BAGIAN INI
$total 			= 2; // Total media yang ingin dikomentari | jadi setiap file dijalankan hanyamengambil beberapa media saja, jangan banyak2 untuk menghindari feedback
$komentar		= 'komentar.txt'; // FIle komentar mu
$saveFile 		= 'logData.txt'; // File log
$cookieFile 	= 'cookieData.txt'; // File cookie
$targetFile 	= 'targetData.txt'; // File cookie
$date 			= date("Y-m-d");
$time 			= date("H:i:s");
?>