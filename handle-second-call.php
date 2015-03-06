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
        //First and secondary did not answer.
    ?>
    <Hangup/>
    <?php
    }
    ?>
</Response>
