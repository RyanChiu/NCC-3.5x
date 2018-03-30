<?php
	/*
	 * define the constants for sites from BBR:
	 * THE CONSTANT NAME combined with 
	 * upper cased abbreviation of the site and "_CHS".
	 * say CAMS2_CHS is for site with abbreviation "cams2".
	 * THE CONSTANT VALUE is a set of numbers seperated by ",".
	 */
	define("CAMS2_CHS", "0,1,2,3,8,9");
	define("CAMS3_CHS", "4,5,6,7,23,24");
	define("BBRD_CHS", "5,6");
	define("ADC_CHS", "10");
	define("BLDS_CHS", "11,12,13,14,28,29");
	define("SXUP_CHS", "15,16,17,18");
	define("NTCP_CHS", "19,20,21,22");
	define("CD02_CHS", "25,26");
	define("SCL_CHS", "27");
	/*
	 * critical time to change biweek into 1-15 or 16-end of a month
	 */
	define("INTOHALFMONTHLYBIWEEKDAY", '2016-12-01');
	
	/*
	 * key string that will be used for encrypt and decrypt
	 */
	define("LINKGENKEY", "ohmyOHMY123$%^");

	/*
	 * routines area
	 */
	//date_default_timezone_set("Asia/Manila");
	date_default_timezone_set("EST5EDT");
	
	/*
	 * functions area
	 */
	function __codec($string, $operation) {
		$codes = array(
			array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', ',', ' '),
			array('6', '2', '0', 'a', 'c', '1', '3', '4', 'd', '5' ,'7', 'f')
		);
        if($operation=='D')
        {
        	$d = '';
        	for ($i = 0; $i < strlen($string); $i++) {
        		for ($j = 0; $j < count($codes[1]); $j++) {
        			if ($codes[1][$j] == $string[$i]) break;
        		}
        		if ($j == count($codes[1])) return 'err';
        		$d .= $codes[0][$j];
        	}
            return $d;
        }
        else
        {
        	$e = '';
        	for ($i = 0; $i < strlen($string); $i++) {
        		for ($j = 0; $j < count($codes[0]); $j++) {
        			if ($codes[0][$j] == $string[$i]) break;
        		}
        		if ($j == count($codes[0])) return 'err';
        		$e .= $codes[1][$j];
        	}
            return $e;
        }
    }
    
    function __getclientip() {
    	$onlineip = false;
		if(getenv('HTTP_CLIENT_IP')) { 
			$onlineip = getenv('HTTP_CLIENT_IP');
		} elseif(getenv('HTTP_X_FORWARDED_FOR')) { 
			$onlineip = getenv('HTTP_X_FORWARDED_FOR');
		} elseif(getenv('REMOTE_ADDR')) { 
			$onlineip = getenv('REMOTE_ADDR');
		} else { 
			$onlineip = $HTTP_SERVER_VARS['REMOTE_ADDR'];
		}
		return $onlineip;
    }
    
