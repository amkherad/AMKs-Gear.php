<?php

/*<namespace.current>*/
namespace Kunststube\Router;
/*</namespace.current>*/
/*<namespace.use>*/
use Kunststube\Router\Route;
/*</namespace.use>*/

/*<includes>*/
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Route.php';
/*</includes>*/

/*<module>*/
class CaseInsensitiveRoute extends Route {

    /**
     * Builds a complete case-insensitive regex that will match a valid URL.
     *
     * @return string
     */
    protected function buildRegex() {
        return sprintf('/^%s%s$/i', $this->regex, $this->wildcard ? '(.*)' : null);
    }
}
/*</module>*/