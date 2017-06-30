<?php

require dirname(__DIR__).'/vendor/autoload.php';

use Codeages\RestApiClient\RestApiClient;
use Codeages\RestApiClient\Specification\JsonHmacSpecification;
use Codeages\RestApiClient\Specification\SimpleJsonHmacSpecification;

$config = array(
    'accessKey' => 'key_ok',
    'secretKey' => 'key_ok_secret',
    'endpoint' => 'http://localhost:8000',
);
$spec = new SimpleJsonHmacSpecification('sha1');
$client = new RestApiClient($config, $spec);
$result = $client->get('/');

var_dump($result);

$config = array(
    'accessKey' => 'key_localhost',
    'secretKey' => 'key_localhost_secret',
    'endpoint' => 'http://localhost:8000',
);
$spec = new SimpleJsonHmacSpecification('sha1');
$client = new RestApiClient($config, $spec);
$result = $client->get('/');

var_dump($result);


