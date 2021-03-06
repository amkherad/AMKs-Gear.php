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
//GearAntiForgeryTokenManager
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\controller;
    /*</namespace.current>*/
/*<namespace.use>*/
use Exception;
use gear\arch\core\GearArgumentNullException;
use gear\arch\core\GearExtensibleClass;
use gear\arch\core\GearInspectableClass;
use gear\arch\core\GearInvalidOperationException;
use gear\arch\helpers\GearDynamicDictionary;
use gear\arch\helpers\GearHtmlHelper;
use gear\arch\helpers\GearUrlHelper;
use gear\arch\helpers\GearHttpHelper;
use gear\arch\core\IGearContext;
use gear\arch\core\IGearMvcContext;
use gear\arch\http\IGearActionResult;
use gear\arch\http\IGearHttpRequest;
use gear\arch\http\IGearHttpResponse;
use gear\arch\http\results\GearCsvonResult;
use gear\arch\http\results\GearFileResult;
use gear\arch\http\results\GearInternalServerErrorResult;
use gear\arch\http\results\GearRedirectResult;
use gear\arch\http\results\GearStatusCodeResult;
use gear\arch\http\results\GearViewResult;
use gear\arch\http\results\GearJsonResult;
use gear\arch\http\results\GearBadRequestResult;
use gear\arch\http\results\GearNotFoundResult;
use gear\arch\http\results\GearUnauthorizedResult;
use gear\arch\http\results\GearEmptyResult;
use gear\arch\model\IGearModel;
use gear\arch\model\IGearModelBinderEngine;
use gear\arch\route\IGearRouteService;
use gear\arch\security\GearAntiForgeryTokenManager;
use ReflectionProperty;

/*</namespace.use>*/

/*<bundles>*/
GearBundle::Arch('core/GearInspectableClass');
/*</bundles>*/

