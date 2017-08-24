<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\auth\services;
/*</namespace.current>*/
/*<namespace.use>*/
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IGearPasswordHashProvider
{
    function getPasswordUsernameHash($password, $username);
    function getPasswordHash($password);
}
/*</module>*/
?>