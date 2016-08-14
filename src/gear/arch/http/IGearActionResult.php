<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\http;
/*</namespace.current>*/

/*<namespace.use>*/
use gear\arch\core\IGearContext;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IGearActionResult
{
    /**
     * @param $context IGearContext
     * @param $request IGearHttpRequest
     * @param $response IGearHttpResponse
     *
     * @return mixed
     */
    function executeResult($context, $request, $response);

    /**
     * @return IGearInnerActionResult
     */
    function getInnerResult();
}
/*</module>*/
?>