<?php
//$SOURCE_LICENSE$

/*<requires>*/
//IGearActionResolver
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\controller;
    /*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\helpers\GearHelpers;
use gear\arch\http\IGearActionResult;
use gear\arch\http\IGearInnerActionResult;
use gear\arch\core\GearInvalidOperationException;
use gear\arch\core\GearSerializer;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/

class GearDefaultActionResolver implements IGearActionResolver
{
    public function invokeAction(
        $controller,
        $context,
        $mvcContext,
        $request,
        $actionName)
    {
        $config = $context->getConfig();
        $method = $request->getMethod();

        $preferedAction = $config->getValue(Gear_Key_PreferredActionPattern, Gear_Section_Controller, Gear_DefaultPreferredActionPattern);
        $preferedAction = str_replace(Gear_PlaceHolder_Action, $actionName, $preferedAction);
        $preferedAction = str_replace(Gear_PlaceHolder_HttpMethod, $method, $preferedAction);

        if (method_exists($controller, $preferedAction)) {
            $actionName = $preferedAction;
        }

        $suppliedArgumentss = array();

        $controllerReflection = new \ReflectionClass($controller);
        try {
            $actionReflection = $controllerReflection->getMethod($actionName);
        } catch(\Exception $ex) {
            throw new \GearHttpNotFoundException("Action '$actionName' not found.");
        }
        $actionParameters = $actionReflection->getParameters();

        $controller->beginExecute($context);

        $controller->checkExecution($context);

        try {
            $result = self::_execAction(
                $context,
                $mvcContext,
                $controller,
                $controllerReflection,
                $actionReflection,
                $actionName,
                $suppliedArgumentss,
                $actionParameters);

            self::_executeActionResult(
                $context,
                $request,
                $context->getResponse(),
                $result);

        } catch (\Exception $ex) {
            $controller->onExceptionOccurred($context, $ex);
            throw $ex;
        }

        $controller->endExecute($context);
    }

    public static function _execAction(
        $context,
        $mvcContext,
        $controller,
        $controllerReflection,
        $actionReflection,
        $actionName,
        $args,
        $actionParameters)
    {
        $binder = $context->getBinder();
        if (sizeof($actionParameters) == 0) {
            if (!isset($args))
                $result = $controller->$actionName();
            else
                $result = call_user_func_array([$controller, $actionName], $args);
        } else {
            if (!isset($args)) $args = $context->getAllValues();
            $actionArgs = array();
            foreach ($actionParameters as $p) {
                $value = null;
                if (GearHelpers::TryGetArrayElementByNameCaseInSensetive($args, $p->getName(), $value))
                    $actionArgs[] = $value;
                else {
                    try {
                        $class = $p->getClass();
                    } catch (\Exception$ex) {
                        $class = null;
                    }
                    if (isset($class)) {
                        $actionArgs[] = $binder->getModelFromContext(
                            $class,
                            $context,
                            $controller,
                            $mvcContext
                        );
                    } else {
                        throw new GearInvalidOperationException("Action '$actionName' argument uses an undefined class type.");
                    }
                }
            }
            $actionArgs = array_merge($actionArgs, $args);
            $result = call_user_func_array([$controller, $actionName], $actionArgs);
        }
        return $result;
    }

    private static function _executeActionResult($context, $request, $response, $result)
    {
        if (!isset($result)) return;
        do {
            if ($result instanceof IGearActionResult) {
                $inner = $result->getInnerResult();
                $result = $result->executeResult($context, $request, $response);
            } else {
                $inner = null;
                if(is_object($result)) {
                    $response->contentType('application/json');
                    $response->write(GearSerializer::json($result));
                } else {
                    $response->write($result);
                }
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