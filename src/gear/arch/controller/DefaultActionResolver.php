<?php
/**
 * Created by PhpStorm.
 * User: Ali Mousavi Kherad
 * Date: 7/19/2016
 * Time: 2:16 AM
 */
//$SOURCE_LICENSE$

/*<requires>*/
//IActionResolver
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\controller;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\helpers\Helpers;
use gear\arch\http\IActionResult;
use gear\arch\http\IInnerActionResult;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class DefaultActionResolver implements IActionResolver
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

        $preferedAction = $config->getValue(Gear_IniKey_PreferredActionPattern, Gear_IniSection_Controller, Gear_DefaultPreferredActionPattern);
        $preferedAction = str_replace(Gear_IniPlaceHolder_Action, $actionName, $preferedAction);
        $preferedAction = str_replace(Gear_IniPlaceHolder_HttpMethod, $method, $preferedAction);

        if (method_exists($controller, $preferedAction)) {
            $actionName = $preferedAction;
        }

        $suppliedArgumentss = array();

        $controllerReflection = new ReflectionClass($controller);
        $actionReflection = $controllerReflection->getMethod($actionName);
        $actionParameters = $actionReflection->getParameters();

        $controller->beginExecute();

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
                if (Helpers::TryGetArrayElementByNameCaseInSensetive($args, $p->getName(), $value))
                    $actionArgs[] = $value;
                else {
                    try {
                        $class = $p->getClass();
                    } catch (Exception$ex) {
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
                        throw new MvcInvalidOperationException("Action '$actionName' argument uses an undefined class type.");
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
        while ($result instanceof IActionResult) {
            $inner = $result->getInnerResult();
            $result = $result->executeResult($context, $request, $response);
            if ($inner instanceof IActionResult) {
                if (!($inner instanceof IInnerActionResult)) {
                    throw new InvalidOperationException('InnerResult must be an instance of IInnerActionResult.');
                }
                self::_executeActionResult($context, $inner);
            }
        }
    }
}
/*</module>*/
?>