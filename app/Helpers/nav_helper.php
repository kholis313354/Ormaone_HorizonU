<?php

use CodeIgniter\Router\RouteCollection;

/**
 * Cek apakah route saat ini aktif
 */
function set_active(string $routeName, string $className = 'active'): string
{
    $currentRoute = service('router')->getMatchedRouteOptions()['as'] ?? '';
    return ($currentRoute === $routeName) ? $className : '';
}
