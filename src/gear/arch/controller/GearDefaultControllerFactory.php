<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\controller;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\GearBundle;
use gear\arch\core\IGearEngineFactory;
/*</namespace.use>*/

/*<bundles>*/
GearBundle::Arch('core\IGearEngineFactory');
/*</bundles>*/

/*<module>*/
class GearDefaultControllerFactory implements IGearEngineFactory
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
        if(isset($areaName) && $areaName != ''){
            $controllerPath = "$areaName\\$controllerPath";
        }

        GearBundle::resolveUserModule($controllerPath);
        return new $controllerName($context);
    }
}
/*</module>*/
?>