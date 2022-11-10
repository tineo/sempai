<?php
require 'vendor/autoload.php';
date_default_timezone_set('America/Lima');

use GuzzleHttp\Client;
use Twilio\Rest\Client as TwClient;

$config = parse_ini_file("config.ini", true);

$client = new Client([
    'base_uri' => 'https://apim.tucambista.pe',
    'timeout'  => 2.0,
]);


$res = $client->request('POST', '/api/transaction/quote?', [
    'headers'        => [
        'Content-Type' => 'application/json',
        'Ocp-Apim-Subscription-Key' => 'e4b6947d96a940e7bb8b39f462bcc56d;product=tucambista-production'
    ],
    'json' => [
        
            "amount"  => 1000,
            "buyOrSell" => "SELL",
            "ccy" => "PEN",
            "totalCreditsToUse" => 0,
            "cancelPromotionCode" => "",
            "promotionCode" => ""
        
    ]
]);

$data = json_decode($res->getBody());

if ($res->getStatusCode() != 200) { // check api is ok
    die();
}

$redis = new Redis();
$redis->connect($config["redis"]["host"], 6379);
$redis->auth($config["redis"]["auth"]);
if(!$redis->get("offerLast")){
    $redis->set("offerLast", $data->offerReferenceRate);
}else{
    $twilio = new TwClient($config["twilio"]["sid"], $config["twilio"]["token"]);
    if($redis->get("offerLast") < $data->offerReferenceRate){

        $redis->set("offerLast", $data->offerReferenceRate);

        $message = $twilio->messages
            ->create("whatsapp:{$config["notifications"]["wsp_number"]}", // to
                array(
                    "from" => "whatsapp:+14155238886",
                    "body" => "↓ {$data->offerReferenceRate}"
                )
            );
        print($message->sid);
    }else if($redis->get("offerLast") > $data->offerReferenceRate){

        $message = $twilio->messages
            ->create("whatsapp:{$config["notifications"]["wsp_number"]}", // to
                array(
                    "from" => "whatsapp:+14155238886",
                    "body" => "↑ {$data->offerReferenceRate}"
                )
            );
        print($message->sid);
    }
}


$host = $config["database"]["host"];
$db   = $config["database"]["db"];
$user = $config["database"]["user"];
$pass = $config["database"]["pass"];
$port = "3306";
$charset = 'utf8';

$options = [
    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    \PDO::ATTR_EMULATE_PREPARES   => false,
];
$dsn = "mysql:host=$host;dbname=$db;charset=$charset;port=$port";
try {
     $pdo = new \PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$dateRate =  date("Y-m-d");
$timeRate = date("H:i:d");



$sql = "INSERT INTO tucambista (bidRate, offerRate, bidReferenceRate, offerReferenceRate, dateRate, timeRate) VALUES (?,?,?,?,?,?)";
$stmt= $pdo->prepare($sql);
$stmt->execute([
    $data->bidRate, 
    $data->offerRate, 
    $data->bidReferenceRate, 
    $data->offerReferenceRate, 
    $dateRate, 
    $timeRate
    ]);
