<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\route;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\GearConfiguration;
use gear\arch\core\GearNotSupportedException;
use gear\arch\core\IGearContext;
use gear\arch\core\IGearMvcContext;
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

    /**
     * GearDefaultRouteService constructor.
     *
     * @param IGearContext $context
     */
    public function __construct($context)
    {
        /** @var GearConfiguration $config */
        $config = $context->getConfig();
        $this->context = $context;
        $this->config = $config;

        $this->mvcContext = $this->createMvcContext('');
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

    /**
     * @param string $url
     * @return IGearMvcContext
     */
    public function createMvcContext($url)
    {
        $area = $this->config->getValue(Gear_Key_DefaultArea, Gear_Section_Defaults, '');
        $controller = $this->config->getValue(Gear_Key_DefaultController, Gear_Section_Defaults, 'home');
        $action = $this->config->getValue(Gear_Key_DefaultAction, Gear_Section_Defaults, 'index');
        $params = $this->config->getValue(Gear_Key_DefaultParams, Gear_Section_Defaults, '');

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
}
/*</module>*/
?>