<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\plugins\orm\redbeanphp\auth;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\auth\datainterface\IGearAuthDataInterface;
use gear\auth\GearAuthenticationManager;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearRedBeanAuthManager extends GearAuthenticationManager
{
    public function __construct($userEntityClassLoader, $dataInterface, $passHashProvider, $successfulHandler)
    {
        parent::__construct($userEntityClassLoader, $dataInterface, $passHashProvider, $successfulHandler);
    }
}
/*</module>*/
?>