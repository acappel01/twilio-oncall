<?php
	header('Content-type: text/xml');
	echo '<?xml version="1.0" encoding="UTF-8"?>';

	echo '<Response>';

	echo '<Say>Brand X</Say>';
    $ch = curl_init();
	$url = "https://myorg.pagerduty.com/api/v1/escalation_policies/PHS65OI/on_call";
	$ch_array1 = 'Accept: application/json';
    $ch_array2 = 'Authorization: Token token=xxxx';
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	  $ch_array1,
	  $ch_array2,
	));

	$json = curl_exec($ch);
	$json_output = json_decode($json,true);
	$user_id =  $json_output['escalation_policy']['on_call'][0]['user']['id'];
	#echo $user_id."\n";
	curl_close($ch);
	#####

    $ch2 = curl_init();
	$url2 = "https://myorg.pagerduty.com/api/v1/users/".$user_id;
	$ch_array1 = 'Accept: application/json';
    $ch_array2 = 'Authorization: Token token=xxxx';
	curl_setopt($ch2, CURLOPT_URL,$url2);
	curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch2, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch2, CURLOPT_HTTPHEADER, array(
	  $ch_array1,
	  $ch_array2,
	));

	$json2 = curl_exec($ch2);
	$json_output2 = json_decode($json2,true);
	$tech_name =  $json_output2['user']['name'];
	echo '<Say>'.$tech_name."</Say>";
	#var_dump($tech_name);
	curl_close($ch2);

	#####

    $ch3 = curl_init();
	$url3 = "https://myorg.pagerduty.com/api/v1/users/".$user_id."/contact_methods";
	#echo $url3;
	$ch_array1 = 'Accept: application/json';
    $ch_array2 = 'Authorization: Token token=xxxx';
	curl_setopt($ch3, CURLOPT_URL,$url3);
	curl_setopt($ch3, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch3, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch3, CURLOPT_HTTPHEADER, array(
	  $ch_array1,
	  $ch_array2,
	));

	$json3 = curl_exec($ch3);
	$json_output3 = json_decode($json3,true);
	$tech_contact =  $json_output3['contact_methods'][1]['phone_number'];
	echo '<Dial>+1'.$tech_contact.'</Dial>';
	#var_dump($json_output3);
	curl_close($ch3);

	echo '</Response>';
?>

