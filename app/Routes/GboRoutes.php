<?php

namespace App\Routes;

use Config\Services;

$routes = Services::routes();

// Gbo Routes

$routes->get('/', 'Auth::index');