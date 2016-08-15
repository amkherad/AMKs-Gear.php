<?php
//$SOURCE_LICENSE$

/*<namespace.current>*/
namespace gear\plugins\orm\redbeanphp\auth\entity;
/*</namespace.current>*/
/*<namespace.use>*/
use gear\auth\IGearAuthUser;
use gear\data\orm\redbeanphp\entity\GearRedBeanEntity;
/*</namespace.use>*/

/*<bundles>*/
/*</bundles>*/

/*<module>*/

class GearRedBeanAuthUser extends GearRedBeanEntity implements IGearAuthUser
{
    public function getId()
    {
        return $this->id;
    }


    public function getUsername()
    {
        return $this->username;
    }
    public function setUsername($username)
    {
        echo $username;
        $this->username = $username;
    }

    public function getPasswordHash()
    {
        return $this->passwordHash;
    }
    public function setPasswordHash($passwordHash)
    {
        $this->passwordHash = $passwordHash;
    }

    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    public function getMobile()
    {
        return $this->mobile;
    }
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }
}
/*</module>*/
?>