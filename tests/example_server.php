<?php

use Codeages\Weblib\Auth\Authentication;
use Codeages\Weblib\Auth\MockKeyProvider;
use Symfony\Component\HttpFoundation\Request;

require dirname(__DIR__).'/vendor/autoload.php';

$keyProvider = new MockKeyProvider();
$authentication = new Authentication($keyProvider);

$request = Request::createFromGlobals();

try {
    $key = $authentication->auth($request);
    echo json_encode($key);
} catch(\Exception $e) {
    $error = array(
        'code' => $e->getCode(),
        'message' => $e->getMessage(),
    );
    echo json_encode(array('error' => $error, 'HEADER' => $request->server->getHeaders()));
}