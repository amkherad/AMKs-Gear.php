<?php
//$SOURCE_LICENSE$

/*<requires>*/
//IGearViewEngine
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\view;
    /*</namespace.current>*/
/*<namespace.use>*/
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
            $viewEngine,
            $viewPath,
            $viewName,
            $controller->viewData,
            $controller->html,
            $controller->url,
            $controller->helper,
            $layout,
            $model,
            $result);
        //ActionResult::ExecuteActionResult($context, $result);

        if (isset($layout)) {
            $controller->layout = null;

            $output = $context->getService(Gear_ServiceViewOutputStream);
            if($output == null) {
                $output = new GearHtmlStream();
            }
            $output->write($viewContent);
            $context->registerService(Gear_ServiceViewOutputStream, $output);

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

            $output->clear();

        } else {
            $context->getResponse()->write($viewContent);
        }
        return $result;
    }

    protected function CheckFileExists(&$path)
    {
        if (file_exists($path)) {
            return true;
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

    private static function _executeView(
        $viewEngine,
        $path,
        $viewName,
        $viewData,
        $html,
        $url,
        $helper,
        &$layout,
        &$model,
        &$result)
    {
        $viewPath = dirname($path) . '/' . $viewName;
        if (!$viewEngine->CheckFileExists($viewPath)) {
            if (!$viewEngine->CheckFileExists($viewPath)) {
                $dblCheck = getcwd() . '/' . $viewPath;
                if (!$viewEngine->CheckFileExists($dblCheck)) {
                    throw new GearViewFileNotFoundException($path);
                }
                //$path = $dblCheck;
            }
        }

        global $Layout, $ViewData, $Model, $Html, $Url, $Helper;
        $Model = $model;
        $Layout = $layout;
        $ViewData = $viewData;
        $Html = $html;
        $Url = $url;
        $Helper = $helper;

        $level = ob_get_level();
        ob_start();
        $result = require($viewPath);
        $buffer = '';
        while (ob_get_level() > $level)
            $buffer = ob_get_clean() . $buffer;
        //global $Layout;
        $layout = $Layout;
        return $buffer;
    }
}
/*</module>*/
?>