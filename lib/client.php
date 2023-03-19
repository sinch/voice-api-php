<?php

declare(strict_types=1);

require_once('vendor/autoload.php');
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '/../.env');
$dotenv->load();

class client
{
    private $key;
    private $secret;
    private $http_verb;
    private $content_type;
    private $uri_path;
    private $timestamp;

    public function __construct()
    {
        $this->key = $_ENV['KEY'];
        $this->secret = $_ENV['SECRET'];
        $this->http_verb = "POST";
        $this->content_type = "application/json; charset=UTF-8";
        $this->base_url = "https://calling.api.sinch.com";
        $this->uri_path = "/calling/v1/callouts/";
        $this->timestamp = date(DateTime::ATOM);
        $this->region = null;
    }

    private function sign($callout_request)
    {
        $b64_decoded_application_secret = base64_decode($this->secret, true);
        $encoded_callout_request = utf8_encode(json_encode($callout_request, JSON_UNESCAPED_UNICODE));
        $md5_callout_request = md5($encoded_callout_request, true);
        $encoded_md5_to_base64_callout_request = base64_encode($md5_callout_request);
        $x_timestamp = "x-timestamp:{$this->timestamp}";

        date_default_timezone_set('UTC');
        $string_to_sign = $this->http_verb . "\n"
            . $encoded_md5_to_base64_callout_request  . "\n"
            . $this->content_type . "\n"
            . $x_timestamp . "\n"
            . $this->uri_path;

        $authorization_signature = base64_encode(hash_hmac("sha256", $string_to_sign, $b64_decoded_application_secret, true));
        return $authorization_signature;
    }

    public function call($callout_request)

    {
        $signature = $this->sign($callout_request);

        $curl = curl_init();

        curl_setopt_array($curl, [
          CURLOPT_HTTPHEADER => [
            "content-type: {$this->content_type}",
            "x-timestamp: {$this->timestamp}",
            "authorization: application {$this->key}:{$signature}"
          ],
          CURLOPT_POSTFIELDS => json_encode($callout_request),
          CURLOPT_URL => $this->base_url."".$this->uri_path,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_CUSTOMREQUEST => $this->http_verb,
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
    public function setRegion($region)
    {
        $regions =  array('euc1' => 'Europe','use1'=> 'North America','sae1' => 'South America','apse1'=> 'Asia Pacific 1', 'apse2'=> 'Asia Pacific 2');
        $region_request = array_search($region, $regions);
        if (!empty($region_request)) {
            $this->region = $region_request;
            $this->base_url = str_replace("calling", "calling-$region_request", $this->base_url);
        } else {
            return $this->base_url;
        }
    }
}