/*<module>*/
abstract class GearController extends GearExtensibleClass
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
    /** @var IGearModelBinderEngine */
    protected $binder;

    /** @var string */
    public $layout;
    /** @var GearDynamicDictionary */
    public $dataBag;
    /** @var GearHtmlHelper */
    private $html;
    /** @var GearUrlHelper */
    private $url;
    /** @var GearHttpHelper */
    public $helper;

    protected
        $beginExecuteHandlers = [],
        $checkExecutionHandlers = [],
        $endExecuteHandlers = [],
        $exceptionHandlers = [];

    /**
     * Creates a controller.
     *
     * @param $context IGearContext
     * @throws GearArgumentNullException
     */
    public function __construct($context)
    {
        if ($context == null) {
            throw new GearArgumentNullException('context');
        }
        parent::__construct(false);

        $this->context = $context;
        $route = $context->getRoute();
        $config = $context->getConfig();
        $mvcContext = $route->getMvcContext();
        $this->route = $route;
        $this->request = $context->getRequest();
        $this->response = $context->getResponse();
        $this->binder = $context->getBinder();
        $this->mvcContext = $mvcContext;

        $this->layout = $config->getValue(Gear_Key_LayoutName, Gear_Section_View, Gear_DefaultLayoutName);

        $this->dataBag = new GearDynamicDictionary(array());
        $urlHelper = new GearUrlHelper($context, $mvcContext, $route);
        $this->url = $urlHelper;
        $this->html = new GearHtmlHelper($context, $mvcContext, $urlHelper, $this);
    }

    /**
     * @return IGearContext
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @return IGearRouteService
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @return IGearHttpRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return IGearHttpResponse
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return IGearMvcContext
     */
    public function getMvcContext()
    {
        return $this->mvcContext;
    }

    /**
     * @return IGearModelBinderEngine
     */
    public function getBinder()
    {
        return $this->binder;
    }

    /**
     * @return GearUrlHelper
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return GearHtmlHelper
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @param string $key
     * @param mixed|null $defaultValue
     * @return mixed
     */
    public function getViewData($key, $defaultValue = null)
    {
        return isset($this->dataBag[$key])
            ? $this->dataBag[$key]
            : $defaultValue;
    }
    /**
     * Checks existence of view data variable.
     *
     * @param string $key
     * @return bool
     */
    public function checkViewData($key)
    {
        return isset($this->dataBag[$key]);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function setViewData($key, $value)
    {
        $this->dataBag[$key] = $value;
    }

    /**
     * @param string $key
     * @return bool Indicates remove is successful or not (item exists or not).
     */
    public function removeViewData($key)
    {
        if (isset($this->dataBag[$key])) {
            unset($this->dataBag[$key]);
            return true;
        }
        return false;
    }


    /**
     * @param IGearContext $context
     */
    public function beginExecute($context)
    {
        foreach ($this->beginExecuteHandlers as $handler) {
            $handler($context);
        }
    }

    /**
     * @param IGearContext $context
     */
    public function checkExecution($context)
    {
        $this->authorize($context);

        foreach ($this->checkExecutionHandlers as $handler) {
            $handler($context);
        }
    }

    /**
     * @param IGearContext $context
     */
    public function endExecute($context)
    {
        foreach ($this->endExecuteHandlers as $handler) {
            $handler($context);
        }
    }

    /**
     * @param IGearContext $context
     * @param Exception $exception
     * @return mixed|IGearActionResult
     */
    public function onExceptionOccurred($context, $exception)
    {
        foreach ($this->exceptionHandlers as $handler) {
            $handler($context, $exception);
        }
        return null;
    }

    /**
     * @param IGearContext $context
     */
    public function authorize($context)
    {

    }

    /**
     * @param callable $handler
     * @throws GearInvalidOperationException
     */
    public function addBeginExecuteHandler($handler)
    {
        if (!is_callable($handler)) {
            throw new GearInvalidOperationException();
        }
        $this->beginExecuteHandlers[] = $handler;
    }

    /**
     * @param callable $handler
     * @throws GearInvalidOperationException
     */
    public function addEndExecuteHandler($handler)
    {
        if (!is_callable($handler)) {
            throw new GearInvalidOperationException();
        }
        $this->endExecuteHandlers[] = $handler;
    }

    /**
     * @param callable $handler
     * @throws GearInvalidOperationException
     */
    public function addCheckExecutionHandler($handler)
    {
        if (!is_callable($handler)) {
            throw new GearInvalidOperationException();
        }
        $this->checkExecutionHandlers[] = $handler;
    }

    /**
     * @param callable $handler
     * @throws GearInvalidOperationException
     */
    public function addExceptionHandler($handler)
    {
        if (!is_callable($handler)) {
            throw new GearInvalidOperationException();
        }
        $this->exceptionHandlers[] = $handler;
    }

    /**
     * @param mixed $model
     * @return mixed
     */
    public function bind($model)
    {
        if (!isset($model)) {
            return null;
        }

        $this->binder->fillModelFromContext($model, $this->context, $this, $this->mvcContext);

        return $model;
    }

    /**
     * @param IGearContext $context
     * @param mixed $model
     * @param ReflectionProperty|null $reflectionProperty
     * @return mixed
     */
    public function observeModel($context, $model, $reflectionProperty = null)
    {
        return $model;
    }

    //public function LayoutRendering($layout)
    //{
    //}

    /**
     * @param mixed $model
     * @return bool
     */
    public function validateModel($model)
    {
        if ($model == null) {
            return false;
        }
        if (!($model instanceof IGearModel)) {
            return false;
        }
        $errors = array();
        $result = boolval($model->validate($errors));
        if ($result == null || !$result) {
            $this->dataBag[Gear_ValidationMessages] = $errors;
        }
        return $result;
    }

    public function validateAntiForgeryToken()
    {
        return GearAntiForgeryTokenManager::validateAntiForgeryToken();
    }

    /**
     * Gets a value from both route values and request parameters.
     *
     * @param string $name
     * @param mixed $defaultValue
     *
     * @return string
     */
    public function getParam($name, $defaultValue = null)
    {
        $params = $this->mvcContext->getParams();
        if (isset($params[$name])) {
            return $params[$name];
        }
        $params = $this->context->getRequest()->getAllValues();
        if (isset($params[$name])) {
            return $params[$name];
        }
        return $defaultValue;
    }


    /**
     * @param int $statusCode
     * @param mixed|null $content
     * @return GearJsonResult
     */
    public function statusCode($statusCode, $content = null)
    {
        return new GearStatusCodeResult($statusCode, $content);
    }

    /**
     * @param mixed|null $content
     * @return GearJsonResult
     */
    public function ok($content = null)
    {
        return new GearStatusCodeResult(200, $content);
    }

    /**
     * @param mixed $mixed
     * @param bool|false $allowGet
     * @return GearJsonResult
     */
    public function json($mixed, $allowGet = false)
    {
        return new GearJsonResult($mixed, $allowGet);
    }

    /**
     * @param string$name
     * @param mixed $mixed
     * @param bool|false $allowGet
     * @return GearCsvonResult
     */
    public function csvon($name, $mixed, $allowGet = false)
    {
        return new GearCsvonResult($name,$mixed, $allowGet);
    }

    /**
     * @param string|null $message
     * @return GearInternalServerErrorResult
     */
    public function serverError($message = null)
    {
        return new GearInternalServerErrorResult($message);
    }

    /**
     * @param string|null $message
     * @return GearBadRequestResult
     */
    public function badRequest($message = null)
    {
        return new GearBadRequestResult($message);
    }

    /**
     * @param string|null $message
     * @return GearNotFoundResult
     */
    public function notFound($message = null)
    {
        return new GearNotFoundResult($message);
    }

    /**
     * @param string|null $message
     * @return GearUnauthorizedResult
     */
    public function unauthorized($message = null)
    {
        return new GearUnauthorizedResult($message);
    }

    /**
     * @param string|null $message
     * @return GearEmptyResult
     */
    public function emptyResult($message = null)
    {
        return new GearEmptyResult($message);
    }

    /**
     * @param string $viewName
     * @param mixed $model
     * @return GearViewResult
     */
    public function view($viewName = null, $model = null)
    {
        return new GearViewResult($this, $viewName, $model);
    }

    /**
     * @param string $viewName
     * @return GearViewResult
     */
    public function viewName($viewName)
    {
        return new GearViewResult($this, $viewName, null);
    }

    /**
     * @param mixed $viewModel
     * @return GearViewResult
     */
    public function viewModel($viewModel)
    {
        return new GearViewResult($this, null, $viewModel);
    }

    /**
     * @param string $actionName
     * @param string|null $controllerName
     * @param string|null $routeParams
     * @return GearRedirectResult
     */
    public function redirectToAction($actionName, $controllerName = null, $routeParams = null, $queryString = null)
    {
        $url = $this->url->action($actionName, $controllerName, $routeParams, $queryString);
        return new GearRedirectResult($url, false);
    }

    /**
     * @param string $actionName
     * @param string|null $controllerName
     * @param string|null $routeParams
     * @return GearRedirectResult
     */
    public function redirectToActionPermanent($actionName, $controllerName = null, $routeParams = null)
    {
        $url = $this->url->action($actionName, $controllerName, $routeParams);
        return new GearRedirectResult($url, true);
    }

    /**
     * @param string $url
     * @return GearRedirectResult
     */
    public function redirectToUrl($url)
    {
        return new GearRedirectResult($url, false);
    }

    /**
     * @param string $url
     * @return GearRedirectResult
     */
    public function redirectToUrlPermanent($url)
    {
        return new GearRedirectResult($url, true);
    }

    /**
     * @param string $fileName
     * @return GearRedirectResult
     */
    public function file($fileName)
    {
        return new GearFileResult($fileName);
    }
}
/*</module>*/
?>