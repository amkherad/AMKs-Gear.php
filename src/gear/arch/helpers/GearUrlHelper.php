<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\helpers;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\GearExtensibleClass;
use gear\arch\core\IGearContext;
use gear\arch\core\IGearMvcContext;
use gear\arch\route\IGearRouteService;

/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearUrlHelper extends GearExtensibleClass implements IActionUrlBuilder
{
    /** @var IGearContext */
    private $context;
    /** @var IGearMvcContext */
    private $mvcContext;
    /** @var IGearRouteService */
    private $route;
    /** @var string */
    private $urlPrefix;

    /**
     * UrlHelper constructor.
     * @param IGearContext $context
     * @param IGearMvcContext $mvcContext
     * @param IGearRouteService $routeService
     */
    public function __construct($context, $mvcContext, $routeService)
    {
        parent::__construct(true);

        $this->context = $context;
        $this->mvcContext = $mvcContext;
        $this->route = $routeService;

        $config = $context->getConfig();
        $this->urlPrefix = $config->getValue(Gear_Key_URLPrefix, Gear_Section_AppEngine);
    }

    /**
     * @return IGearContext
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @return IGearMvcContext
     */
    public function getMvcContext()
    {
        return $this->mvcContext;
    }

    public function route($routeParams)
    {
        return $this->route->createUrl($this->context, $this->mvcContext, $routeParams);
    }

    public function action($actionName, $controllerName = null, $routeParams = null, $queryStrings = null)
    {
        if ($controllerName == null) {
            $controllerName = $this->mvcContext->getControllerName();
        }

        $headArray = ['action' => $actionName, 'controller' => $controllerName];
        $areaName = $this->mvcContext->getAreaName();
        if ($areaName != null) {
            $headArray['area'] = $areaName;
        }
        if (is_array($routeParams)) {
            $routeParams = array_merge($headArray, $routeParams);
        } elseif ($routeParams != null) {
            $routeParams = array_merge($headArray, array($routeParams));
        } else {
            $routeParams = $headArray;
        }

        $url =
            $this->urlPrefix .
            $this->route->createUrl($this->context, $this->mvcContext, $routeParams);

        if (is_array($queryStrings)) {
            $queries = [];
            foreach ($queryStrings as $key => $qs) {
                $queries[] = $key.'='.urlencode($qs);
            }
            if (count($queries) > 0) {
                $url .= '?' . (implode('&', $queries));
            }
        }

        return $url;
    }

    public function content($path)
    {
        $firstChar = substr($path, 0, 1);
        if ($firstChar == '/' || $firstChar == '\\') {
            $path = "{$this->urlPrefix}$path";
        } elseif($this->urlPrefix != null) {
            $path = "{$this->urlPrefix}/$path";
        }
        return $path;
    }

    public function asset($path)
    {
        return $this->content('assets/' . $path);
    }
}
/*</module>*/
?>