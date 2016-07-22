<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace GearCms\unity;
    /*</namespace.current>*/
    /*<namespace.use>*/
use GearCms\helpers\gcAuthentication;
    /*</namespace.use>*/

    /*<bundles>*/
    /*</bundles>*/

/*<module>*/
trait Authorize
{
    public function authorize($context)
    {
        gcAuthentication::checkUserAuthorization($context, $this->getUserForAuthorization());
    }
}
/*</module>*/
?>