<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\controller;
use gear\arch\Bundle;
use gear\arch\core\IEngineFactory;

/*</namespace.current>*/

/*<bundles>*/
Bundle::Arch('core\IEngineFactory');
/*</bundles>*/

/*<module>*/
class DefaultControllerFactory implements IEngineFactory
{
    function createEngine($context)
    {
        $route = $context->getRoute();
        $mvcContext = $route->getMvcContext();

        $controllerName = $mvcContext->getControllerName();

        if(substr($controllerName, strlen($controllerName) - 10) != 'Controller')
            $controllerName .= 'Controller';
        Bundle::resolveUserModule("controllers\\".$controllerName.'.php');
        return new $controllerName();
    }
}
/*</module>*/
?>