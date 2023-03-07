<?php

require_once("lib/client.php");

// The from number is your purhcased or test Sinch number. (https=>//developers.sinch.com/docs/voice/getting-started/#getting-started) 
$from = "+4600000001";

// The to number is the destination you are calling.  Please ensure both numbers are in E164 format. (https=>//community.sinch.com/t5/Glossary/E-164/ta-p/7537)
$to = "+46111111111";


$instructions = [
        "name" => "connectPstn",
        "text" => "test123"
];


$ice = [ 
    "instructions" => [array_values($instructions)],
    "action" => [
        "name" => "connectPstn",
        "cli" => "+PUTNUMBERINHERE",
        "number" => "PUTNUMBERHERE",
    ]];

$ace = [
    "action" => [
        "name" => "continue"
    ]  
];


#$iceesc = str_replace(str_split('"'), '\"', json_encode($ice));
#$aceesc = str_replace(str_split('"'), '\"', json_encode($ace));
  
#print("Modified string: ");
#print($iceesc);
#print($aceesc);


$calloutRequest = [
  "method" => "customCallout",
  "customCallout" => [
    "ice" => [json_encode($ice)],
    "ace" => json_encode($ace)
    ]
];

printf(json_encode($calloutRequest));

#$client = new client;
#$call = $client->call($calloutRequest);
######
#print("Callout request response=> {$call}");
