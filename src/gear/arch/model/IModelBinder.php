<?php
/**
 * Created by PhpStorm.
 * User: Ali Mousavi Kherad
 * Date: 7/19/2016
 * Time: 1:42 AM
 */
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\model;
    /*</namespace.current>*/

    /*<bundles>*/
    /*</bundles>*/

/*<module>*/
interface IModelBinder
{
    function getModelFromContext($modelDescriptor, $context, $controller, $mvcContext);
    function fillModelFromContext($instance, $context, $controller, $mvcContext);
}

/*</module>*/
?>