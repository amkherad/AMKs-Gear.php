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
class GearDefaultPasswordHashProvider implements IGearPasswordHashProvider
{
    /** @var string */
    private $algorithm;

    /**
     * GearDefaultPasswordHashProvider constructor.
     * @param $algorithm string Sets the hashing algorithm.
     */
    public function __construct($algorithm)
    {
        $this->algorithm = $algorithm;
    }

    public function getPasswordUsernameHash($password, $username)
    {
        return hash($this->algorithm, "$password|$username", false);
    }
    public function getPasswordHash($password)
    {
        return hash($this->algorithm, $password, false);
    }
}
/*</module>*/
?>