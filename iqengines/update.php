<?php 	
	//Url to the IQEngines Update api
	$url = "http://api.iqengines.com/v1.2/update/";
	
	//Get the current time in UTC Time Zone (Else we get client-server time mismatch)
	date_default_timezone_set('UTC');
    $timestamp = date('YmdHis');
	
	//API variables
	$api_key = '<YOUR API_KEY>';
	$json = '1';
	$api_secret = '<YOUR API_SECRET>';
	
	//Compose the api raw signature string
	$temp_api_sig = 'api_key'.$api_key.'json'.$json.'time_stamp'.$timestamp;
	
	//Encode the api signature
	$api_sig = hash_hmac("sha1",$temp_api_sig,$api_secret,false);
	
	//Just echo-ing all of my variables
	/*trace('tempAPI: '.$temp_api_sig);
	trace('img: '.$img);
	trace('api_key: '.$api_key);
	trace('api_secret: '.$api_secret);
	trace('api_sig: '.$api_sig);
	trace('timestamp: '.$timestamp);
	trace('json: '.$json);*/
	
	//Preparing the data we will be sending
	$fields = array(
		'api_key' => urlencode($api_key),
		'api_sig' => $api_sig,
		'time_stamp' => urlencode($timestamp),
		'json' => urlencode($json)
	);
	
	
	//Url-ify the data for the POST 
	$fields_string = "";
	foreach($fields as $key=>$value) { 
		$fields_string .= $key.'='.$value.'&'; 
	}
	rtrim($fields_string,'&');
	
	
	//Here I make the cURL request to the API
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,true);  // RETURN THE CONTENTS OF THE CALL
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
 	curl_setopt($ch, CURLOPT_POST      , true);
 	curl_setopt($ch, CURLOPT_POSTFIELDS    , $fields);
 	curl_setopt($ch, CURLOPT_HEADER      ,false);  // DO NOT RETURN HTTP HEADERS 
 	$response = curl_exec($ch);
 	
 	//View the response from the API server
 	echo $response;
 	
 	//If no response is sent back from the server, silmply resend this query.
?>