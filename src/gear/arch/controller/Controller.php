<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\arch\controller;

use gear\arch\core\InspectableClass;
use gear\arch\http\results\JsonResult;

/*</namespace.current>*/

/*<bundles>*/
Bundle::Arch('core/InspectableClass');
/*</bundles>*/

/*<module>*/

class Controller extends InspectableClass
{
    public function beginExecute()
    {
    }

    public function Json($mixed, $allowGet = false)
    {
        return new JsonResult($mixed, $allowGet);
    }
}

/*</module>*/
?>