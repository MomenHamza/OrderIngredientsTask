<?php

require __DIR__ .'/vendor/autoload.php';

$root_uri='/order_ingred_task';
$uri = parse_url($_SERVER['REQUEST_URI'])['path'];

$routes=[
    $root_uri."/" => "index.php",
    $root_uri."/order" => "controllers/orderController.php"
];

if(key_exists($uri,$routes)){
    require $routes[$uri];
}else{
    abort();
}

function abort(){
    http_response_code(404);
    die;
}
