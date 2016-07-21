<?php
//$SOURCE_LICENSE$

/*<requires>*/
//Router
/*</requires>*/

/*<namespace.current>*/
namespace gear\_3rdparty\kunststube;
    /*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\route\GearRouteMvcContext;
use gear\arch\route\IGearRouteService;
use Kunststube\Router\Router;

/*</namespace.use>*/

/*<includes>*/
require_once __DIR__ . DIRECTORY_SEPARATOR . 'router/Router.php';
/*</includes>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/

class GearKunststubeRouteServiceMigration implements IGearRouteService
{
    protected
        $context,
        $mvcContextCache,
        $config,
        $route,
        $urlProvider,
        $enableCache;

    public function __construct($context)
    {
        $config = $context->getConfig();
        $this->context = $context;
        $this->config = $config;
        $router = new Router();
        $this->route = $router;
        //$router->defaultCallback([$this, '_callback']);
    }

    public function getMvcContext()
    {
        if ($this->enableCache && $this->mvcContextCache != null) {
            return $this->mvcContextCache;
        }

        $area = '';
        $controller = '';
        $action = '';
        $params = '';

        $config = $this->config;
        if (!isset($area) || $area == '') {
            $area = $config->getValue(Gear_Key_DefaultArea, Gear_Section_Defaults, '');
        }
        if (!isset($controller) || $controller == '') {
            $controller = $config->getValue(Gear_Key_DefaultController, Gear_Section_Defaults, 'home');
        }
        if (!isset($action) || $action == '') {
            $action = $config->getValue(Gear_Key_DefaultAction, Gear_Section_Defaults, 'index');
        }
        if (!isset($params) || $params == '') {
            $params = $config->getValue(Gear_Key_DefaultParams, Gear_Section_Defaults, '');
        }

        $urlProvider = $this->urlProvider;
        if (is_callable($urlProvider)) {
            $url = $urlProvider();
        } else {
            $urlFieldName = $config->getValue(Gear_Key_RouterUrl, Gear_Section_Router, 'url');
            if (isset($_GET[$urlFieldName])) {
                $url = $_GET[$urlFieldName];
            } else {
                $url = '';
            }
        }

        $route = $this->route;
        echo $url . '<br>';
        $result = $route->route($url);
        print_r($result);

        $context = new GearRouteMvcContext($area, $controller, $action, $params);
        if ($this->enableCache) {
            $this->mvcContextCache = $context;
        }
        return $context;
    }

    public function createUrl($context, $mvcContext, $params)
    {

    }

    private function _callback(Route $route)
    {
        //require_once 'MyDispatcher.php';
        //$dispatcher = new Dispatcher;
        //$dispatcher->dispatch($route);
        echo 'Router::_callback';
    }

    function getConfigurator()
    {
        return $this->route;
    }

    function enableCache()
    {
        $this->enableCache = true;
    }

    function setUrlProvider($provider)
    {
        $this->urlProvider = $provider;
    }
}

/*</module>*/
?>