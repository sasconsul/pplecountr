<?php


	//Get the webhook JSON data
	$json = file_get_contents('php://input');
	
	//Decode the JSON data
        $json = json_decode($json);
	
	
	//Write a file with the QID name. Save the retreived QID's within the application/database
	$myFile = "/tmp/iqe_results_". $json->qid .".json";
	$fh = fopen($myFile, 'w') or die("can't open file");
	

	fwrite($fh, $result);
	fclose($fh);
	

?>