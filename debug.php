<?php
require('lib/config.php');
$cookieData		= explode('|', file_get_contents('./data/'.$cookieFile));
$cookie 		= $cookieData[0]; // Cookie Instagram
$useragent 		= $cookieData[1]; // Useragent Instagram
$username		= 'cantwo.id';
$userid			= getuid($username);
//feed/user/{$userId}/story/
if($cookie){
	$komens		= file_get_contents('./data/storyAnswer.txt');
	$komen		= explode("\n", str_replace("\r", "", $komens));
	$komen		= array($komen)[0];
	//
	$getstory   = proccess(1, $useragent, 'feed/user/'.$userid.'/story/', $cookie);
	$getstory   = json_decode($getstory[1], true);
	$reels		= array();
	$reels_suc	= array();
	foreach($getstory['reel']['items'] as $storyitem):
		$reels[count($reels)]	= $storyitem['id']."_".$getstory['reel']['user']['pk'];
		$stories['id']			= $storyitem['id'];
		$stories['reels']		= $storyitem['id']."_".$getstory['reel']['user']['pk'];
		$stories['reel']		= $storyitem['taken_at'].'_'.time();
		if(strpos(file_get_contents('./data/storyData.txt'), $stories['reels']) == false){
			$hook       = '{"live_vods_skipped": {}, "nuxes_skipped": {}, "nuxes": {}, "reels": {"'.$stories['reels'].'": ["'.$stories['reel'].'"]}, "live_vods": {}, "reel_media_skipped": {}}';
			$viewstory  = proccess_v2(1, $useragent, 'media/seen/?reel=1&live_vod=0', $cookie, hook(''.$hook.''));
			$viewstory  = json_decode($viewstory[1], true);
			if($storyitem['story_polls']){
				$stories['pool_id']	= $storyitem['story_polls'][0]['poll_sticker']['poll_id'];
				$react_1	  		= proccess(1, $useragent, 'media/'.$stories['id'].'/'.$stories['pool_id'].'/story_poll_vote/', $cookie, hook('{"radio_type": "none", "vote": "'.rand(0,1).'"}'));
				echo "[Stories Polls True : ".$stories['pool_id']." : ".$react_1[1]."] ";
			}
			if($storyitem['story_questions']){
				$stories['question_id']	= $storyitem['story_questions'][0]['question_sticker']['question_id'];
				$rand					= rand(0, count($komen)-1);
		        $textAnswer 			= $komen[$rand];
				$react_2	  			= proccess(1, $useragent, 'media/'.$stories['id'].'/'.$stories['question_id'].'/story_question_response/', $cookie, hook('{"response": "'.$textAnswer.'", "type": "text"}'));
				echo "[Stories Question True : ".$stories['question_id']." : ".$react_2[1]."] ";
			}
			if($storyitem['story_countdowns']){
				$stories['countdown_id']	= $storyitem['story_countdowns'][0]['countdown_sticker']['countdown_id'];
				$react_3	  				= proccess(1, $useragent, 'media/'.$stories['countdown_id'].'/follow_story_countdown/', $cookie);
				echo "[Stories Countdown True : ".$stories['countdown_id']." : ".$react_3[1]."] ";
			}
			if($storyitem['story_sliders']){
				$stories['slider_id']	= $storyitem['story_sliders'][0]['slider_sticker']['slider_id'];
				$react_4	  			= proccess(1, $useragent, 'media/'.$stories['id'].'/'.$stories['slider_id'].'/story_slider_vote/', $cookie, hook('{"radio_type": "wifi-none", "vote": "1"}'));
				echo "[Stories Slider True : ".$stories['slider_id']." : ".$react_4[1]."] ";
			}
			if($storyitem['story_quizs']){
				$stories['quiz_id']	= $storyitem['story_quizs'][0]['quiz_sticker']['quiz_id'];
				//$react_5	  		= proccess(1, $useragent, 'media/'.$stories['id'].'/'.$stories['quiz_id'].'/story_poll_vote/', $cookie, hook('{"radio_type": "none", "vote": "'.rand(0,3).'"}'));
				echo "[Stories Quiz True : ".$stories['quiz_id']." : ".$react_5[1]."] ";
			}
			if($viewstory['status'] == 'ok'){
				$reels_suc[count($reels_suc)] = $storyitem['id']."_".$getstory['reel']['user']['pk'];
				saveData('./data/storyData.txt', $stories['reels']);
			}
			echo "[".$stories['reels']."]\n";
		}
	endforeach;
} else {
	echo "[!] Please login";
}
?>