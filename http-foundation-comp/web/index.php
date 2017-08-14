<?php

// web/index.php
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


$request = Request::createFromGlobals();

$response = new Response(
    'Here is your "foo" query parameter: ' . $request->get('foo'),
    Response::HTTP_OK,
    array('content-type' => 'text/html')
);

$response->prepare($request);
$response->send();
