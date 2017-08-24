<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\controller;
    /*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\IGearContext;
use gear\arch\core\IGearMvcContext;
use gear\arch\http\IGearHttpRequest;
use gear\arch\http\IGearHttpResponse;
use GearInvalidOperationException;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/

/**
 * Class GearActionExecutor Typically used for unit testing purposes.
 * @package gear\arch\controller
 */
class GearActionExecutor
{
    /** @var GearController */
    private $controller;
    /** @var IGearContext */
    private $context;
    /** @var IGearMvcContext */
    private $mvcContext;
    /** @var IGearHttpRequest */
    private $request;
    /** @var IGearHttpResponse */
    private $response;
    /** @var string */
    private $actionName;
    /** @var IGearActionResolver */
    private $actionResolver;

    private $startTime;
    private $endTime;

    private $actionResult;

    public function __construct(
        $controller,
        $context,
        $mvcContext,
        $request,
        $actionName,
        $actionResolver)
    {
        $this->controller = $controller;
        $this->context = $context;
        $this->mvcContext = $mvcContext;
        $this->request = $request;
        $this->actionName = $actionName;
        $this->actionResolver = $actionResolver;
    }

    public function boxedExecute()
    {
        $controller = $this->controller;

        $this->startTime = microtime(true);

        $this->actionResolver->invokeAction(
            $controller,
            $this->context,
            $this->mvcContext,
            $this->request,
            $this->actionName);

        $this->endTime = microtime(true);

        $this->response = $controller->getResponse();

        //$this->body = ob_end_clean();
    }

    protected function throwIfNotExecuted()
    {

    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function getMvcContext()
    {
        return $this->mvcContext;
    }

    public function getActionName()
    {
        return $this->actionName;
    }

    public function getActionResolver()
    {
        return $this->actionResolver;
    }

    public function getController()
    {
        return $this->controller;
    }




    public function getResponse()
    {
        $this->throwIfNotExecuted();

        return $this->response;
    }

    public function isBadRequest()
    {
        $this->throwIfNotExecuted();

        return $this->response->getStatusCode() == 400;
    }

    public function isServerError()
    {
        $this->throwIfNotExecuted();

        return $this->response->getStatusCode() == 500;
    }

    public function getStartTime()
    {
        $this->throwIfNotExecuted();

        return $this->startTime;
    }

    public function getEndTime()
    {
        $this->throwIfNotExecuted();

        return $this->endTime;
    }

    public function getTotalTime()
    {
        $this->throwIfNotExecuted();

        return $this->endTime - $this->startTime;
    }

    public function getActionResult()
    {
        return $this->actionResult;
    }
    public function checkActionResult($class)
    {
        $actionResult = $this->getActionResult();
        if (isset($actionResult)) {
            return get_class($actionResult) == $class;
        }
        return false;
    }
}

/*</module>*/
?>