<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\http\results;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\http\IActionResult;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
abstract class ActionResultBase implements IActionResult
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