<?php
//$SOURCE_LICENSE$

/*<requires>*/
//GearStatusCodeResult
/*</requires>*/

/*<namespace.current>*/
namespace gear\arch\http\results;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\arch\http\results\GearStatusCodeResult;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearUnauthorizedResult extends GearStatusCodeResult
{
    public function __construct($message = null)
    {
        parent::__construct(401, $message);
    }
}
/*</module>*/
?>