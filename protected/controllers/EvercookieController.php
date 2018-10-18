<?php

class EverCookieController extends CController
{
	public function actionEverCookiePng() {
		//include dirname(__FILE__) . DIRECTORY_SEPARATOR . '_cookie_name.php';
		$cookie_name = $this->evercookie_get_cookie_name(__FILE__);

		// we don't have a cookie, user probably deleted it, force cache
		if (empty($_COOKIE[$cookie_name])) {
    		if(!headers_sent()) {
        		header('HTTP/1.1 304 Not Modified');
    		}
    		exit;
		}

		// width of 200 means 600 bytes (3 RGB bytes per pixel)
		$x = 200;
		$y = 1;

		$gd = imagecreatetruecolor($x, $y);

		$data_arr = str_split($_COOKIE[$cookie_name]);

		$x = 0;
		$y = 0;
		for ($i = 0, $i_count = count($data_arr); $i < $i_count; $i += 3) {
    		$red   = isset($data_arr[$i])   ? ord($data_arr[$i])   : 0;
    		$green = isset($data_arr[$i+1]) ? ord($data_arr[$i+1]) : 0;
    		$blue  = isset($data_arr[$i+2]) ? ord($data_arr[$i+2]) : 0;
    		$color = imagecolorallocate($gd, $red, $green, $blue);
    		imagesetpixel($gd, $x++, $y, $color);
		}

		if(!headers_sent()) {
    		header('Content-Type: image/png');
    		header('Last-Modified: Wed, 30 Jun 2010 21:36:48 GMT');
    		header('Expires: Tue, 31 Dec 2030 23:30:45 GMT');
    		header('Cache-Control: private, max-age=630720000');
		}

		// boom. headshot.
		imagepng($gd);

	}

	public function actionEverCookieEtag() {
		//include dirname(__FILE__) . DIRECTORY_SEPARATOR . '_cookie_name.php';
		$cookie_name = $this->evercookie_get_cookie_name(__FILE__);

		// we don't have a cookie, so we're not setting it
		if (empty($_COOKIE[$cookie_name])) {
   		 // read our etag and pass back
    		if (!function_exists('apache_request_headers')) {
       			 function apache_request_headers() {
            		// Source: http://www.php.net/manual/en/function.apache-request-headers.php#70810
            		$arh = array();
            		$rx_http = '/\AHTTP_/';
            		foreach ($_SERVER as $key => $val) {
               			 if (preg_match($rx_http, $key)) {
                    			$arh_key = preg_replace($rx_http, '', $key);
                    			$rx_matches = array();
                    			// do some nasty string manipulations to restore the original letter case
                    			// this should work in most cases
                   			 $rx_matches = explode('_', $arh_key);
                    		if (count($rx_matches) > 0 and strlen($arh_key) > 2) {
                        		foreach ($rx_matches as $ak_key => $ak_val) {
                            		$rx_matches[$ak_key] = ucfirst(strtolower($ak_val));
                        		}
                        		$arh_key = implode('-', $rx_matches);
                   			 }
                    		$arh[$arh_key] = $val;
                		}
            		}
            		return ($arh);
        		}
    		}

    		$headers = apache_request_headers();
    		if(isset($headers['If-None-Match'])) {
       		 // extracting value from ETag presented format (which may be prepended by Weak validator modifier)
        		$etag_value = preg_replace('|^(W/)?"(.+)"$|', '$2', $headers['If-None-Match']);
        		header('HTTP/1.1 304 Not Modified');
        		header('ETag: "' . $etag_value . '"');
       		    echo $etag_value;
    		}
    		exit;
		}

		// set our etag
		header('ETag: "' . $_COOKIE[$cookie_name] . '"');
		echo $_COOKIE[$cookie_name];

	}

	public function actionEverCookieCache() {
		//include dirname(__FILE__) . DIRECTORY_SEPARATOR . '_cookie_name.php';
		$cookie_name = $this->evercookie_get_cookie_name(__FILE__);
		

		// we don't have a cookie, user probably deleted it, force cache
		if (empty($_COOKIE[$cookie_name])) {
    		header('HTTP/1.1 304 Not Modified');
    		exit;
		}

		header('Content-Type: text/html');
		header('Last-Modified: Wed, 30 Jun 2010 21:36:48 GMT');
		header('Expires: Tue, 31 Dec 2030 23:30:45 GMT');
		header('Cache-Control: private, max-age=630720000');

		echo $_COOKIE[$cookie_name];
	}

	public function evercookie_get_cookie_name($file_name) {
    	if (!empty($_GET['cookie'])) {
       		 return $_GET['cookie'];
    	}
    	return basename($file_name, '.php');
	}
}
?>