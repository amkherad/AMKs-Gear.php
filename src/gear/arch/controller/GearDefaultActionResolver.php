<?php
//$SOURCE_LICENSE$

/*<requires>*/
//IGearActionResolver
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\controller;
    /*</namespace.current>*/
/*<namespace.use>*/
use Exception;
use gear\arch\core\IGearContext;
use gear\arch\core\IGearMvcContext;
use gear\arch\helpers\GearCollectionHelpers;
use gear\arch\helpers\GearHelpers;
use gear\arch\http\IGearActionResult;
use gear\arch\http\IGearHttpRequest;
use gear\arch\http\IGearHttpResponse;
use gear\arch\http\IGearInnerActionResult;
use gear\arch\core\GearInvalidOperationException;
use Throwable;

/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/

class GearDefaultActionResolver implements IGearActionResolver
{
    private function sanitizeActionNameWithPattern(
        $actionName,
        $actionPattern,
        $method,
        $isAjax)
    {

        $actionPattern = str_replace(Gear_PlaceHolder_Action, $actionName, $actionPattern);
        $actionPattern = str_replace(Gear_PlaceHolder_HttpMethod, $method, $actionPattern);
        if ($isAjax) {
            $actionPattern = str_replace(Gear_PlaceHolder_IsAjax, 'AJAX', $actionPattern);
        } else {
            $actionPattern = str_replace(Gear_PlaceHolder_IsAjax, '', $actionPattern);
        }

        return $actionPattern;
    }

    public function invokeAction(
        $controller,
        $context,
        $mvcContext,
        $request,
        $actionName)
    {
        $config = $context->getConfig();
        $method = strtoupper($request->getMethod());
        $isAjax = $request->isAjaxRequest();

        $origActionName = $actionName;

        $requestAttributesLookupEnabled = $config->getValue(Gear_Key_ActionLookupRequestAttributes, Gear_Section_Controller, false);
        $lookupFallback = true;

        $preferredAction = null;
        if ($requestAttributesLookupEnabled) {
            $separator = $config->getValue(Gear_Key_ActionPatternSeparator, Gear_Section_Controller, Gear_DefaultActionPatternSeparator);

            $scheme = strtoupper($request->getProtocol());
            $contentType = explode('/', $request->getContentType());
            $contentType = strtoupper(preg_replace("/[^a-zA-Z0-9]+/", '', array_pop($contentType)));

            $ajax = $isAjax ? 'AJAX' : 'NOAJAX';

            $requestAttributes = [
                [null, $method],
                [null, $ajax],
                [null, $scheme],
                [null, $contentType]
            ];

            $permutations = array_reverse(
                GearCollectionHelpers::crossJoinStrings($requestAttributes, $separator, true)
            );

            foreach ($permutations as $permutation) {
                $permutation = $actionName . $separator . $permutation;
                if (method_exists($controller, $permutation)) {
                    $lookupFallback = false;
                    $actionName = $permutation;
                    break;
                }
            }
            $preferredAction = '%RequestAttributesLookupEngine%';
        }
        if ($lookupFallback) {
            if (!$requestAttributesLookupEnabled) {
                $preferredAction = $config->getValue(Gear_Key_PreferredActionPattern, Gear_Section_Controller, Gear_DefaultPreferredActionPattern);
                $preferredAction = $this->sanitizeActionNameWithPattern($actionName, $preferredAction, $method, $isAjax);
                if (method_exists($controller, $preferredAction)) {
                    $actionName = $preferredAction;
                }
            }
            $actionPattern = $config->getValue(Gear_Key_ActionPattern, Gear_Section_Controller, Gear_DefaultActionPattern);
            $actionName = $this->sanitizeActionNameWithPattern($actionName, $actionPattern, $method, $isAjax);
        }

        $context->setValue('ActionName', $actionName);

        $controllerReflection = new \ReflectionClass($controller);
        try {
            $actionReflection = $controllerReflection->getMethod($actionName);
        } catch (Throwable $ex) {
            throw new GearActionNotFoundException($origActionName, [$actionName, $preferredAction]);
        }
        $actionParameters = $actionReflection->getParameters();

        $controller->beginExecute($context);

        $controller->checkExecution($context);
        $response = $context->getResponse();

        try {
            $result = self::_execAction(
                $context,
                $mvcContext,
                $controller,
                $controllerReflection,
                $actionReflection,
                $request,
                $response,
                $actionName,
                null,//$suppliedArgumentss,
                $actionParameters);

            self::_executeActionResult(
                $context,
                $request,
                $response,
                $result);

        } catch (Throwable $ex) {
            try {

                $result = $controller->onExceptionOccurred($context, $ex);
                if ($result instanceof IGearActionResult) {
                    self::_executeActionResult(
                        $context,
                        $request,
                        $response,
                        $result);

                    $ex = null;
                }

            } catch (Throwable $t) {
                $controller->onExceptionOccurred($context, $ex);
                throw $t;
            }
            if ($ex != null) {
                throw $ex;
            }
        }

        $controller->endExecute($context);

        return $result;
    }

