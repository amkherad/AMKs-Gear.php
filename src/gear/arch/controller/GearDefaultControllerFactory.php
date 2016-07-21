<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\controller;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\GearBundle;
use gear\arch\core\IGearEngineFactory;
use gear\arch\http\exceptions\GearHttpNotFoundException;
/*</namespace.use>*/

/*<bundles>*/
GearBundle::Arch('core\IGearEngineFactory');
/*</bundles>*/

/*<module>*/
class GearDefaultControllerFactory implements IGearEngineFactory
{
    function createEngine($context)
    {
        $config = $context->getConfig();
        $route = $context->getRoute();
        $mvcContext = $route->getMvcContext();

        $controllerName = $mvcContext->getControllerName();
        $areaName = $mvcContext->getAreaName();

        $controllerSuffix = $config->getValue(Gear_Key_ControllerSuffix, Gear_Section_Controller, Gear_DefaultControllerSuffix);

        if (substr($controllerName, strlen($controllerName) - 10) != $controllerSuffix)
            $controllerName .= $controllerSuffix;

        $controllerPath = "controllers\\" . $controllerName . '.php';
        if (isset($areaName) && $areaName != '') {
            $controllerPath = "$areaName\\$controllerPath";
            $areaRootPath = $config->getValue(Gear_Key_AreaRoot, Gear_Section_Controller, Gear_DefaultAreasRootPath);
            if (isset($areaRootPath) && $areaRootPath != '') {
                $controllerPath = "$areaRootPath\\$controllerPath";
            }
        }

        try {
            GearBundle::resolveUserModule($controllerPath);
        } catch (\Exception $ex) {
            throw new GearHttpNotFoundException("Controller '$controllerName' not found on '$controllerPath'.");
        }
        if (!class_exists($controllerName)) {
            throw new GearHttpNotFoundException("Controller '$controllerName' not found.");
        }
        return new $controllerName($context);
    }
}

/*</module>*/
?>