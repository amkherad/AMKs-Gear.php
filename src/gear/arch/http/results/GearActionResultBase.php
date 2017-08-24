<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\http\results;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\http\IGearActionResult;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
abstract class GearActionResultBase implements IGearActionResult
{
    private $innerResult;

    public function getInnerResult()
    {
        return $this->innerResult;
    }


    public abstract function executeResult($context, $request, $response);
}
/*</module>*/
?>