    /**
     * @param IGearContext $context
     * @param IGearMvcContext $mvcContext
     * @param GearController $controller
     * @param \ReflectionClass $controllerReflection
     * @param \ReflectionMethod $actionReflection
     * @param IGearHttpRequest $request
     * @param IGearHttpResponse $response
     * @param string $actionName
     * @param mixed $args
     * @param mixed $actionParameters
     *
     * @return mixed
     *
     * @throws GearActionNotFoundException
     * @throws GearInvalidOperationException
     */
    public static function _execAction(
        $context,
        $mvcContext,
        $controller,
        $controllerReflection,
        $actionReflection,
        $request,
        $response,
        $actionName,
        $args,
        $actionParameters)
    {
        $binder = $context->getBinder();
        if (sizeof($actionParameters) == 0) {
            if (!isset($args)) {
                $result = $controller->$actionName();
            } else {
                $result = call_user_func_array([$controller, $actionName], $args);
            }
        } else {
            if (!isset($args)) {
                $args = array_merge($mvcContext->getParams(), $context->getRequest()->getAllValues());
            }
            //if (!isset($args)) $args = $context->getRequest()->getAllValues();
            $actionArgs = array();
            foreach ($actionParameters as $p) {
                /** @var $p \ReflectionParameter */
                $value = null;
                if (GearHelpers::tryGetArrayElementByNameCaseInSensetive($args, $p->getName(), $value)) {
                    $actionArgs[] = $value;
                } else {
                    try {
                        $class = $p->getClass();
                    } catch (Exception $ex) {
                        $class = null;
                    }
                    if (isset($class)) {
                        $objModel = $binder->getModelFromContext(
                            $class,
                            $context,
                            $controller,
                            $mvcContext
                        );
                        $controller->observeModel($context, $objModel, $p);
                        $actionArgs[] = $objModel;
                    } elseif ($p->isArray()) {
                        throw new GearInvalidOperationException("Action '$actionName' argument uses an undefined class type.");
                    } else {
                        $name = $p->getName();
                        if (isset($args[$name])) {
                            $actionArgs[] = $args[$name];
                        } elseif ($p->isDefaultValueAvailable()) {
                            $actionArgs[] = $p->getDefaultValue();
                        }
                    }
                }
            }
            if (sizeof($actionParameters) != sizeof($actionArgs)) {
                throw new GearActionNotFoundException($actionName);
            }

            //$actionArgs = array_merge($actionArgs, $args);
            $result = call_user_func_array([$controller, $actionName], $actionArgs);
        }
        return $result;
    }

    /**
     * @param IGearContext $context
     * @param IGearHttpRequest $request
     * @param IGearHttpResponse $response
     * @param IGearActionResult $result
     * @throws GearInvalidOperationException
     */
    private static function _executeActionResult($context, $request, $response, $result)
    {
        if (!isset($result)) return;
        do {
            if ($result instanceof IGearActionResult) {
                $inner = $result->getInnerResult();
                $result = $result->executeResult($context, $request, $response);
            } else {
                $inner = null;
                //if(is_object($result)) {
                //    $response->setContentType('application/json');
                //    $response->write(GearSerializer::json($result));
                //} else {
                $response->write($result);
                //}
            }
            if ($inner instanceof IGearActionResult) {
                if (!($inner instanceof IGearInnerActionResult)) {
                    throw new GearInvalidOperationException('InnerResult must be an instance of IInnerActionResult.');
                }
                self::_executeActionResult($context, $request, $response, $inner);
            }
        } while ($result instanceof IGearActionResult);
    }
}

/*</module>*/
?>