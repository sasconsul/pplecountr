<?php 	
	
	//Url to the IQEngines Query api
	$url = "http://api.iqengines.com/v1.2/query/";
	
	//Get the current time in UTC Time Zone (Else we get client-server time mismatch)
	date_default_timezone_set('UTC');
    $timestamp = date('YmdHis');

	//File name is: eg. "image20100405224038.png"
	// $target = "uploads/image" . $timestamp. ".png"; 
	$target = "uploads/crowd_makovitch.png"; 
	
	//In my example, the file is received from an iPhone application.
	if(move_uploaded_file($_FILES["media"]["tmp_name"], $target)) {
    	//CONTINUE
    	//echo json_encode("The file has been uploaded to " . $target);
	} else{
 	   echo json_encode("There was an error uploading the file, please try again!");
	}
	
	//API variables
	$api_key = 'e20eec7cb14e45aca1875ec0cd91a48c';
	$webhook = 'http://pcntr1.dev4.webenabled.net/iqengines/webhook.php';
	$img = '@'.realpath($target).';type=image/jpg';
	$json = '1';
	$api_secret = '30809ddfbcb6403496544c0eb1619c14';
	$filename = "image" . $timestamp. ".png";
	//I use an extra Device_ID variable to identify which device sent the query. This is handy for catching the results afterwards.
	$extra = $_POST['device_id'];
	
	//Compose the api raw signature string
	$temp_api_sig = 'api_key'.$api_key.'extra'.urlencode($extra).'img'.$filename.'json'.$json.'time_stamp'.$timestamp.'webhook'.urlencode($webhook);
	
	//Encode the api signature
	$api_sig = hash_hmac("sha1",$temp_api_sig,$api_secret,false);
	
	//Just echo-ing all of my variables
	/*echo 'tempAPI: '.$temp_api_sig;
	echo '<br />';
	echo 'img: '.$filename;
	echo '<br />';
	echo 'api_key: '.$api_key;
	echo '<br />';
	echo 'api_secret: '.$api_secret;
	echo '<br />';
	echo 'api_sig: '.$api_sig;
	echo '<br />';
	echo 'timestamp: '.$timestamp;
	echo '<br />';
	echo 'json: '.$json;
	echo '<br />';
	echo 'webhook: ' .$webhook;*/
	
	//Preparing the data we will be sending
	$fields = array(
		'api_key' => urlencode($api_key),
		'img' => $img,
		'api_sig' => $api_sig,
		'time_stamp' => urlencode($timestamp),
		'json' => urlencode($json),
		'webhook' => urlencode($webhook),
		'extra' => urlencode($extra)
	);
	
	
	//url-ify the data for the POST 
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
?>