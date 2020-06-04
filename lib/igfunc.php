<?php
set_time_limit(0);
ignore_user_abort(1);
function trim_replace($data)
{
    $a = trim($data);
    $a = str_replace('<', '', $a);
    $a = str_replace('>', '', $a);
    $a = str_replace('"', '', $a);
    $a = str_replace('`', '', $a);
    $a = str_replace('-', '', $a);
    return $a;
}

function GetTimeDiff($timestamp)
{
    $how_log_ago = '';
    $seconds     = $timestamp - time();
    $minutes     = (int) ($seconds / 60);
    $hours       = (int) ($minutes / 60);
    $days        = (int) ($hours / 24);
    if ($days >= 1) {
        $how_log_ago = $days . ' hari';
    } else if ($hours >= 1) {
        $how_log_ago = $hours . ' jam';
    } else if ($minutes >= 1) {
        $how_log_ago = $minutes . ' menit';
    } else {
        $how_log_ago = $seconds . ' detik';
    }
    return $how_log_ago;
}

function base_url($atRoot = FALSE, $atCore = FALSE, $parse = FALSE)
{
    if (isset($_SERVER['HTTP_HOST'])) {
        $http     = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
        $hostname = $_SERVER['HTTP_HOST'];
        $dir      = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
        $core     = preg_split('@/@', str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(dirname(__FILE__))), NULL, PREG_SPLIT_NO_EMPTY);
        $core     = $core[0];
        $tmplt    = $atRoot ? ($atCore ? "%s://%s/%s/" : "%s://%s/") : ($atCore ? "%s://%s/%s/" : "%s://%s%s");
        $end      = $atRoot ? ($atCore ? $core : $hostname) : ($atCore ? $core : $dir);
        $base_url = sprintf($tmplt, $http, $hostname, $end);
    } else
        $base_url = 'http://localhost/';
    if ($parse) {
        $base_url = parse_url($base_url);
        if (isset($base_url['path']))
            if ($base_url['path'] == '/')
                $base_url['path'] = '';
    }
    return $base_url;
}

function paginasi($reload, $page, $tpages)
{
    $adjacents = 2;
    $prevlabel = "&lsaquo; Prev";
    $nextlabel = "Next &rsaquo;";
    $out       = '<ul class="pagination pagination-sm no-margin pull-left">';
    if ($page == 1) {
        $out .= "<li><a href=\"#\">$prevlabel</a></li>\n";
    } elseif ($page == 2) {
        $out .= "<li><a href=\"" . $reload . "\">" . $prevlabel . "</a></li>\n";
    } else {
        $out .= "<li><a href=\"" . $reload . "&page=" . ($page - 1) . "\">" . $prevlabel . "</a></li>\n";
    }
    $pmin = ($page > $adjacents) ? ($page - $adjacents) : 1;
    $pmax = ($page < ($tpages - $adjacents)) ? ($page + $adjacents) : $tpages;
    for ($i = $pmin; $i <= $pmax; $i++) {
        if ($i == $page) {
            $out .= "<li class=\"active\"><a href=\"#\">" . $i . "</a></li>\n";
        } elseif ($i == 1) {
            $out .= "<li><a href=\"" . $reload . "\">" . $i . "</a></li>\n";
        } else {
            $out .= "<li><a href=\"" . $reload . "&page=" . $i . "\">" . $i . "</a></li>\n";
        }
    }
    if ($page < ($tpages - $adjacents)) {
        $out .= "<li><a href=\"" . $reload . "&page=" . $tpages . "\">" . $tpages . "</a></li>\n";
    }
    if ($page < $tpages) {
        $out .= "<li><a href=\"" . $reload . "&page=" . ($page + 1) . "\">" . $nextlabel . "</a></li>\n";
    } else {
        $out .= "<li><a href=\"#\">$nextlabel</a></li>\n";
    }
    $out .= "</ul>";
    return $out;
}

