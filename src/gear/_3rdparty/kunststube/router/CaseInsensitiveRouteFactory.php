<?php

/*<namespace.current>*/
namespace Kunststube\Router;
/*</namespace.current>*/
/*<namespace.use>*/
use Kunststube\Router\RouteFactory;
use Kunststube\Router\CaseInsensitiveRoute;
/*</namespace.use>*/

/*<includes>*/
require_once __DIR__ . DIRECTORY_SEPARATOR . 'RouteFactory.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'CaseInsensitiveRoute.php';
/*</includes>*/

/*<module>*/
class CaseInsensitiveRouteFactory extends RouteFactory {

    public function newRoute($pattern, array $dispatch = array()) {
        return new CaseInsensitiveRoute($pattern, $dispatch);
    }
}
/*</module>*/