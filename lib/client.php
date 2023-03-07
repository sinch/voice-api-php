<?php 

declare(strict_types=1);

require_once ('vendor/autoload.php');
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '/../.env');
$dotenv->load();


class client
{
    private $key;
    private $secret;
    private $httpVerb;
    private $contentType;
    private $uriPath;
    private $timeStamp;

    public function __construct()
    {
        $this->key = $_ENV['KEY'];;  
        $this->secret = $_ENV['SECRET'];;  
        $this->httpVerb = "POST";  
        $this->contentType = "application/json; charset=UTF-8";
        $this->baseUrl = "https://calling.api.sinch.com";
        $this->uriPath = "/calling/v1/callouts/";
        $this->timeStamp = date(DateTime::ATOM);
        #$this->timeStamp = new DateTime("now", new DateTimeZone("UTC"));
    }

    private function sign($calloutRequest)
    {
         
        $b64DecodedApplicationSecret = base64_decode($this->secret, true);
        $encodedCalloutRequest = utf8_encode(json_encode($calloutRequest, JSON_UNESCAPED_UNICODE));
        $md5CalloutRequest = md5($encodedCalloutRequest, true);
        $encodedMd5ToBase64CalloutRequest = base64_encode($md5CalloutRequest);
        $xTimeStamp = "x-timestamp:{$this->timeStamp}";

        date_default_timezone_set('UTC');
        $stringToSign = $this->httpVerb . "\n"
            . $encodedMd5ToBase64CalloutRequest . "\n"
            . $this->contentType . "\n"
            . $xTimeStamp . "\n"
            . $this->uriPath;

        $authorizationSignature = base64_encode(hash_hmac("sha256", $stringToSign, $b64DecodedApplicationSecret, true));

        return $authorizationSignature;

    }

    public function call($calloutRequest){

        $signature = $this->sign($calloutRequest);
        
        $curl = curl_init();
        
        curl_setopt_array($curl, [
          CURLOPT_HTTPHEADER => [
            "content-type: {$this->contentType}",
            "x-timestamp: {$this->timeStamp}",
            "authorization: application {$this->key}:{$signature}"
          ],
          CURLOPT_POSTFIELDS => json_encode($calloutRequest),
          CURLOPT_URL => $this->baseUrl."".$this->uriPath,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_CUSTOMREQUEST => $this->httpVerb,
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    
    }

}
?>
