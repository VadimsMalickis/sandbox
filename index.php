<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing;


require_once __DIR__ .  "/vendor/autoload.php";

$request = Request::createFromGlobals();
$routes = include __DIR__ . '/routes.php';


$context = new Routing\RequestContext();
$context->fromRequest($request);
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

try {
    extract($matcher->match($request->getPathInfo()), EXTR_SKIP);
    ob_start();
    include sprintf(__DIR__ . '/views/%s.php', $_route);

    $response = new Response(ob_get_clean());

} catch (Routing\Exception\ResourceNotFoundException $exception) {
    $response = new Response('Not Found', 404);
} catch (Exception $exception) {
    $response = new Response('An error occurred', 500);
}


$response->prepare($request);
$response->send();
