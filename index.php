<?php

// ------------ Laravel index.php when project is inside a subfolder ------------

$root = __DIR__;

// Autoload
require $root . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once $root . '/bootstrap/app.php';

// Run Laravel
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);