<?php
//$SOURCE_LICENSE$

/*<requires>*/
//IGearAuthSuccessfulHandler
/*</requires>*/

/*<namespace.current>*/
namespace gear\auth\services;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\auth\services\IGearAuthSuccessfulHandler;
use gear\auth\services\GearDefaultAuthSessionService;
use gear\auth\services\GearDefaultAuthCookieService;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearDefaultWebSuccessfulHandler implements IGearAuthSuccessfulHandler
{
    /** @var IGearAuthSessionService */
    private $sessionService;
    /** @var IGearAuthCookieService */
    private $cookieService;

    /**
     * GearDefaultWebSuccessfulHandler constructor.
     * @param $sessionService IGearAuthSessionService
     * @param $cookieService IGearAuthCookieService
     */
    public function __construct($sessionService = null, $cookieService = null)
    {
        $this->sessionService = $sessionService == null
            ? new GearDefaultAuthSessionService()
            : $sessionService;

        $this->cookieService = $cookieService == null
            ? new GearDefaultAuthCookieService()
            : $cookieService;
    }

    public function onAuthSuccessful($userObject, $username, $password, $params)
    {

    }
}
/*</module>*/
?>