<?php
//$SOURCE_LICENSE$

/*<requires>*/
//IGearInnerActionResult
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\http\results;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\http\IGearInnerActionResult;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearEmptyResult extends GearActionResultBase implements IGearInnerActionResult
{
    public function executeResult($context, $request, $response)
    {
        return null;
    }
}
/*</module>*/
?>