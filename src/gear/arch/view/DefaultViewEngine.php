<?php
//$SOURCE_LICENSE$

/*<requires>*/
//IViewEngine
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\view;
    /*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\view\IViewEngine;
use gear\arch\helpers\Path;
use gear\arch\http\IActionResult;
use gear\arch\http\results\BatchActionResult;
use gear\arch\io\HtmlStream;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/

class DefaultViewEngine implements IViewEngine
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
                if ($r instanceof IActionResult) {
                    $result[] = $r;
                }
            }
        }
        if (sizeof($result) > 0) {
            return new BatchActionResult($result);
        }

        return $execResult;
    }

    private static function _renderView(
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
        $ext = Path::GetExtension($viewPath);
        if ($ext != 'phtml' && $ext != 'php') $viewPath .= '.phtml';
        $viewPath = Path::GetUseablePath(Path::Combine($viewRoot, $controllerName, $viewPath));

        $layout = $useLayout == true ? $controller->layout : null;
        $viewContent = self::_executeView(
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
                $output = new HtmlStream();
            }
            $output->write($viewContent);
            $context->registerService(Gear_ServiceViewOutputStream, $output);

            self::_renderView(
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

    private static function _checkFileExists(&$path)
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
        if (!self::_checkFileExists($viewPath)) {
            if (!self::_checkFileExists($viewPath)) {
                $dblCheck = getcwd() . '/' . $viewPath;
                if (!self::_checkFileExists($dblCheck)) {
                    throw new ViewFileNotFoundException($path);
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