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
use gear\arch\core\GearConfiguration;
use gear\arch\core\GearInspectableClass;
use gear\arch\core\GearInvalidOperationException;
use gear\arch\helpers\GearDynamicDictionary;
use gear\arch\helpers\GearHtmlHelper;
use gear\arch\helpers\GearUrlHelper;
use gear\arch\helpers\GearGeneralHelper;
use gear\arch\core\IGearContext;
use gear\arch\core\IGearMvcContext;
use gear\arch\http\IGearHttpRequest;
use gear\arch\http\IGearHttpResponse;
use gear\arch\http\results\GearViewResult;
use gear\arch\http\results\GearJsonResult;
use gear\arch\http\results\GearStatusCodeResult;
use gear\arch\http\results\GearBadRequestResult;
use gear\arch\http\results\GearNotFoundResult;
use gear\arch\http\results\GearUnauthorizedResult;
use gear\arch\http\results\GearEmptyResult;
use gear\arch\model\GearModel;
use gear\arch\model\IGearModelBinder;
use gear\arch\route\IGearRouteService;
/*</namespace.use>*/

/*<bundles>*/
GearBundle::Arch('core/GearInspectableClass');
/*</bundles>*/

/*<module>*/

abstract class GearController// extends InspectableClass
{
    /** @var IGearContext */
    protected $context;
    /** @var IGearMvcContext */
    protected $mvcContext;
    /** @var IGearRouteService */
    protected $route;
    /** @var IGearHttpRequest */
    protected $request;
    /** @var IGearHttpResponse */
    protected $response;
    /** @var IGearModelBinder */
    protected $binder;

    /** @var string */
    public $layout;
    /** @var GearDynamicDictionary */
    public $dataBag;
    /** @var GearHtmlHelper */
    public $html;
    /** @var GearUrlHelper */
    public $url;
    /** @var GearGeneralHelper */
    public $helper;

    private
        $beginExecuteHandlers = [],
        $checkExecutionHandlers = [],
        $endExecuteHandlers = [],
        $exceptionHandlers = []
    ;

    /**
     * Creates a controller.
     *
     * @param $context IGearContext
     */
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

        $this->dataBag = new GearDynamicDictionary(array());
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
        foreach($this->beginExecuteHandlers as $handler) {
            $handler($context);
        }
    }

    public function checkExecution($context)
    {
        $this->authorize($context);

        foreach($this->checkExecutionHandlers as $handler) {
            $handler($context);
        }
    }

    public function endExecute($context)
    {
        foreach($this->endExecuteHandlers as $handler) {
            $handler($context);
        }
    }

    public function onExceptionOccurred($context, $exception)
    {
        foreach($this->exceptionHandlers as $handler) {
            $handler($context, $exception);
        }
    }

    public function authorize($context)
    {

    }

    public function addBeginExecuteHandler($handler) {
        if (!is_callable($handler)) {
            throw new GearInvalidOperationException();
        }
        $this->beginExecuteHandlers[] = $handler;
    }
    public function addEndExecuteHandler($handler) {
        if (!is_callable($handler)) {
            throw new GearInvalidOperationException();
        }
        $this->endExecuteHandlers[] = $handler;
    }
    public function addCheckExecutionHandler($handler) {
        if (!is_callable($handler)) {
            throw new GearInvalidOperationException();
        }
        $this->checkExecutionHandlers[] = $handler;
    }
    public function addExceptionHandler($handler) {
        if (!is_callable($handler)) {
            throw new GearInvalidOperationException();
        }
        $this->exceptionHandlers[] = $handler;
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

    public function validateModel($model)
    {
        if ($model == null) {
            return false;
        }
        if (!($model instanceof GearModel)) {
            return false;
        }
        $errors = array();
        $result = boolval($model->validate($errors));
        if ($result == null || !$result) {
            $this->dataBag[Gear_ValidationMessages] = $errors;
        }
        return $result;
    }


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