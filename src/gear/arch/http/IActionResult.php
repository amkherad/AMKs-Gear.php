<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\http;
/*</namespace.current>*/

/*<namespace.current>*/
/*</namespace.current>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IActionResult
{
    function executeResult($context, $request, $response);
    function getInnerResult();
}
/*</module>*/
?>