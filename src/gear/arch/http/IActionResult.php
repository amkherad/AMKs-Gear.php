<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
Bundle::Arch('core/IContext');
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