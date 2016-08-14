<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\auth;
    /*</namespace.current>*/
    /*<namespace.use>*/
use gear\data\core\entity\IGearIdEntity;
    /*</namespace.use>*/

    /*<bundles>*/
    /*</bundles>*/

/*<module>*/
interface IGearAuthUser extends IGearIdEntity
{
    //public
    //    $username,
    //    $mobile,
    //    $email,
    //    $firstName,
    //    $lastName,
//
    //    $passwordHash
    //;

    function getUsername();
    function getPassword();
    function getMobile();
    function getEmail();
    function getFirstName();
    function getLastName();
}
/*</module>*/
?>