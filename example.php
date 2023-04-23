<?php

require __DIR__ . '/vendor/autoload.php';

$client = new \GuzzleHttp\Client();

$slow = true;
$normal = false;
$lang = 'vi';
$text = 'Xin chào mọi người';
$speed = $normal;

$parameter = [$text, $lang, $speed, "null"];
$escapedParameter = json_encode($parameter);
$rpc = [[["jQ1olc", $escapedParameter, null, "generic"]]];
$espacedRpc = json_encode($rpc);
$paramUrl = 'f.req=' . urlencode($espacedRpc) . '&';
$uri = "https://translate.google.com/_/TranslateWebserverUi/data/batchexecute?$paramUrl";

$response = $client->request(
    'POST',
    $uri,
    [
        'headers' => [
            "Referer"=> "http://translate.google.com/",
            "User-Agent"=> "Mozilla/5.0 (Windows NT 10.0; WOW64) ",
            "AppleWebKit/537.36 (KHTML, like Gecko) ",
            "Chrome/47.0.2526.106 Safari/537.36",
            "Content-Type"=> "application/x-www-form-urlencoded;charset=utf-8",
        ]
    ]
);

$body = $response->getBody();

if (str_contains($body, 'jQ1olc')) {
    if (preg_match_all('/jQ1olc\"\,"\[\\\\"(.*)\\\\"\]/m', $body, $matches, PREG_SET_ORDER, 0)) {
        if (!empty($matches)) {
            $bytes = base64_decode(iconv("UTF-8", "ASCII", $matches[0][1]));
        }
    }
}

if ($fp = fopen('thuan.mp3', 'wb')) {
    fwrite($fp, $bytes);
    fclose($fp);
}