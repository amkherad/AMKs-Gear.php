<?php

/*<namespace.current>*/
namespace Kunststube\Router;
/*</namespace.current>*/
/*<namespace.use>*/
use Kunststube\Router\Route;
/*</namespace.use>*/

/*<includes>*/
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Router.php';
/*</includes>*/

/*<module>*/
class RouteFactory {

    public function newRoute($pattern, array $dispatch = array()) {
        return new Route($pattern, $dispatch);
    }

}
/*</module>*/