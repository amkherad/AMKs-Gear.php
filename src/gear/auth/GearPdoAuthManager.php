<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\auth;
/*</namespace.current>*/
/*<namespace.use>*/
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearPdoAuthManager extends GearAuthenticationManager
{
    public function __construct($userEntityClassLoader, $dataInterface, $passHashProvider, $successfulHandler)
    {
        parent::__construct($userEntityClassLoader, $dataInterface, $passHashProvider, $successfulHandler);
    }
}
/*</module>*/
?>