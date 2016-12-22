<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\helpers;
/*</namespace.current>*/
/*<namespace.use>*/
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IActionUrlBuilder
{
    function action($actionName, $controllerName = null, $routeParams = null, $queryStrings = null);
}
/*</module>*/
?>