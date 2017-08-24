<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\model;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\controller\GearController;
use gear\arch\core\IGearContext;
use gear\arch\core\IGearMvcContext;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IGearModelBinderEngine
{
    /**
     * @param \ReflectionClass $modelDescriptor
     * @param IGearContext $context
     * @param GearController $controller
     * @param IGearMvcContext $mvcContext
     * @return mixed
     */
    function getModelFromContext($modelDescriptor, $context, $controller, $mvcContext);

    /**
     * @param object $instance
     * @param IGearContext $context
     * @param GearController $controller
     * @param IGearMvcContext $mvcContext
     * @return mixed
     */
    function fillModelFromContext($instance, $context, $controller, $mvcContext);
}
/*</module>*/
?>