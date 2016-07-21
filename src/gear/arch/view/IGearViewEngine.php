<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\view;
    /*</namespace.current>*/

    /*<bundles>*/
    /*</bundles>*/

/*<module>*/
interface IGearViewEngine
{
    function renderView(
        $context,
        $controller,
        $viewName,
        $model
    );
}
/*</module>*/
?>