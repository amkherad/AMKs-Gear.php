<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\auth\meta;
    /*</namespace.current>*/
/*<namespace.use>*/
use gear\auth\IGearAuthUser;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearAuthenticationResult
{
    /** @var bool */
    var $isSuccessful;
    /** @var string */
    var $message;
    /** @var string */
    var $reason;
    /** @var object */
    var $result;
    /** @var IGearAuthUser */
    var $user;

    /**
     * Creates an instance of GearAuthenticationResult
     *
     * @param $isSuccessful bool Indicates the result of authentication.
     * @param $message string Provides a message for end-user
     * @param $reason string Provides a reason for status query.
     * @param $result string Stores the result returned by successful handler.
     * @param $user IGearAuthUser Stores the user retrieved from database for login and register.
     */
    public function __construct($isSuccessful, $message, $reason, $result, $user)
    {
        $this->isSuccessful = $isSuccessful;
        $this->message = $message;
        $this->reason = $reason;
        $this->result = $result;
        $this->user = $user;
    }

    /**
     * Indicates that an authentication is successful.
     *
     * @return bool
     */
    public function getIsSuccessful()
    {
        return $this->isSuccessful;
    }

    /**
     * Gets the end-user message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Determines the reason of unsuccessful authentication, invalid-username or invalid-password.
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Returns pure authentication result.
     *
     * @return object
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return IGearAuthUser
     */
    public function getUser()
    {
        return $this->user;
    }
}
/*</module>*/
?>