<?php
/**
 * Created by PhpStorm.
 * User: Ali Mousavi Kherad
 * Date: 7/19/2016
 * Time: 10:10 PM
 */
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\view;
    /*</namespace.current>*/

    /*<bundles>*/
    /*</bundles>*/

/*<module>*/
interface IViewEngine
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