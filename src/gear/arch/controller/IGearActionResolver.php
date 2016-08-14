<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\controller;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\controller\GearController;
use gear\arch\core\IGearContext;
use gear\arch\core\IGearMvcContext;
use gear\arch\http\IGearHttpRequest;

/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IGearActionResolver
{
    /**
     * @param $controller GearController
     * @param $context IGearContext
     * @param $mvcContext IGearMvcContext
     * @param $request IGearHttpRequest
     * @param $actionName string
     *
     * @return bool Always returns true.
     */
    function invokeAction($controller,
                          $context,
                          $mvcContext,
                          $request,
                          $actionName);
}
/*</module>*/
?>