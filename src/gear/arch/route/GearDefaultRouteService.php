<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\route;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\GearNotSupportedException;
use gear\arch\route\IGearRouteService;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearDefaultRouteService implements IGearRouteService
{
    private
        $area,
        $controller,
        $action,
        $params,

        $mvcContext;

    public function __construct($config)
    {
        $area = $config->getValue(Gear_Key_DefaultArea, Gear_Section_Defaults, '');
        $controller = $config->getValue(Gear_Key_DefaultController, Gear_Section_Defaults, 'home');
        $action = $config->getValue(Gear_Key_DefaultAction, Gear_Section_Defaults, 'index');
        $params = $config->getValue(Gear_Key_DefaultParams, Gear_Section_Defaults, '');

        $this->area = $area;
        $this->controller = $controller;
        $this->action = $action;
        $this->params = $params;

        $this->mvcContext = new GearRouteMvcContext(
            $area,
            $controller,
            $action,
            $params
        );
    }

    function getMvcContext()
    {
        return $this->mvcContext;
    }

    function createUrl($context, $mvcContext, $params)
    {
        throw new GearNotSupportedException();
        //return 'Default route service not support reverse routing.';
    }

    function getConfigurator()
    {
        throw new GearNotSupportedException();
    }

    function enableCache()
    {
    }

    function setUrlProvider($provider)
    {
        throw new GearNotSupportedException();
    }
}
/*</module>*/
?>