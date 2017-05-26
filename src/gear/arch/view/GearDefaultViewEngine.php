<?php
//$SOURCE_LICENSE$

/*<requires>*/
//IGearViewEngine
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\view;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\controller\GearController;
use gear\arch\core\GearConfiguration;
use gear\arch\core\IGearContext;
use gear\arch\core\IGearMvcContext;
use gear\arch\helpers\GearDynamicDictionary;
use gear\arch\helpers\GearGeneralHelper;
use gear\arch\helpers\GearHtmlHelper;
use gear\arch\helpers\GearUrlHelper;
use gear\arch\view\IGearViewEngine;
use gear\arch\helpers\GearPath;
use gear\arch\http\IGearActionResult;
use gear\arch\http\results\GearBatchActionResult;
use gear\arch\io\GearHtmlStream;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearDefaultViewEngine implements IGearViewEngine
{
    /**
     * @var array Provides probing locations
     */
    protected
        $probLocations = [
        '/:rootarea/:area/views/:controller',
        '/:rootarea/:area/views/:controller/_shared',
        '/:rootarea/:area/views/:shared',
        '/:rootarea/:area/views',
        '/views/:controller',
        '/views/:controller/_shared',
        '/views/:shared',
        '/views',
    ];

    public function renderView(
        $context,
        $controller,
        $viewName,
        $model
    )
    {
        $route = $context->getRoute();
        $mvcContext = $route->getMvcContext();
        $controllerName = $mvcContext->getControllerName();

        $execResult = self::_renderView(
            $this,
            0,
            $context,
            $mvcContext,
            $controllerName,
            $controller,
            $viewName,
            $model,
            true);

        $result = array();
        if (is_array($execResult)) {
            foreach ($execResult as $r) {
                if ($r instanceof IGearActionResult) {
                    $result[] = $r;
                }
            }
        }
        if (sizeof($result) > 0) {
            return new GearBatchActionResult($result);
        }

        return $execResult;
    }

    public function renderPartialView(
        $context,
        $controller,
        $partialViewName,
        $model
    )
    {
        $route = $context->getRoute();
        $mvcContext = $route->getMvcContext();
        $controllerName = $mvcContext->getControllerName();

        $execResult = self::_renderView(
            $this,
            0,
            $context,
            $mvcContext,
            $controllerName,
            $controller,
            $partialViewName,
            $model,
            false);

        $result = array();
        if (is_array($execResult)) {
            foreach ($execResult as $r) {
                if ($r instanceof IGearActionResult) {
                    $result[] = $r;
                }
            }
        }
        if (sizeof($result) > 0) {
            return new GearBatchActionResult($result);
        }

        return $execResult;
    }

    /**
     * @param $viewEngine IGearViewEngine
     * @param $indent int
     * @param $context IGearContext
     * @param $mvcContext IGearMvcContext
     * @param $controllerName string
     * @param $controller GearController
     * @param $viewName string
     * @param $model mixed
     * @param $useLayout bool
     *
     * @return mixed
     */
    private static function _renderView(
        $viewEngine,
        $indent,
        $context,
        $mvcContext,
        $controllerName,
        $controller,
        $viewName,
        $model,
        $useLayout)
    {
        $config = $context->getConfig();
        $viewRoot = $config->getValue(Gear_Key_RootPath, Gear_Section_View, Gear_DefaultViewsRootPath);

        $viewPath = strtolower($viewName);
        $ext = GearPath::GetExtension($viewPath);
        if ($ext != 'phtml' && $ext != 'php') $viewPath .= '.phtml';
        $viewPath = GearPath::GetUseablePath(GearPath::Combine($viewRoot, $controllerName, $viewPath));

        $layout = $useLayout == true ? $controller->layout : null;
        $viewContent = self::_executeView(
            $config,
            $context,
            $mvcContext,
            $viewEngine,
            $viewPath,
            $viewName,
            $controller,
            $controller->dataBag,
            $controller->getHtml(),
            $controller->getUrl(),
            $controller->helper,
            $useLayout,
            $layout,
            $model,
            $result);
        //ActionResult::ExecuteActionResult($context, $result);

        if (isset($layout)) {
            //$controller->layout = null;

            $stream = $context->getResponse()->getInnerStream();
            $stream->write($viewContent);

//            $output = $context->getService(Gear_ServiceViewOutputStream);
//            if ($output == null) {
//                $output = new GearHtmlStream();
//            }
//            $output->write();
//            $context->registerService(Gear_ServiceViewOutputStream, $output);

            self::_renderView(
                $viewEngine,
                $indent + 1,
                $context,
                $mvcContext,
                null,
                $controller,
                $layout,
                $model,
                false);

            $stream->clear();

        } else {
            $context->getResponse()->write($viewContent);
        }
        return $result;
    }

    protected static function checkFileExists(&$path)
    {
        if (file_exists($path)) {
            if(filetype($path) != 'dir') {
                return true;
            }
        }

        if (file_exists("$path.phtml")) {
            $path = "$path.phtml";
            return true;
        }
        if (file_exists("$path.php")) {
            $path = "$path.php";
            return true;
        }

        return false;
    }

    /**
     * @param $config GearConfiguration
     * @param $context IGearContext
     * @param $mvcContext IGearMvcContext
     * @param $viewEngine IGearViewEngine
     * @param $rootPath string
     * @param $viewName string
     *
     * @return null|string
     *
     * @throws GearViewFileNotFoundException
     */
    protected static function probView(
        $config,
        $context,
        $mvcContext,
        $viewEngine,
        $rootPath,
        $viewName)
    {
        $areaRoot = $config->getValue(Gear_Key_AreaRoot, Gear_Section_View, Gear_DefaultAreasRootPath);
        $area = $mvcContext->getAreaName();
        $controller = $mvcContext->getControllerName();
        $action = $mvcContext->getActionName();
        $shared = $config->getValue(Gear_Key_SharedView, Gear_Section_View, Gear_DefaultSharedRootPath);

        $found = false;
        $viewPath = null;
        $searchedLocs = array();
        foreach ($viewEngine->probLocations as $location) {
            if(!$area) {
                if(stripos($location, ':area')) {
                    continue;
                }
            }
            $location = str_replace(':area', $area, $location);
            $location = str_replace(':rootarea', $areaRoot, $location);
            $location = str_replace(':controller', $controller, $location);
            $location = str_replace(':action', $action, $location);
            $location = str_replace(':shared', $shared, $location);

            $viewPath = "$location/$viewName";
            if (!self::checkFileExists($viewPath)) {
                $dblCheck = getcwd() . '/' . $viewPath;
                if (self::checkFileExists($dblCheck)) {
                    $found = true;
                    $viewPath = $dblCheck;
                    break;
                }
            } else {
                $found = true;
                break;
            }
            $searchedLocs[] = $viewPath;
        }
        if(!$found) {
            throw new GearViewFileNotFoundException($rootPath, " Searched locations were:<br>" . implode('<br>', $searchedLocs));
        }

        return $viewPath;
    }

    /**
     * @param $config GearConfiguration
     * @param $context IGearContext
     * @param $mvcContext IGearMvcContext
     * @param $viewEngine IGearViewEngine
     * @param $path string
     * @param $viewName string
     * @param GearController $controller
     * @param $dataBag GearDynamicDictionary
     * @param $html GearHtmlHelper
     * @param $url GearUrlHelper
     * @param $helper GearGeneralHelper
     * @param $layout string
     * @param $model mixed
     * @param $result mixed
     *
     * @return string
     *
     * @throws GearViewFileNotFoundException
     */
    private static function _executeView(
        $config,
        $context,
        $mvcContext,
        $viewEngine,
        $path,
        $viewName,
        $controller,
        $dataBag,
        $html,
        $url,
        $helper,
        $useLayout,
        &$layout,
        &$model,
        &$result)
    {
        $viewPath = self::probView($config, $context, $mvcContext, $viewEngine, $path, $viewName);

        global $Layout, $DataBag, $Model, $Html, $Url, $Helper, $Controller;
        if ($model != null) {
            $Model = $model;
        }
        $layoutBackup = '';
        if ($useLayout) {
            $Layout = $layout;
        } else {
            $layoutBackup = $Layout;
        }
        $DataBag = $dataBag;
        $Html = $html;
        $Url = $url;
        $Helper = $helper;
        $Controller = $controller;

        $level = ob_get_level();
        ob_start();
        $result = require($viewPath);
        $buffer = '';
        while (ob_get_level() > $level) {
            $buffer = ob_get_clean() . $buffer;
        }
        //global $Layout;
        if ($useLayout) {
            $layout = $Layout;
        } else {
            $Layout = $layoutBackup;
        }
        return $buffer;
    }
}

/*</module>*/
?>