/*
   function __isblocked($ip, $fiplst = 'philippines') {
    	$handle = fopen(APP . 'vendors' . DS . $fiplst . '.txt', 'r');
    	while (!feof($handle)) {
    		$buf = fgets($handle);
    		$subnet = explode('/', $buf);
    		if (count($subnet) == 2) {
    			//echo '(ip:' . $subnet[0] . ', mask:' . long2ip(0xffffffff << 32 - $subnet[1]) . ')';
    			if (ip2long($ip) >> (32 - $subnet[1]) == ip2long($subnet[0]) >> (32 - $subnet[1])) return true;
    		}
    	}
    	fclose($handle);
    	return false;
    }
*/
   function __isblocked($ip, $fiplst = 'philippines') {
        $url="http://208.76.89.61/isBlock.php?ip=$ip";
        $scrape_ch = curl_init();
        curl_setopt($scrape_ch, CURLOPT_URL, $url);
        curl_setopt($scrape_ch, CURLOPT_RETURNTRANSFER, true);
        
        $scrape = curl_exec( $scrape_ch );
        return "Y" == $scrape;
   }
   
	function __fillzero4m($str, $forelen = 24, $afterlen = 24) {
		/*
		 * we get rid of characters followed "_" (and "_" itself)
		 * from $str here,in order to make the similar username
		 * be closely after sorting.
		 */
		$pos = strpos($str, "_");
		if ($pos !== false) {
			$str = substr($str, 0, $pos);
		}
		
		$str0 = $str;
		$str1 = "";
		for ($i = 0; $i < strlen($str); $i++) {
			if ($str{$i} >= '0' && $str{$i} <= '9') {
				$str0 = substr($str, 0, $i);
				$str1 = substr($str, $i, strlen($str) - $i);
				break;
			}
		}
		
		$str0 = $str0 . str_repeat("0", $forelen - strlen($str0));
		$str1 = str_repeat("0", $afterlen - strlen($str1)) . $str1;
		
		$str = substr($str0, 0, $forelen) . substr($str1, 0, $afterlen);
		
		return $str;
	}
	
	/*functions for stats drivers*/
	function __stats_get_abbr($argv0) {
		$path_parts = pathinfo($argv0);
		$basenames = explode("_", $path_parts['basename']);
		return $basenames[0];
	}
	
	function __stats_get_types_site(&$typeids, &$siteid, $abbr, $dblink) {
		/*find out the typeids and siteid from db by the abbreviation of the site*/
		$sql = sprintf(
			'select a.id as typeid, a.siteid from types a, sites b'
			. ' where a.siteid = b.id and b.abbr = "%s"'
			. ' order by a.id',
			$abbr
		);
		$rs = mysql_query($sql, $dblink)
			or die ("Something wrong with: " . mysql_error());
		$typeids = array();
		$siteid = null;
		while ($row = mysql_fetch_assoc($rs)) {
			array_push($typeids, $row['typeid']);
			$siteid = $row['siteid'];
		}
	}

	function __stats_get_type_srcdriver_site(&$typeid, &$siteid, &$srcdriver, $abbr, $dblink) {
		/*find out the typeids and siteid from db by the abbreviation of the site*/
		$sql = sprintf(
			'select a.id as typeid, a.siteid, b.srcdriver from types a, sites b'
			. ' where a.siteid = b.id and b.abbr = "%s"'
			. ' order by a.id',
			$abbr
		);
		$rs = mysql_query($sql, $dblink)
			or die ("Something wrong with: " . mysql_error());
		$siteid = null;
		$row = mysql_fetch_assoc($rs);
		$typeid = $row['typeid'];
		$siteid = $row['siteid'];
		$srcdriver = $row['srcdriver'];
	}
	
	/*
	 * try to send an email
	 */
	function __phpmail($mailto = "agents.maintainer@gmail.com", $subject = "", $content = "") {
		require_once("Mail.php");
		$mailer = Mail::factory(
			"SMTP",
			array(
				'host' => "ssl://smtp.gmail.com",
				'port' => "465",
				'auth' => true,
				'username' => "agents.maintainer@gmail.com",
				'password' => "`1qaz2wsx"
			)
		);
		
		$a_headers['From'] = "agents.maintainer@gmail.com";
		$a_headers['To'] = $mailto;
		
		$a_headers['Subject'] = $subject;
		
		$res = $mailer->send($a_headers['To'], $a_headers, $content);
		if ($res) {
			$mailinfo = 'email sent.';
		} else {
			$mailinfo = $res->getMessage();
		}
		return $mailinfo;
	}
	
	/*
	 * get the local date of the stats servers
	 * parameters:
	 * origin_dt	the string present date, like 2010-05-01,12:34:56
	 * remote_tz	the time zone of the remote server, like "Europe/London"
	 * offset_h		the offset time in hours
	 * origin_tz	the time zone of the server which the origin_dt belongs to, like "America/New_York"
	 * islongf		if the return value should be as 2010-05-01 or 2010-05-01 12:00:01
	 */
	function __get_remote_date($origin_dt, $remote_tz = null, $offset_h = -1, $origin_tz = "America/New_York", $islongf = false) {
		$err = "Illegal parameter, it should be like '2010-05-01,12:34:56'.\n";
		if (strpos($origin_dt, ",") === false) {
			exit($err);
		}
		$datestr = trim(str_replace(",", " ", $origin_dt));
		if (strlen($datestr) != 19) {
			exit($err);
		}
		if (strtotime($datestr) == -1) {
			exit($err);
		}
		$arydt = explode(",", $origin_dt);
		$ymdhis = array();
		$ymdhis[0] = explode("-", $arydt[0]);
		if (count($ymdhis[0]) != 3) {
			exit($err);
		}
		$ymdhis[1] = explode(":", $arydt[1]);
		if (count($ymdhis[0]) != 3) {
			exit($err);
		}
		if ($remote_tz == null) {
			return $islongf ? $arydt[0] . " " . $arydt[1] : $arydt[0];
		}
		
		$_origin_dtz = new DateTimeZone($origin_tz);
		$_remote_dtz = new DateTimeZone($remote_tz);
		$_origin_dt = new DateTime("now", $_origin_dtz);
		$_remote_dt = new DateTime("now", $_remote_dtz);
		$offset = $_origin_dtz->getOffset($_origin_dt) - $_remote_dtz->getOffset($_remote_dt);
		$dt = date($islongf ? "Y-m-d H:i:s" : "Y-m-d",
			mktime(
				$ymdhis[1][0], $ymdhis[1][1], 
				$ymdhis[1][2] - $offset + ($offset_h * 3600), 
				$ymdhis[0][1], $ymdhis[0][2], $ymdhis[0][0])
		);
		return $dt;
	}
	
	/*
	 * for CKEditor, the file upload function module
	 */
	function __mkuploadhtml($fn,$fileurl,$message) 
	{ 
		$str = '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction('
			. $fn
			. ', \''
			. $fileurl
			. '\', \''
			. $message
			. '\');</script>'; 
		return $str;
	}
	
	/*
	 * try to save a cookie forever
	 * when $cookievalue equals to null or is ignored, it'll try
	 * to reset the cookie named $cookiename for 1 year again if
	 * it exists and return the value of it, otherwise just will
	 * return null.
	 * when $cookievalue does not equal to null, it'll try to set
	 * the cookie named $cookiename the value of $cookievalue, and
	 * then return the value of $cookievalue, otherwise just will
	 * return null, too. 
	 */
	function __crucify_cookie($cookiename, $cookievalue = null) {
		if ($cookievalue == null) {
			if (isset($_COOKIE[$cookiename])) {
				setcookie(
					$cookiename,
					$_COOKIE[$cookiename], 
					time() + (60 * 60 * 24 * 365)// it seems that it could only be saved for 1 year
				);
				return $_COOKIE[$cookiename];
			}
			return null;
		} else {
			setcookie(
				$cookiename, 
				$cookievalue, 
				time() + (60 * 60 * 24 * 365)// it seems that it could only be saved for 1 year
			);
			if (isset($_COOKIE[$cookiename])) {
				return $_COOKIE[$cookiename];
			} else {
				return null;
			}
		}
	}
	
	/*
	 * try to judge if it's in daylight saving time (summer time).
	 * 1 means true, and 0 means false;
	 */
	function is_dst()
	{
		$timezone = date('e'); //get local time zone
		date_default_timezone_set('US/Pacific-New'); //set time zone
		$dst = date('I'); //judge
		date_default_timezone_set($timezone); //set time zone back
		return $dst;
	}
	
	/*
	 * get the current biweek "start and and"
	 */
	function __getCurBiweek() {
		$startwith_start = "2016-10-30";
		$startwith_end = date("Y-m-d", strtotime($startwith_start . " + 2 weeks - 1 day"));
		$today = date("Y-m-d");
		while ($today > $startwith_end) {
			$startwith_start = date("Y-m-d", strtotime($startwith_start . " + 2 weeks"));
			$startwith_end = date("Y-m-d", strtotime($startwith_start . " + 2 weeks - 1 day"));
		}
		/*
		 * special trick for $startwith_end in 11/13/2016~11/30/2016
		 */
		if ($startwith_end >= '2016-11-21' && $startwith_end <= '2016-11-30'
			|| $startwith_start >= '2016-11-21' && $startwith_start <= '2016-11-30') {
			$startwith_start = '2016-11-13';
			$startwith_end = '2016-11-30';
		}
		/*
		 * and after 2016-11-30, biweek should be 1-15, and 16-the end of a month
		 */
		if ($today >= INTOHALFMONTHLYBIWEEKDAY){
			$day = date("d");
			if ($day <= 15) {
				$startwith_start = date("Y-m-01");
				$startwith_end = date("Y-m-15");
			} else {
				$startwith_start = date("Y-m-16");
				$startwith_end = date("Y-m-d", strtotime(date("Y-m-01") . " + 1 month - 1 day"));
			}
		}
		return $startwith_start . "," . $startwith_end;
	}
	
	/*
	 * get the previous biweek "start and and" by given current biweek
	 * --only suitable when date is after 2016-11-30
	 */
	function __getPreBiweek($curbiweek) {
		$se = explode(",", $curbiweek);
		$start = $se[0];
		$end = $se[1];
		if (date("d", strtotime($start)) == "01") {
			$e = date("Y-m-d", strtotime($start . " - 1 day"));
			$s = date("Y-m-16", strtotime($start . " - 1 day"));
			return $s . "," . $e;
		} else if (date("d", strtotime($start)) == "16") {
			$s = date("Y-m-01", strtotime($start));
			$e = date("Y-m-15", strtotime($start));
			return $s . "," . $e;
		} else {
			return null;
		}
	}
	
	/*
	 * get the periods in select box for top10s and progresses and etc.
	 */
	function __getPeriods() {
		$lastday = date("Y-m-d", strtotime(date('Y-m-d') . " Sunday"));
		if (date("w") == 0) {
			$lastday = date("Y-m-d", strtotime($lastday . " + 6 days"));
		} else {
			$lastday = date("Y-m-d", strtotime($lastday . " - 1 days"));
		}
		$weekend = $lastday;
		$weekstart = date("Y-m-d", strtotime($lastday . " - 6 days"));
		$periods = array();
		for ($i = 0; $i < 52; $i++) {
			$oneweek = date("Y-m-d", strtotime($lastday . " - " . (7 * $i + 6) . " days"))
			. ',' . date("Y-m-d", strtotime($lastday . " - " . (7 * $i) . " days"));
			$v = "[W]$oneweek";
			switch ($i) {
				case 0:
					$v = 'THIS WEEK';
					break;
				case 1:
					$v = 'LAST WEEK';
					break;
				default:
					break;
			}
			$periods += array($oneweek => $v);
		}
		$curbiweek = __getCurBiweek();
		$curbiweekse = explode(",", $curbiweek);
		$biweekstart = $curbiweekse[0];
		$biweekend = $curbiweekse[1];
		$periods += array($biweekstart . "," . $biweekend => "[2W]" . $biweekstart . "," . $biweekend);
		/*
		 * and after 2016-11-30, biweek should be 1-15, and 16-the end of a month
		 */
		
		for ($i = 1; $i <= 26; $i++) {
			$biweek = null;
			if (date("Y-m-d") < INTOHALFMONTHLYBIWEEKDAY) {
				$biweek = 
					date("Y-m-d", strtotime($biweekstart . sprintf(" - %d", $i * 2) . " weeks"))
					. ','
					. date("Y-m-d", strtotime($biweekstart . sprintf(" - %d", ($i - 1) * 2) . " weeks - 1 day"));
			} else {
				$biweek = __getPreBiweek($curbiweek);
				$curbiweek = $biweek;
			}
			$periods += array($biweek => "[2W]$biweek");
		}
		return $periods;
	}
	
	/**
	 * encrypt, decrypt
	 */
	function encrypt($data, $key)
	{
		$key    =    md5($key);
		$x        =    0;
		$len    =    strlen($data);
		$l        =    strlen($key);
		for ($i = 0; $i < $len; $i++)
		{
			if ($x == $l)
			{
				$x = 0;
			}
			$char .= $key{$x};
			$x++;
		}
		for ($i = 0; $i < $len; $i++)
		{
			$str .= chr(ord($data{$i}) + (ord($char{$i})) % 256);
		}
		return base64_encode($str);
	}
	
	function decrypt($data, $key)
	{
		$key = md5($key);
		$x = 0;
		$data = base64_decode($data);
		$len = strlen($data);
		$l = strlen($key);
		for ($i = 0; $i < $len; $i++)
		{
			if ($x == $l)
			{
				$x = 0;
			}
			$char .= substr($key, $x, 1);
			$x++;
		}
		for ($i = 0; $i < $len; $i++)
		{
			if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1)))
			{
				$str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
			}
			else
			{
				$str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
			}
		}
		return $str;
	}
?>
