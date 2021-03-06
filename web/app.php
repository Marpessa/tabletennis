<?php

require_once __DIR__.'/../app/bootstrap.php.cache';
require_once __DIR__.'/../app/AppKernel.php';
require_once __DIR__.'/../app/AppCache.php';


//use Symfony\Component\ClassLoader\ApcClassLoader;
use Symfony\Component\HttpFoundation\ApacheRequest;

// Use APC for autoloading to improve performance
// Change 'sf2' by the prefix you want in order to prevent key conflict with another application
/*
$loader = new ApcClassLoader('sf2', $loader);
$loader->register(true);
*/

$kernel = new AppKernel('prod', false);
//$kernel->loadClassCache();

//$kernel = new AppCache($kernel);
$request = ApacheRequest::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
