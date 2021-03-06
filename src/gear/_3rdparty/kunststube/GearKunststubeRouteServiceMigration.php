<?php
//$SOURCE_LICENSE$

/*<requires>*/
//Router
/*</requires>*/

/*<namespace.current>*/
namespace gear\_3rdparty\kunststube;
    /*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\GearConfiguration;
use gear\arch\core\IGearContext;
use gear\arch\core\IGearMvcContext;
use gear\arch\route\GearRouteMvcContext;
use gear\arch\route\IGearRouteService;
use Kunststube\Router\Route;
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
    protected $context;
    protected $mvcContextCache;
    protected $config;
    protected $urlProvider;
    protected $enableCache;
    /** @var Router */
    protected $route;

    /** @var Route */
    private $routeCache;

    /**
     * GearKunststubeRouteServiceMigration constructor.
     * @param IGearContext $context
     */
    public function __construct($context)
    {
        /** @var GearConfiguration $config */
        $config = $context->getConfig();
        $this->context = $context;
        $this->config = $config;
        $router = new Router();
        $this->route = $router;
        $router->defaultCallback([$this, '_callback']);
    }

    public function getMvcContext()
    {
        if ($this->enableCache && $this->mvcContextCache != null) {
            return $this->mvcContextCache;
        }

        $config = $this->config;

        $urlProvider = $this->urlProvider;
        if (is_callable($urlProvider)) {
            $url = $urlProvider();
        } else {
            $urlFieldName = $config->getValue(Gear_Key_RouterUrl, Gear_Section_Router, 'url');
            $url = $this->context->getRequest()->getValue($urlFieldName, '');
        }

        return $this->createMvcContext($url);
    }

    /**
     * @param string $url
     * @return IGearMvcContext
     */
    public function createMvcContext($url)
    {
        $config = $this->config;

        $route = $this->route;
        $route->route($url);
        $result = $this->routeCache;

        $area = $result->area;
        $controller = $result->controller;
        $action = $result->action;
        $params = $result->dispatchValues();

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

        $context = new GearRouteMvcContext($area, $controller, $action, $params);
        if ($this->enableCache) {
            $this->mvcContextCache = $context;
        } else {
            $this->mvcContextCache = null;
        }
        return $context;
    }

    public function createUrl($context, $mvcContext, $params)
    {
        return $this->route->reverseRoute($params);
    }

    public function _callback(Route $route)
    {
        $this->routeCache = $route;
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