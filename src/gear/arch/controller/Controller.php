<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\controller;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\InspectableClass;
use gear\arch\helpers\DynamicDictionary;
use gear\arch\http\results\JsonResult;
/*</namespace.use>*/

/*<bundles>*/
Bundle::Arch('core/InspectableClass');
/*</bundles>*/

/*<module>*/

abstract class Controller// extends InspectableClass
{
    protected
        $context,
        $mvcContext,
        $route,
        $request,
        $response,
        $binder;

    public
        $layout,
        $viewData,
        $html,
        $url,
        $helper
    ;

    public function __construct($context)
    {
        $this->context = $context;
        $route = $context->getRoute();
        $config = $context->getConfig();
        $this->route = $route;
        $this->request = $context->getRequest();
        $this->response = $context->getResponse();
        $this->binder = $context->getBinder();
        $this->mvcContext = $route->getMvcContext();

        $this->layout = $config->getValue(Gear_Key_LayoutName, Gear_Section_View, Gear_DefaultLayoutName);

        $this->viewData = new DynamicDictionary(array());
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getMvcContext()
    {
        return $this->mvcContext;
    }

    public function getBinder()
    {
        return $this->binder;
    }

    public function beginExecute()
    {
    }

    public function checkExecution()
    {
        $this->authorize();
    }

    public function endExecute()
    {
    }

    public function onExceptionOccurred($exception)
    {
        echo 'sexy??';
    }

    public function authorize()
    {

    }

    public function Bind($model)
    {
        if (!isset($model)) {
            return null;
        }

        $this->binder->fillModelFromContext($model, $this->context, $this, $this->mvcContext);

        return $model;
    }

    public function LayoutRendering($layout)
    {
    }


    public function Json($mixed, $allowGet = false)
    {
        return new JsonResult($mixed, $allowGet);
    }

    public function View($model = null, $viewName = null)
    {
        return new ViewResult($this, $viewName, $model);
    }
}
/*</module>*/
?>