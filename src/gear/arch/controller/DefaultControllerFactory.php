<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\controller;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\Bundle;
use gear\arch\core\IEngineFactory;
/*</namespace.use>*/

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
        $areaName = $mvcContext->getAreaName();

        if(substr($controllerName, strlen($controllerName) - 10) != 'Controller')
            $controllerName .= 'Controller';

        $controllerPath = "controllers\\".$controllerName.'.php';
        if(isset($areaName)){
            $controllerPath = "$areaName\\$controllerPath";
        }

        Bundle::resolveUserModule($controllerPath);
        return new $controllerName($context);
    }
}
/*</module>*/
?>