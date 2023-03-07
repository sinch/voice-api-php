<?php

require_once("lib/client.php");

// The from number is your purhcased or test Sinch number. (https://developers.sinch.com/docs/voice/getting-started/#getting-started) 
$from = $_ENV["CLI"];

// The to number is the destination you are calling.  Please ensure both numbers are in E164 format. (https://community.sinch.com/t5/Glossary/E-164/ta-p/7537)
$to = $_ENV["TO"];

//
$calloutRequest = [
  "method" => "conferenceCallout",
  "conferenceCallout" => [
    "cli" => $from,
    "destination" => [
      "type" => "number",
      "endpoint" => $to
    ],
    "locale" => "en-US",
    "greeting" => "Welcome to the conference.",
    "conferenceId" => "MyConf-{$from}"
  ]
];

$client = new client;
$call = $client->call($calloutRequest);

print("Callout request response: {$call}");
