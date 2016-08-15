<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\auth\datainterface;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\auth\IGearAuthUser;
use gear\data\core\datainterface\IGearCrudService;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IGearAuthDataInterface
{
    /**
     * Creates a user.
     * @param $userModel IGearAuthUser
     * @param $reason
     * @return IGearAuthUser
     */
    function createUser($userModel, &$reason);

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
     * @param $reason
     * @return mixed
     */
    function findUsername($username, &$reason);
}
/*</module>*/
?>