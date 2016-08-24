<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\model;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\core\IGearContext;
use gear\arch\core\IGearMvcContext;
use gear\arch\http\IGearHttpRequest;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IGearModelBinder
{
    /**
     * @param IGearContext $context
     * @param IGearMvcContext $mvcContext
     * @param IGearHttpRequest $request
     * @param GearModelBindingContext $bindingContext
     * @return mixed
     */
    function bind($context, $mvcContext, $request, $bindingContext);
}
/*</module>*/
?>