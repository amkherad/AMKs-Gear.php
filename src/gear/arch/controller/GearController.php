<?php
//$SOURCE_LICENSE$

/*<requires>*/
//GearInspectableClass
//GearDynamicDictionary
//GearViewResult
//GearJsonResult
//GearStatusCodeResult
//GearBadRequestResult
//GearNotFoundResult
//GearUnauthorizedResult
//GearEmptyResult
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\controller;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\GearInspectableClass;
use gear\arch\helpers\GearDynamicDictionary;
use gear\arch\http\results\GearViewResult;
use gear\arch\http\results\GearJsonResult;
use gear\arch\http\results\GearStatusCodeResult;
use gear\arch\http\results\GearBadRequestResult;
use gear\arch\http\results\GearNotFoundResult;
use gear\arch\http\results\GearUnauthorizedResult;
use gear\arch\http\results\GearEmptyResult;
/*</namespace.use>*/

/*<bundles>*/
GearBundle::Arch('core/GearInspectableClass');
/*</bundles>*/

/*<module>*/

abstract class GearController// extends InspectableClass
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

        $this->viewData = new GearDynamicDictionary(array());
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

    public function beginExecute($context)
    {
    }

    public function checkExecution($context)
    {
        $this->authorize($context);
    }

    public function endExecute($context)
    {
    }

    public function onExceptionOccurred($context, $exception)
    {
    }

    public function authorize($context)
    {

    }

    public function bind($model)
    {
        if (!isset($model)) {
            return null;
        }

        $this->binder->fillModelFromContext($model, $this->context, $this, $this->mvcContext);

        return $model;
    }

    //public function LayoutRendering($layout)
    //{
    //}


    public function json($mixed, $allowGet = false)
    {
        return new GearJsonResult($mixed, $allowGet);
    }
    public function badRequest($message = null)
    {
        return new GearBadRequestResult($message);
    }
    public function notFound($message = null)
    {
        return new GearNotFoundResult($message);
    }
    public function unauthorized($message = null)
    {
        return new GearUnauthorizedResult($message);
    }
    public function emptyResult($message = null)
    {
        return new GearEmptyResult($message);
    }

    public function view($viewName = null, $model = null)
    {
        return new GearViewResult($this, $viewName, $model);
    }
    public function viewName($viewName)
    {
        return new GearViewResult($this, $viewName, null);
    }
    public function viewModel($viewModel)
    {
        return new GearViewResult($this, null, $viewModel);
    }
}
/*</module>*/
?>