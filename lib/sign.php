<?php 

declare(strict_types=1);

require_once ('vendor/autoload.php');
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '/../.env');
$dotenv->load();

class applicationSignature
{

    public static function generate($calloutRequest)
    {
        $applicationSecret=$_ENV['SECRET'];
        
        $b64DecodedApplicationSecret = base64_decode($applicationSecret, true);
        $encodedCalloutRequest = utf8_encode(json_encode($calloutRequest, JSON_UNESCAPED_UNICODE));
        $md5CalloutRequest = md5($encodedCalloutRequest, true);
        $encodedMd5ToBase64CalloutRequest = base64_encode($md5CalloutRequest);

        $httpVerb = 'POST';
        $requestContentType = 'application/json; charset=UTF-8';
        date_default_timezone_set('UTC');
        $timeNow = date(DateTime::ATOM);
        $requestTimeStamp = "x-timestamp:" . $timeNow;
        $requestUriPath = "/calling/v1/callouts/";

        $stringToSign = $httpVerb . "\n"
            . $encodedMd5ToBase64CalloutRequest . "\n"
            . $requestContentType . "\n"
            . $requestTimeStamp . "\n"
            . $requestUriPath;

        $authorizationSignature = base64_encode(hash_hmac("sha256", $stringToSign, $b64DecodedApplicationSecret, true));

        return $authorizationSignature;
    }

    public static function key()
    {
        $applicationKey=$_ENV['KEY'];
        return $applicationKey;
    }

    public static function cli()
    {
        $cli=$_ENV['CLI'];
        return $cli;
    }

    public static function destination()
    {
        $destination=$_ENV['DESTINATION'];
        return $destination;
    }

    public static function sinchCalloutUrl()
    {
        $sinchCalloutUrl = "https://calling.api.sinch.com/calling/v1/callouts";
        return $sinchCalloutUrl;
    }

    public static function httpVerb() {
    
        return 'POST';
    
    }

    public static function timeNow() {
    
        return date(DateTime::ATOM);
    
    }

    public static function contentType() {

        $contentType ='application/json; charset=UTF-8';
        return $contentType;
    }


}
?>
