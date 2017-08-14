<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();
$routes->add('hello', new Route('/hello/{name}', array(
        '_controller' => function (Request $request) {
            return new Response(
                sprintf("Hello %s", $request->get('name'))
            );
        })
));

dump([
    'ROUTES AFTER THE FIRST ROUTE WAS ADDED: ',
    $routes->all()
]);

$request = Request::createFromGlobals();

dump([
    'REQUEST FORM GLOBALS: ',
    $request
]);

$matcher = new UrlMatcher($routes, new RequestContext());

dump([
    'URL MATCHER: ',
    $matcher
]);

$dispatcher = new EventDispatcher();
dump([
    'EVENT DISPATCHER BEFORE ROUTERLISTENER WAS ADDED: ',
    $dispatcher
]);
$dispatcher->addSubscriber(new RouterListener($matcher, new RequestStack()));

dump([
    'EVENT DISPATCHER AFTER ROUTERLISTENER WAS ADDED: ',
    $dispatcher
]);

$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

$kernel = new HttpKernel($dispatcher, $controllerResolver, new RequestStack(), $argumentResolver);
dump([
    'INITIALIZED KERNEL: ',
    $kernel
]);

$response = $kernel->handle($request);
dump([
    'RESPONSE FROM REQUEST VIA KERNEL: ',
    $response
]);
$response->send();

$kernel->terminate($request, $response);