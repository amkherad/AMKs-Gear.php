<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\view;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\controller\GearController;
use gear\arch\core\IGearContext;
use gear\arch\http\IGearActionResult;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IGearViewEngine
{
    /**
     * @param $context IGearContext
     * @param $controller GearController
     * @param $viewName string
     * @param $model mixed
     *
     * @return IGearActionResult
     */
    function renderView(
        $context,
        $controller,
        $viewName,
        $model
    );

    /**
     * @param IGearContext $context
     * @param GearController $controller
     * @param string $partialViewName
     * @param mixed $model
     *
     * @return IGearActionResult
     */
    function renderPartialView(
        $context,
        $controller,
        $partialViewName,
        $model
    );
}
/*</module>*/
?>