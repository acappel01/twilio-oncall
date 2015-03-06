<?xml version="1.0" encoding="UTF-8"?>
<Response>
    <?php
    if($_GET['DialCallStatus'] == 'completed'){
        $recorded_url = $_GET['RecordingUrl'];
        $recorded_url = "https://api.twilio.com".$recorded_url;
        $evt = new \PagerDuty\Event();
        $evt->setServiceKey('xxxx');
        $evt->setDescription('Incoming Call into On-Call Telephone Line.');
        $evt->setDetail('Call Recording URL',''.$recorded_url.'');
        $resp = $evt->trigger();
        var_dump( $resp, $evt->toArray(), $evt->getIncidentKey() );
        $resp = $evt->acknowledge();
        var_dump( $resp );
    }else{
        // There was no answer - go to secondary on-call.
        $ch = curl_init();
        $url = "https://myorg.pagerduty.com/api/v1/escalation_policies/PXV09M3/on_call";
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
        $user_id =  $json_output['escalation_policy']['on_call'][1]['user']['id'];
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
        $tech_contact =  $json_output3['contact_methods'][1]['phone'];
        echo '<Say>All calls are recorded.</Say>';
        echo '<Dial record = "record-from-ringing" action="handle-second-call.php">';
        echo '<Number>1'.$tech_contact.'</number>';
        echo '</Dial>';
        #var_dump($json_output3);
        curl_close($ch3);
    ?>
    <Hangup/>
    <?php
    }
    ?>
</Response>
