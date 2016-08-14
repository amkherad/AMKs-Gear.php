<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\plugins\orm\redbeanphp\auth\entity;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\data\orm\redbeanphp\entity\GearRedBeanEntity;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/
class GearRedBeanAuthUser extends GearRedBeanEntity
{
    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setPasswordHash($passwordHash)
    {
        $this->passwordHash = $passwordHash;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }
}
/*</module>*/
?>