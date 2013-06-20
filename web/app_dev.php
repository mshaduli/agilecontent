<?php


require_once __DIR__.'/../app/bootstrap.php.cache';
require_once __DIR__.'/../app/AppKernel.php';

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();

// if you want to use the SonataPageBundle with multisite
// using different relative paths, you must change the request
// object to use the SiteRequest
// use Sonata\PageBundle\Request\SiteRequest as Request;

use Symfony\Component\HttpFoundation\Request;

$kernel->handle(Request::createFromGlobals())->send();


function d($var){
    echo "<pre>";
    print_r($var);
    echo "</pre>";
}
