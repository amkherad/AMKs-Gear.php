<?php
/**
 * Created by PhpStorm.
 * User: Ali Mousavi Kherad
 * Date: 7/19/2016
 * Time: 2:15 AM
 */
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\controller;
/*</namespace.current>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IActionResolver
{
    function invokeAction($controller,
                          $context,
                          $mvcContext,
                          $request,
                          $actionName);
}
/*</module>*/
?>