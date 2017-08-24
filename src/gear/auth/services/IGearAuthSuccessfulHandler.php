<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\auth\services;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\auth\services\IGearAuthSessionService;
use gear\auth\services\IGearAuthCookieService;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
interface IGearAuthSuccessfulHandler
{
    /**
     * @param $userObject
     * @param $username
     * @param $password
     * @param $params
     *
     * @return mixed Target result.
     */
    function onAuthSuccessful($userObject, $username, $password, $params);
}
/*</module>*/
?>