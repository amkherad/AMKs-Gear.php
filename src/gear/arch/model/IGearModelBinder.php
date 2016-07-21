<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\model;
    /*</namespace.current>*/

    /*<bundles>*/
    /*</bundles>*/

/*<module>*/
interface IGearModelBinder
{
    function getModelFromContext($modelDescriptor, $context, $controller, $mvcContext);
    function fillModelFromContext($instance, $context, $controller, $mvcContext);
}

/*</module>*/
?>