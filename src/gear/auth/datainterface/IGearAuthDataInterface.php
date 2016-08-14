<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\auth\datainterface;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\data\core\datainterface\IGearCrudService;
use gear\auth\GearUserBase;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IGearAuthDataInterface
{
    /**
     * Creates a user.
     * @param $userModel GearUserBase
     * @param $password
     * @return mixed
     */
    function createUser($userModel, $password);

    /**
     * Finds a user with given username and password.
     * @param $username
     * @param $password
     * @param $reason
     * @return mixed
     */
    function findUsernameAndPassword($username, $password, &$reason);

    /**
     * Finds a user with given username.
     * @param $username
     * @return mixed
     */
    function findUsername($username);
}
/*</module>*/
?>