function proccess($ighost, $useragent, $url, $cookie = 0, $data = 0, $httpheader = array(), $proxy = 0, $userpwd = 0, $is_socks5 = 0)
{
    $url = $ighost ? 'https://i.instagram.com/api/v1/' . $url : $url;
    $ch  = curl_init($url);
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    if ($proxy)
        curl_setopt($ch, CURLOPT_PROXY, $proxy);
    if ($userpwd)
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, $userpwd);
    if ($is_socks5)
        curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
    if ($httpheader)
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    if ($cookie)
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    if ($data):
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    endif;
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch);
    if (!$httpcode)
        return false;
    else {
        $header = substr($response, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
        $body   = substr($response, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
        curl_close($ch);
        return array(
            $header,
            $body
        );
    }
}

function proccess_v2($ighost, $useragent, $url, $cookie = 0, $data = 0, $httpheader = array(), $proxy = 0, $userpwd = 0, $is_socks5 = 0)
{
    $url = $ighost ? 'https://i.instagram.com/api/v2/' . $url : $url;
    $ch  = curl_init($url);
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    if ($proxy)
        curl_setopt($ch, CURLOPT_PROXY, $proxy);
    if ($userpwd)
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, $userpwd);
    if ($is_socks5)
        curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
    if ($httpheader)
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    if ($cookie)
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    if ($data):
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    endif;
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch);
    if (!$httpcode)
        return false;
    else {
        $header = substr($response, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
        $body   = substr($response, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
        curl_close($ch);
        return array(
            $header,
            $body
        );
    }
}

function request($ighost, $useragent, $url, $cookie = 0, $data = 0, $httpheader = array(), $proxy = 0, $userpwd = 0, $is_socks5 = 0)
{
    $url = $ighost ? 'https://i.instagram.com/api/v1/' . $url : $url;
    $ch  = curl_init($url);
    curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 20);
    if ($proxy)
        curl_setopt($ch, CURLOPT_PROXY, $proxy);
    if ($userpwd)
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, $userpwd);
    if ($is_socks5)
        curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
    if ($httpheader)
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    if ($cookie)
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    if ($data):
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    endif;
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch);
    if (!$httpcode)
        return false;
    else {
        $header = substr($response, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
        $body   = substr($response, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
        curl_close($ch);
        return array(
            $header,
            $body
        );
    }
}

function cekpoint($url, $data, $csrf, $cookies, $ua){
	$a = curl_init();
    curl_setopt($a, CURLOPT_URL, $url);
    curl_setopt($a, CURLOPT_USERAGENT, $ua);
	curl_setopt($a, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($a, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($a, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($a, CURLOPT_HEADER, 1);
    curl_setopt($a, CURLOPT_COOKIE, $cookies);
    if($data){
    curl_setopt($a, CURLOPT_POST, 1);	
    curl_setopt($a, CURLOPT_POSTFIELDS, $data);
    }
    if($csrf){
    curl_setopt($a, CURLOPT_HTTPHEADER, array(
            'Connection: keep-alive',
            'Proxy-Connection: keep-alive',
            'Accept-Language: en-US,en',
            'x-csrftoken: '.$csrf,
            'x-instagram-ajax: 1',
            'Referer: '.$url,
            'x-requested-with: XMLHttpRequest',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
    ));
    }
    $b = curl_exec($a);
    return $b;
}

function generate_useragent($sign_version = '107.0.0.27.121')
{
    $resolusi = array(
        '1080x1776',
        '1080x1920',
        '720x1280',
        '320x480',
        '480x800',
        '1024x768',
        '1280x720',
        '768x1024',
        '480x320'
    );
    $versi    = array(
        'GT-N7000',
        'SM-N9000',
        'GT-I9220',
        'GT-I9100'
    );
    $dpi      = array(
        '120',
        '160',
        '320',
        '240'
    );
    $ver      = $versi[array_rand($versi)];
    return 'Instagram ' . $sign_version . ' Android (' . mt_rand(10, 11) . '/' . mt_rand(1, 3) . '.' . mt_rand(3, 5) . '.' . mt_rand(0, 5) . '; ' . $dpi[array_rand($dpi)] . '; ' . $resolusi[array_rand($resolusi)] . '; samsung; ' . $ver . '; ' . $ver . '; smdkc210; en_US)';
}

function hook($data)
{
    return 'ig_sig_key_version=4&signed_body=' . hash_hmac('sha256', $data, '5d406b6939d4fb10d3edb4ac0247d495b697543d3f53195deb269ec016a67911') . '.' . urlencode($data);
}

function ava_hook($bound, $csrf, $file_url)
{
    $eol  = "\r\n";
    $body = '';
    $body .= '--' . $bound . $eol;
    $body .= 'Content-Disposition: form-data; name="_csrftoken"' . $eol . $eol;
    $body .= $csrf . $eol;
    $body .= '--' . $bound . $eol;
    $body .= 'Content-Disposition: form-data; name="profile_pic"; filename="profile_pic"' . $eol;
    $body .= 'Content-Type: application/octet-stream' . $eol;
    $body .= 'Content-Transfer-Encoding: binary' . $eol . $eol;
    $body .= file_get_contents($file_url) . $eol;
    $body .= '--' . $bound . '--' . $eol . $eol;
    return $body;
}

function generate_device_id()
{
    return 'android-' . md5(rand(1000, 9999)) . rand(2, 9);
}

function generate_guid($tipe = 0)
{
    $guid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    return $tipe ? $guid : str_replace('-', '', $guid);
}

function replace_friendship($teks, $tipe, $useragent, $cookies)
{
    preg_match('#_from_(.*?)_limit_#', $teks, $from);
    $ch = curl_init('https://www.instagram.com/' . $from[1] . '/');
    curl_setopt($ch, CURLOPT_USERAGENT, 'Instagram 6.22.0 Android (16/4.1.2; 120dpi; 240x320; samsung; GT-S5282; mint; sp8810; in_ID)');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $resp     = curl_exec($ch);
    $httpcode = curl_getinfo($ch);
    curl_close($ch);
    if ($httpcode['http_code'] == 200):
        preg_match('#","id":"(.*?)","biography":#', $resp, $idv);
        preg_match('#_limit_(.*?)::}#', $teks, $limit);
        $jumlah = (((int) $limit[1] - 1) > 10) ? 10 : ((int) $limit[1] - 1);
        $list   = array();
        $c      = 0;
        do {
            $parameters = ($c > 0) ? '?max_id=' . $c : '';
            $req        = proccess(1, $useragent, 'friendships/' . $idv[1] . '/' . $tipe . '/' . $parameters, $cookies, null, array(
                'Accept-Language: id-ID, en-US',
                'X-IG-Connection-Type: WIFI'
            ));
            $req        = json_decode($req[1]);
            for ($i = 0; $i < count($req->users); $i++):
                if (count($list) <= $jumlah)
                    $list[count($list)] = $req->users[$i]->username;
            endfor;
            $c = (isset($req->next_max_id)) ? $req->next_max_id : 0;
        } while (count($list) <= $jumlah);
        $mention_people = '';
        for ($i = 0; $i < count($list); $i++)
            $mention_people .= '@' . $list[$i] . ' ';
        preg_match('#{::(.*?)::}#', $teks, $teks1);
        return str_replace('{::' . $teks1[1] . '::}', $mention_people, $teks);
    else:
        return false;
    endif;
}

function replace_media($teks, $tipe, $useragent, $cookies)
{
    preg_match('#_from_(.*?)_limit_#', $teks, $from);
    $ch = curl_init('https://api.instagram.com/oembed/?url=https://www.instagram.com/p/' . $from[1] . '/');
    curl_setopt($ch, CURLOPT_USERAGENT, 'Instagram 6.22.0 Android (16/4.1.2; 120dpi; 240x320; samsung; GT-S5282; mint; sp8810; in_ID)');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $resp     = curl_exec($ch);
    $httpcode = curl_getinfo($ch);
    curl_close($ch);
    if ($httpcode['http_code'] == 200):
        $resp = json_decode($resp);
        preg_match('#_limit_(.*?)::}#', $teks, $limit);
        $jumlah = (((int) $limit[1] - 1) > 10) ? 10 : ((int) $limit[1] - 1);
        $list   = array();
        $c      = 0;
        if ($tipe == 'likers'):
            do {
                $req = proccess(1, $useragent, 'media/' . $resp->media_id . '/' . $tipe . '/', $cookies, null, array(
                    'Accept-Language: id-ID, en-US',
                    'X-IG-Connection-Type: WIFI'
                ));
                $req = json_decode($req[1]);
                if ($jumlah > count($req->users))
                    $jumlah = count($req->users) - 1;
                for ($i = 0; $i < count($req->users); $i++):
                    if (count($list) <= $jumlah)
                        $list[count($list)] = $req->users[$i]->username;
                endfor;
            } while (count($list) <= $jumlah);
        else:
            do {
                $parameters = ($c > 0) ? '?max_id=' . $c : '';
                $reqs       = proccess(1, $useragent, 'media/' . $resp->media_id . '/' . $tipe . '/' . $parameters, $cookies, null, array(
                    'Accept-Language: id-ID, en-US',
                    'X-IG-Connection-Type: WIFI'
                ));
                $req        = json_decode($reqs[1]);
                if ($jumlah > $req->comment_count)
                    $jumlah = $req->comment_count - 1;
                for ($i = 0; $i < count($req->comments); $i++):
                    if (count($list) <= $jumlah)
                        $list[count($list)] = $req->comments[$i]->user->username;
                endfor;
                preg_match('#"next_max_id":(.*?),"caption"#', $reqs[1], $max_id);
                if ($max_id[1])
                    $c = ($c == $max_id[1]) ? 0 : $max_id[1];
                else
                    $c = 0;
            } while (count($list) <= $jumlah);
        endif;
        $mention_people = '';
        for ($i = 0; $i < count($list); $i++)
            $mention_people .= '@' . $list[$i] . ' ';
        preg_match('#{::(.*?)::}#', $teks, $teks1);
        return str_replace('{::' . $teks1[1] . '::}', $mention_people, $teks);
    else:
        return false;
    endif;
}

function unicode_decode($str)
{
    return preg_replace_callback("/\\\u([0-9a-f]{4})/i", create_function('$matches', 'return html_entity_decode(\'&#x\'.$matches[1].\';\', ENT_QUOTES, \'UTF-8\');'), $str);
}

function getuid($username)
{
    $url      = "https://www.instagram.com/" . $username;
    $html     = file_get_contents($url);
    $arr      = explode('window._sharedData = ', $html);
    $arr      = explode(';</script>', $arr[1]);
    $obj      = json_decode($arr[0], true);
    $id       = $obj['entry_data']['ProfilePage'][0]['graphql']['user']['id'];
    
    return $id;
}

function getmediaid($url)
{
    $getid   = file_get_contents("https://api.instagram.com/oembed/?url=".$url);
    $json1   = json_decode($getid);
    $mediaid = $json1->media_id;
    if($mediaid){
        return $mediaid;
    } else {
        return false;
    }
}