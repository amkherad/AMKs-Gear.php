<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\http\results;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\http\results\GearActionResultBase;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/
/*<module>*/
class GearBatchActionResult extends GearActionResultBase
{
    private
        $actions;

    public function __construct($actions)
    {
        $this->actions = $actions;
    }

    public function executeResult($context, $request, $response)
    {
        foreach ($this->actions as $action) {
            $action->executeResult($context, $request, $response);
        }
    }
}
/*</module>*/
?>