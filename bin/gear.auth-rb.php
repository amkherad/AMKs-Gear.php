<?php
//Bundle: Gear.Authentication.RedBeanPhp

/* Dependencies: */


/* Modules: */
class GearRedBeanAuthDataInterface implements IGearAuthDataInterface
{
    private
        $namespace,
        $entityName;

    /**
     * GearRedBeanAuthDataInterface constructor.
     * @param $namespace string
     * @param $entityName string
     */
    public function __construct($namespace, $entityName)
    {
        $this->namespace = $namespace;
        $this->entityName = $entityName;
    }

    /**
     * @param GearRedBeanAuthUser $userModel
     *
     * @return mixed|void
     */
    public function createUser($userModel, &$reason)
    {
        $service = new GearRedBeanDataInterface($this->entityName);
        try {
            $service->insert($userModel);
        } finally {
            $service->dispose();
        }

        $reason = GearAuthenticationManager::AuthUserSuccessful;
        return $userModel;
    }

    public function findUsernameAndPassword($username, $password, &$reason)
    {
        $service = new GearRedBeanDataInterface($this->entityName);
        try {
            /** @var $user IGearAuthUser */
            $user = $service->query()
                ->isEqual('username', $username)
                ->selectOne();
        } finally {
            $service->dispose();
        }

        if ($user == null) {
            $reason = GearAuthenticationManager::AuthUserNotFound;
            return null;
        } else {
            $pass = $user->getPasswordHash();

            if ($pass == $password) {
                $reason = GearAuthenticationManager::AuthUserSuccessful;
                return $user;
            } else {
                $reason = GearAuthenticationManager::AuthUserWrongPassword;
                return null;
            }
        }
    }

    public function findUsername($username, &$reason)
    {
        $service = new GearRedBeanDataInterface($this->entityName);
        try {
            /** @var $user IGearAuthUser */
            $user = $service->query()
                ->isEqual('username', $username)
                ->selectOne();
        } finally {
            $service->dispose();
        }

        if ($user == null) {
            $reason = GearAuthenticationManager::AuthUserNotFound;
            return null;
        } else {
            $reason = GearAuthenticationManager::AuthUserSuccessful;
            return $user;
        }
    }
}
class GearRedBeanAuthManager extends GearAuthenticationManager
{
    public function __construct($userEntityClassLoader, $dataInterface, $passHashProvider, $successfulHandler)
    {
        parent::__construct($userEntityClassLoader, $dataInterface, $passHashProvider, $successfulHandler);
    }
}
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

    public function getTell()
    {
        return $this->tell;
    }
    public function setTell($tell)
    {
        $this->tell = $tell;
    }

    public function getMiddleName()
    {
        return $this->middleName;
    }
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;
    }
}


/* Generals: */

